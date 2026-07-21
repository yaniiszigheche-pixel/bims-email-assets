(function () {
  'use strict';
  var cfg = window.VTW || {};
  var root = document.querySelector('.vtw-root');
  if (!root) return;

  var $ = function (id) { return document.getElementById(id); };

  // disc-local screen-center angle (deg from top, clockwise) per prize
  var CENTERS = { mealdeal: 0, burger: 67.5, fries: 112.5, drink: 157.5, applepie: 202.5, swirl: 247.5, churros: 292.5 };
  var META = {
    burger:   { img: 'burger-sq.png',    title: 'A FREE NASHVILLE BURGER' },
    fries:    { img: 'fries-sq.png',      title: 'FREE FRIES' },
    drink:    { img: 'drink-sq.png',      title: 'ANY FREE DRINK' },
    applepie: { img: 'apple-pie-sq.png',  title: 'A FREE APPLE PIE' },
    swirl:    { img: 'swirl-ice-sq.png',  title: 'A FREE SWIRL ICE' },
    churros:  { img: 'churros-sq.png',    title: '3 FREE CHURROS' },
    mealdeal: { img: 'medium-meal-sq.png', title: 'A FREE MEDIUM MEAL' }
  };
  var TERMS = '*Promotion closes 31 July 2026. Free prize — no purchase necessary; entry requires voting for Bim’s in the Uber Eats Awards. Open to UK residents aged 18+; one prize per person. Food vouchers, including the free medium meal, are valid in-store until 31 July 2026, subject to availability. No cash alternative; prizes are non-transferable. Promoter: Bim’s, 131 High St North, London E6 1HZ.';

  var LS = 'vtw_state_v1';
  var spinning = false;
  var currentRedeemUrl = ''; // set by renderResult(), read by the redeem modal on click
  var currentRedeemed = false; // once true, the redeem button locks — one redeem per win

  if ($('vtw-terms-1')) $('vtw-terms-1').textContent = TERMS;
  if ($('vtw-terms-4')) $('vtw-terms-4').textContent = TERMS;

  function show(n) {
    var screens = root.querySelectorAll('.vtw-screen');
    for (var i = 0; i < screens.length; i++) screens[i].style.display = 'none';
    var el = $('vtw-screen-' + n);
    if (el) el.style.display = 'flex';
  }

  function saveState(s) { try { localStorage.setItem(LS, JSON.stringify(s)); } catch (e) {} }
  function loadState() { try { return JSON.parse(localStorage.getItem(LS) || 'null'); } catch (e) { return null; } }

  /* ---------- restore where they left off on refresh / return ---------- */
  var saved = loadState();
  if (saved && saved.done) {
    // finished a spin -> keep the winning page
    renderResult(saved.prize, saved.code, saved.redeemUrl, saved.stamp, saved.redeemed);
  } else if (saved && saved.unlocked) {
    // already voted & unlocked their spin -> drop them straight on the wheel
    show(3);
  }

  /* ---------- Screen 1: vote ---------- */
  if ($('vtw-vote')) $('vtw-vote').addEventListener('click', function () {
    try { window.open(cfg.voteUrl, '_blank', 'noopener'); } catch (e) {}
    // remember they've unlocked, so returning to the page lands on the wheel (not screen 1)
    var st = loadState() || {};
    st.unlocked = true;
    saveState(st);
    show(2);
    var secs = (cfg.unlockSecs || 20) * 1000;
    setTimeout(function () { show(3); }, secs);
  });

  /* ---------- Screen 2: reopen vote ---------- */
  if ($('vtw-reopen')) $('vtw-reopen').addEventListener('click', function () {
    try { window.open(cfg.voteUrl, '_blank', 'noopener'); } catch (e) {}
  });

  /* ---------- Redeem modal: loads the redeem link in an in-page overlay,
     so winners never leave the BIM'S site to redeem. One redeem per win —
     the button locks itself the moment it's tapped. ---------- */
  function lockRedeemButton() {
    var btn = $('vtw-redeem-link');
    if (!btn) return;
    btn.disabled = true;
    btn.textContent = 'Already redeemed';
    btn.style.cursor = 'not-allowed';
    btn.style.background = 'rgba(20,20,20,.25)';
    btn.style.color = 'rgba(20,20,20,.5)';
  }
  function unlockRedeemButton() {
    var btn = $('vtw-redeem-link');
    if (!btn) return;
    btn.disabled = false;
    btn.textContent = 'Redeem now';
    btn.style.cursor = 'pointer';
    btn.style.background = '#141414';
    btn.style.color = '#FFCE27';
  }
  function openRedeemModal() {
    if (!currentRedeemUrl || currentRedeemed) return;
    currentRedeemed = true;
    var st = loadState() || {};
    st.redeemed = true;
    saveState(st);
    lockRedeemButton();
    $('vtw-redeem-frame').src = currentRedeemUrl;
    $('vtw-redeem-modal').style.display = 'flex';
  }
  function closeRedeemModal() {
    $('vtw-redeem-modal').style.display = 'none';
    $('vtw-redeem-frame').src = 'about:blank';
  }
  if ($('vtw-redeem-link')) $('vtw-redeem-link').addEventListener('click', openRedeemModal);
  if ($('vtw-redeem-close')) $('vtw-redeem-close').addEventListener('click', closeRedeemModal);
  if ($('vtw-redeem-modal')) $('vtw-redeem-modal').addEventListener('click', function (e) {
    if (e.target.id === 'vtw-redeem-modal') closeRedeemModal();
  });

  /* ---------- Screen 3: spin ---------- */
  if ($('vtw-spin')) $('vtw-spin').addEventListener('click', doSpin);

  function doSpin() {
    if (spinning) return;
    // already spun before? jump straight to the saved result.
    var st = loadState();
    if (st && st.done) { renderResult(st.prize, st.code, st.redeemUrl, st.stamp, st.redeemed); return; }

    spinning = true;
    $('vtw-spin').style.display = 'none';
    $('vtw-spin-wait').style.display = 'block';

    var body = 'action=vtw_spin&nonce=' + encodeURIComponent(cfg.nonce);
    fetch(cfg.ajax, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      credentials: 'same-origin',
      body: body
    })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        // Server blocked the spin (cookie already used, IP over daily cap, or prizes sold out).
        if (res && !res.success && res.data &&
            (res.data.reason === 'spun' || res.data.reason === 'ip' || res.data.reason === 'soldout')) {
          var w = $('vtw-spin-wait');
          w.style.fontSize = '15px';
          w.style.lineHeight = '1.4';
          w.style.padding = '18px 20px';
          w.textContent = res.data.message || 'You’ve already used your spin.';
          return;
        }
        if (!res || !res.success) { throw new Error('spin failed'); }
        var d = res.data;
        animateTo(d.prize, function () {
          var stamp = d.stamp || new Date().toLocaleString('en-GB');
          saveState({ done: true, prize: d.prize, code: d.code || '', redeemUrl: d.redeemUrl || '', stamp: stamp, redeemed: false });
          renderResult(d.prize, d.code || '', d.redeemUrl || '', stamp, false);
        });
      })
      .catch(function () {
        spinning = false;
        $('vtw-spin').style.display = 'block';
        $('vtw-spin-wait').style.display = 'none';
        alert('Something went wrong — please try again.');
      });
  }

  function animateTo(prize, done) {
    var wheel = $('vtw-wheel');
    var center = CENTERS.hasOwnProperty(prize) ? CENTERS[prize] : 0;
    var halfSlice = prize === 'mealdeal' ? 34 : 15;
    var jitter = (Math.random() * 2 - 1) * halfSlice;
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var turns = reduce ? 2 : 7;
    var dur = reduce ? 900 : (cfg.spinSecs || 5) * 1000;
    var rotation = turns * 360 - center + jitter;
    if (wheel) {
      wheel.style.transition = 'transform ' + dur + 'ms cubic-bezier(.16,1,.32,1)';
      wheel.style.transform = 'rotate(' + rotation + 'deg)';
    }
    setTimeout(done, dur + 650);
  }

  /* ---------- Screen 4: render result ---------- */
  function renderResult(prize, code, redeemUrl, stamp, redeemed) {
    var m = META[prize] || META.burger;
    var img = $('vtw-result-img');
    var emoji = $('vtw-result-emoji');

    if (m.img) {
      img.src = cfg.assets + m.img;
      img.style.display = 'block';
      emoji.style.display = 'none';
    } else {
      img.style.display = 'none';
      emoji.textContent = m.emoji || '';
      emoji.style.display = 'block';
    }

    $('vtw-result-title').textContent = m.title;

    if (redeemUrl) {
      currentRedeemUrl = redeemUrl;
      currentRedeemed = !!redeemed;
      $('vtw-redeem-stamp').textContent = stamp || '';
      $('vtw-redeem').style.display = 'block';
      $('vtw-code-block').style.display = 'none';
      if (currentRedeemed) lockRedeemButton(); else unlockRedeemButton();
    } else {
      $('vtw-code').textContent = code;
      $('vtw-stamp').textContent = stamp || '';
      $('vtw-code-block').style.display = 'block';
      $('vtw-redeem').style.display = 'none';
    }

    $('vtw-treat').style.display = 'block';
    show(4);
  }
})();
