<?php if ( ! defined( 'ABSPATH' ) ) exit; $a = VTW_URL . 'assets/'; ?>
<div class="vtw-root" style="width:100%;font-family:'Fredoka',sans-serif;color:#141414;">

  <!-- ===================== SCREEN 1 — VOTE GATE ===================== -->
  <section class="vtw-screen" id="vtw-screen-1" style="position:relative;min-height:100vh;width:100%;overflow:hidden;background:radial-gradient(circle at 50% 34%, #FFEA96 0%, #FFD23C 48%, #F6B70E 100%);display:flex;flex-direction:column;align-items:center;padding:0 0 40px;">
    <div style="position:absolute;inset:-25%;background:repeating-conic-gradient(from 8deg at 50% 30%, rgba(255,255,255,.05) 0deg 5deg, rgba(255,255,255,0) 5deg 11deg);pointer-events:none;"></div>

    <!-- marquees -->
    <div style="position:relative;width:100%;display:flex;flex-direction:column;gap:6px;padding-top:14px;z-index:2;">
      <div style="width:118%;margin-left:-9%;overflow:hidden;background:#141414;padding:8px 0;transform:rotate(-1.5deg);">
        <div style="display:flex;width:max-content;animation:stwMarquee 9s linear infinite;font-family:'Passion One',sans-serif;font-weight:900;font-size:15px;letter-spacing:1px;color:#FFCE27;text-transform:uppercase;">
          <?php for ( $i = 0; $i < 6; $i++ ) : ?><span style="white-space:nowrap;flex-shrink:0;padding-left:16px;">VOTE TO WIN <span style="color:#F16266;">&#9733;</span> FREE BURGER <span style="color:#F16266;">&#9733;</span> FREE MEDIUM MEAL <span style="color:#F16266;">&#9733;</span> FREE CHURROS <span style="color:#F16266;">&#9733;</span> </span><?php endfor; ?>
        </div>
      </div>
      <div style="width:118%;margin-left:-9%;overflow:hidden;background:#F16266;padding:7px 0;transform:rotate(1.5deg);">
        <div style="display:flex;width:max-content;animation:stwMarquee 9s linear infinite reverse;font-family:'Passion One',sans-serif;font-weight:900;font-size:15px;letter-spacing:1px;color:#141414;text-transform:uppercase;">
          <?php for ( $i = 0; $i < 6; $i++ ) : ?><span style="white-space:nowrap;flex-shrink:0;padding-left:16px;">BIM&rsquo;S x UBER EATS <span style="color:#fff;">&#9679;</span> ONE SPIN ONE PRIZE <span style="color:#fff;">&#9679;</span> GAME ON <span style="color:#fff;">&#9679;</span> </span><?php endfor; ?>
        </div>
      </div>
    </div>

    <div style="position:relative;z-index:2;width:100%;max-width:460px;padding:0 22px;">
      <div style="display:flex;align-items:center;gap:10px;margin-top:26px;">
        <img src="<?php echo esc_url( $a . 'bims-logo.png' ); ?>" alt="BIM'S" style="height:40px;width:auto;display:block;">
        <span style="width:6px;height:6px;border-radius:50%;background:#F16266;"></span>
        <span style="font-family:'Fredoka',sans-serif;font-weight:700;font-size:11.5px;letter-spacing:1.4px;color:rgba(20,20,20,.62);">UBER EATS AWARDS 2026</span>
      </div>

      <div style="margin-top:14px;line-height:.8;">
        <div style="font-family:'Passion One',sans-serif;font-weight:900;color:#EC1C2A;font-size:clamp(58px,17vw,84px);letter-spacing:1px;-webkit-text-stroke:6px #fff;paint-order:stroke;filter:drop-shadow(3px 5px 0 rgba(0,0,0,.15));transform:rotate(-2deg);">VOTE&nbsp;TO</div>
        <div style="font-family:'Passion One',sans-serif;font-weight:900;color:#EC1C2A;font-size:clamp(96px,30vw,150px);letter-spacing:2px;-webkit-text-stroke:10px #fff;paint-order:stroke;filter:drop-shadow(4px 7px 0 rgba(0,0,0,.17));margin-top:2px;">WIN!</div>
      </div>
      <p style="font-family:'Fredoka',sans-serif;font-weight:500;font-size:16.5px;line-height:1.45;color:#1a1a1a;margin:16px 0 0;max-width:410px;">One free spin for backing <b style="font-weight:700;">Bim&rsquo;s</b>. Every slice is a free treat — land on the top prize for a <b style="font-weight:700;">free medium meal</b> of your choice.</p>

      <div style="position:relative;margin-top:22px;background:#fff;border:3px solid #141414;border-radius:26px;box-shadow:8px 8px 0 #F16266;padding:16px 16px 20px;transform:rotate(-1deg);overflow:hidden;">
        <div style="position:absolute;left:50%;top:44%;width:340px;height:340px;transform:translate(-50%,-50%);background:repeating-conic-gradient(from 0deg, rgba(255,206,39,.5) 0deg 6deg, rgba(255,206,39,0) 6deg 15deg);-webkit-mask:radial-gradient(circle,#000 18%,transparent 62%);mask:radial-gradient(circle,#000 18%,transparent 62%);animation:stwSpinRays 70s linear infinite;"></div>
        <div style="position:relative;display:inline-block;background:#EC1C2A;color:#fff;font-family:'Passion One',sans-serif;font-weight:900;font-size:19px;letter-spacing:1.5px;padding:5px 18px;border-radius:8px;border:3px solid #fff;box-shadow:0 4px 0 #a5121d;transform:rotate(-1.5deg);">TOP PRIZE</div>
        <img src="<?php echo esc_url( $a . 'medium-meal-sq.png' ); ?>" alt="Free medium meal" style="position:relative;display:block;width:78%;max-width:280px;height:auto;margin:6px auto 2px;filter:drop-shadow(0 16px 16px rgba(0,0,0,.22));animation:stwFloat 5.5s ease-in-out infinite;">
        <div style="position:relative;text-align:center;font-family:'Passion One',sans-serif;font-weight:900;font-size:clamp(30px,9vw,42px);line-height:.9;color:#EC1C2A;-webkit-text-stroke:6px #fff;paint-order:stroke;filter:drop-shadow(2px 4px 0 rgba(0,0,0,.14));">A <span style="color:#FBB50E;-webkit-text-stroke:6px #141414;font-size:1.25em;filter:drop-shadow(3px 4px 0 #F16266);">FREE</span> MEDIUM MEAL!<span style="font-size:.42em;vertical-align:super;-webkit-text-stroke:3px #fff;">*</span></div>
        <div style="position:relative;text-align:center;font-family:'Fredoka',sans-serif;font-weight:600;font-size:13px;color:rgba(20,20,20,.6);margin-top:6px;">Any burger, any side, any drink — your call.</div>
      </div>

      <div style="display:flex;align-items:center;gap:8px;margin:26px 0 14px;">
        <span style="height:3px;flex:1;background:rgba(20,20,20,.18);border-radius:2px;"></span>
        <span style="font-family:'Passion One',sans-serif;font-weight:900;font-size:17px;letter-spacing:.5px;color:#141414;text-transform:uppercase;">Also on the wheel</span>
        <span style="height:3px;flex:1;background:rgba(20,20,20,.18);border-radius:2px;"></span>
      </div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px 10px;">
        <div style="display:flex;flex-direction:column;align-items:center;gap:9px;animation:stwBob 4.2s ease-in-out infinite;">
          <div style="width:100%;aspect-ratio:1;border-radius:50%;background:#fff;border:5px solid #F16266;box-shadow:0 8px 16px rgba(0,0,0,.18);display:flex;align-items:center;justify-content:center;padding:11px;overflow:hidden;"><img src="<?php echo esc_url( $a . 'burger-sq.png' ); ?>" style="max-width:118%;max-height:118%;object-fit:contain;"></div>
          <span style="font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;color:#141414;">NASHVILLE BURGER</span>
        </div>
        <div style="display:flex;flex-direction:column;align-items:center;gap:9px;animation:stwBob 4.6s ease-in-out .3s infinite;">
          <div style="width:100%;aspect-ratio:1;border-radius:50%;background:#fff;border:5px solid #FFCE27;box-shadow:0 8px 16px rgba(0,0,0,.18);display:flex;align-items:center;justify-content:center;padding:10px;overflow:hidden;"><img src="<?php echo esc_url( $a . 'fries-sq.png' ); ?>" style="max-width:116%;max-height:116%;object-fit:contain;"></div>
          <span style="font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;color:#141414;">FRIES</span>
        </div>
        <div style="display:flex;flex-direction:column;align-items:center;gap:9px;animation:stwBob 4.4s ease-in-out .6s infinite;">
          <div style="width:100%;aspect-ratio:1;border-radius:50%;background:#fff;border:5px solid #47BFAB;box-shadow:0 8px 16px rgba(0,0,0,.18);display:flex;align-items:center;justify-content:center;padding:8px;overflow:hidden;"><img src="<?php echo esc_url( $a . 'drink-sq.png' ); ?>" style="max-width:112%;max-height:126%;object-fit:contain;"></div>
          <span style="font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;color:#141414;">ANY DRINK</span>
        </div>
        <div style="display:flex;flex-direction:column;align-items:center;gap:9px;animation:stwBob 4.5s ease-in-out .2s infinite;">
          <div style="width:100%;aspect-ratio:1;border-radius:50%;background:#fff;border:5px solid #47BFAB;box-shadow:0 8px 16px rgba(0,0,0,.18);display:flex;align-items:center;justify-content:center;padding:12px;overflow:hidden;"><img src="<?php echo esc_url( $a . 'apple-pie-sq.png' ); ?>" style="max-width:120%;max-height:120%;object-fit:contain;"></div>
          <span style="font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;color:#141414;">APPLE PIE</span>
        </div>
        <div style="display:flex;flex-direction:column;align-items:center;gap:9px;animation:stwBob 4.3s ease-in-out .5s infinite;">
          <div style="width:100%;aspect-ratio:1;border-radius:50%;background:#fff;border:5px solid #FFCE27;box-shadow:0 8px 16px rgba(0,0,0,.18);display:flex;align-items:center;justify-content:center;padding:9px;overflow:hidden;"><img src="<?php echo esc_url( $a . 'swirl-ice-sq.png' ); ?>" style="max-width:116%;max-height:122%;object-fit:contain;"></div>
          <span style="font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;color:#141414;">SWIRL ICE</span>
        </div>
        <div style="display:flex;flex-direction:column;align-items:center;gap:9px;animation:stwBob 4.7s ease-in-out .8s infinite;">
          <div style="width:100%;aspect-ratio:1;border-radius:50%;background:#fff;border:5px solid #F16266;box-shadow:0 8px 16px rgba(0,0,0,.18);display:flex;align-items:center;justify-content:center;padding:11px;overflow:hidden;"><img src="<?php echo esc_url( $a . 'churros-sq.png' ); ?>" style="max-width:118%;max-height:118%;object-fit:contain;"></div>
          <span style="font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;color:#141414;">3 CHURROS</span>
        </div>
      </div>

      <div style="display:flex;align-items:center;justify-content:center;gap:7px;margin:26px 0 20px;flex-wrap:wrap;">
        <span style="font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;letter-spacing:.4px;text-transform:uppercase;color:#fff;background:#141414;padding:6px 13px;border-radius:999px;">Vote</span>
        <span style="color:#141414;font-weight:700;">&rarr;</span>
        <span style="font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;letter-spacing:.4px;text-transform:uppercase;color:#fff;background:#141414;padding:6px 13px;border-radius:999px;">Come back</span>
        <span style="color:#141414;font-weight:700;">&rarr;</span>
        <span style="font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;letter-spacing:.4px;text-transform:uppercase;color:#fff;background:#F16266;border:2px solid #141414;padding:5px 13px;border-radius:999px;">Spin</span>
      </div>

      <button id="vtw-vote" style="display:block;width:100%;border:3px solid #141414;cursor:pointer;background:#141414;color:#FFCE27;font-family:'Passion One',sans-serif;font-weight:900;font-size:24px;letter-spacing:.6px;text-transform:uppercase;padding:16px 22px;border-radius:16px;box-shadow:6px 6px 0 rgba(0,0,0,.22);">Vote &amp; unlock my spin</button>
      <p style="font-family:'Fredoka',sans-serif;font-weight:500;font-size:12.5px;line-height:1.45;color:rgba(20,20,20,.62);margin:12px 4px 0;text-align:center;">Voting is free and takes seconds — come back to this page once you&rsquo;ve voted to take your spin.</p>
      <p style="font-family:'Fredoka',sans-serif;font-weight:400;font-size:11px;line-height:1.4;color:rgba(20,20,20,.5);margin:18px 2px 0;text-align:center;" id="vtw-terms-1"></p>
    </div>
  </section>

  <!-- ===================== SCREEN 2 — WAIT / VOTE ===================== -->
  <section class="vtw-screen" id="vtw-screen-2" style="display:none;min-height:100vh;width:100%;overflow:hidden;background-color:#141414;background-image:radial-gradient(rgba(255,255,255,.06) 1.4px,transparent 1.5px);background-size:16px 16px;display:none;flex-direction:column;align-items:center;justify-content:center;padding:34px 24px;">
    <div style="width:100%;max-width:440px;text-align:center;">
      <div style="font-size:clamp(54px,15vw,74px);line-height:1;">&#128499;&#65039;</div>
      <h1 style="font-family:'Passion One',sans-serif;font-weight:900;font-size:clamp(42px,11vw,60px);color:#FFCE27;margin:12px 0 0;text-transform:uppercase;letter-spacing:.5px;line-height:.86;">Go vote for<br>Bim&rsquo;s</h1>
      <p style="font-family:'Fredoka',sans-serif;font-weight:500;font-size:15.5px;line-height:1.5;color:rgba(255,255,255,.82);margin:14px auto 0;max-width:340px;">Cast your vote in the Uber Eats Awards tab that just opened, then come back here — your spin unlocks automatically.</p>
      <div style="display:inline-flex;align-items:center;gap:8px;margin-top:28px;">
        <span style="width:9px;height:9px;border-radius:50%;background:#F16266;animation:stwPulse 1.2s ease-in-out infinite;"></span>
        <span style="width:9px;height:9px;border-radius:50%;background:#F16266;animation:stwPulse 1.2s ease-in-out .2s infinite;"></span>
        <span style="width:9px;height:9px;border-radius:50%;background:#F16266;animation:stwPulse 1.2s ease-in-out .4s infinite;"></span>
        <span style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:12.5px;letter-spacing:1.3px;text-transform:uppercase;color:rgba(255,255,255,.5);margin-left:5px;">Unlocking your spin</span>
      </div>
      <div style="margin-top:30px;display:flex;flex-direction:column;gap:12px;">
        <button id="vtw-reopen" style="display:block;width:100%;border:3px solid #FFCE27;cursor:pointer;background:transparent;color:#FFCE27;font-family:'Passion One',sans-serif;font-weight:900;font-size:20px;letter-spacing:.6px;text-transform:uppercase;padding:14px 22px;border-radius:16px;">Reopen voting page</button>
      </div>
    </div>
  </section>

  <!-- ===================== SCREEN 3 — WHEEL ===================== -->
  <section class="vtw-screen" id="vtw-screen-3" style="display:none;position:relative;min-height:100vh;width:100%;overflow:hidden;background:radial-gradient(circle at 50% 42%, #FFEA96 0%, #FFD23C 48%, #F6B70E 100%);display:none;flex-direction:column;align-items:center;justify-content:center;padding:30px 22px;">
    <div style="position:absolute;inset:-25%;background:repeating-conic-gradient(from 8deg at 50% 42%, rgba(255,255,255,.05) 0deg 5deg, rgba(255,255,255,0) 5deg 11deg);pointer-events:none;"></div>
    <div style="position:relative;z-index:2;width:100%;max-width:440px;text-align:center;">
      <h2 style="font-family:'Passion One',sans-serif;font-weight:900;font-size:clamp(40px,11vw,58px);color:#141414;margin:0 0 4px;text-transform:uppercase;letter-spacing:.5px;line-height:.9;">Spin the wheel!</h2>
      <p style="font-family:'Fredoka',sans-serif;font-weight:500;font-size:14.5px;color:#2a2a2a;margin:0 0 20px;">Every slice is a <b style="font-weight:700;">free treat</b> — land on the black slice for a <b style="font-weight:700;">free medium meal.</b></p>

      <div style="position:relative;width:312px;height:312px;margin:0 auto;">
        <div style="position:absolute;left:-10px;top:6px;z-index:6;font-family:'Passion One',sans-serif;font-weight:900;font-size:14px;text-transform:uppercase;letter-spacing:.5px;color:#fff;background:#F16266;border:3px solid #141414;padding:9px 11px;border-radius:50%;transform:rotate(-14deg);box-shadow:3px 3px 0 #141414;animation:stwWobble 3.5s ease-in-out infinite;">1 spin!</div>
        <div style="position:absolute;inset:0;border-radius:50%;background:#F16266;box-shadow:0 16px 34px rgba(0,0,0,.3), inset 0 0 0 4px #e0565b;">
          <?php for ( $i = 0; $i < 12; $i++ ) : $deg = $i * 30; $delay = $i * 0.13; ?>
          <span style="position:absolute;left:50%;top:50%;width:10px;height:10px;margin:-5px;border-radius:50%;background:#FFE896;box-shadow:0 0 6px rgba(255,220,90,.9);transform:rotate(<?php echo $deg; ?>deg) translateY(-148px);animation:stwBulb 1.6s ease-in-out <?php echo $delay; ?>s infinite;"></span>
          <?php endfor; ?>
          <div id="vtw-wheel" style="position:absolute;left:16px;top:16px;width:280px;height:280px;border-radius:50%;background:conic-gradient(from -45deg, #141414 0deg 90deg, #F16266 90deg 135deg, #ffffff 135deg 180deg, #47BFAB 180deg 225deg, #FFCE27 225deg 270deg, #F16266 270deg 315deg, #47BFAB 315deg 360deg);box-shadow:inset 0 0 0 3px rgba(0,0,0,.06);will-change:transform;">
            <div style="position:absolute;left:50%;top:16px;transform:translateX(-50%);font-family:'Passion One',sans-serif;font-weight:900;font-size:34px;line-height:.86;color:#FBB50E;text-shadow:0 2px 4px rgba(0,0,0,.5);">FREE</div>
            <div style="position:absolute;left:50%;top:56px;transform:translateX(-50%);font-family:'Fredoka',sans-serif;font-weight:700;font-size:11px;letter-spacing:1.5px;color:#fff;white-space:nowrap;">&#9733; MEAL &#9733;</div>
            <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%) rotate(67.5deg) translateY(-92px) rotate(-90deg);font-family:'Fredoka',sans-serif;font-weight:700;font-size:14px;color:#fff;white-space:nowrap;text-shadow:0 1px 2px rgba(0,0,0,.25);">BURGER</div>
            <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%) rotate(112.5deg) translateY(-92px) rotate(-90deg);font-family:'Fredoka',sans-serif;font-weight:700;font-size:14px;color:#141414;white-space:nowrap;">FRIES</div>
            <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%) rotate(157.5deg) translateY(-92px) rotate(-90deg);font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;color:#fff;white-space:nowrap;text-shadow:0 1px 2px rgba(0,0,0,.25);">ANY DRINK</div>
            <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%) rotate(202.5deg) translateY(-92px) rotate(-90deg);font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;color:#141414;white-space:nowrap;">APPLE PIE</div>
            <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%) rotate(247.5deg) translateY(-92px) rotate(-90deg);font-family:'Fredoka',sans-serif;font-weight:700;font-size:12.5px;color:#fff;white-space:nowrap;text-shadow:0 1px 2px rgba(0,0,0,.25);">SWIRL ICE</div>
            <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%) rotate(292.5deg) translateY(-92px) rotate(-90deg);font-family:'Fredoka',sans-serif;font-weight:700;font-size:12px;color:#fff;white-space:nowrap;text-shadow:0 1px 2px rgba(0,0,0,.25);">3 CHURROS</div>
          </div>
          <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:74px;height:74px;border-radius:50%;background:#fff;border:4px solid #141414;box-shadow:0 4px 10px rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center;z-index:3;">
            <img src="<?php echo esc_url( $a . 'bims-burger.png' ); ?>" style="width:40px;height:auto;object-fit:contain;">
          </div>
        </div>
        <div style="position:absolute;top:-10px;left:50%;transform:translateX(-50%);z-index:5;width:0;height:0;border-left:18px solid transparent;border-right:18px solid transparent;border-top:32px solid #141414;filter:drop-shadow(0 3px 3px rgba(0,0,0,.3));"></div>
      </div>

      <div style="margin-top:28px;">
        <button id="vtw-spin" style="display:block;width:100%;border:3px solid #141414;cursor:pointer;background:#141414;color:#FFCE27;font-family:'Passion One',sans-serif;font-weight:900;font-size:24px;letter-spacing:.7px;text-transform:uppercase;padding:16px 22px;border-radius:16px;box-shadow:6px 6px 0 rgba(0,0,0,.22);animation:stwBtn 1.8s ease-in-out infinite;">Spin the wheel</button>
        <div id="vtw-spin-wait" style="display:none;width:100%;border:3px solid rgba(0,0,0,.2);background:rgba(0,0,0,.12);color:rgba(20,20,20,.5);font-family:'Passion One',sans-serif;font-weight:900;font-size:24px;letter-spacing:.7px;text-transform:uppercase;padding:16px 22px;border-radius:16px;text-align:center;">Good luck&hellip;</div>
      </div>
      <p style="font-family:'Fredoka',sans-serif;font-weight:500;font-size:12.5px;color:rgba(20,20,20,.62);margin:12px 0 0;">One spin only — no take-backs!</p>
    </div>
  </section>

  <!-- ===================== SCREEN 4 — RESULT ===================== -->
  <section class="vtw-screen" id="vtw-screen-4" style="display:none;min-height:100vh;width:100%;overflow:hidden;background-color:#FBF6E8;background-image:radial-gradient(rgba(0,0,0,.05) 1.3px,transparent 1.4px);background-size:16px 16px;display:none;flex-direction:column;align-items:center;justify-content:center;padding:40px 22px;">
    <div style="width:100%;max-width:440px;text-align:center;">

      <!-- TREAT WON -->
      <div id="vtw-treat" style="display:none;">
        <div style="width:150px;height:150px;margin:0 auto;border-radius:50%;background:#fff;border:6px solid #47BFAB;box-shadow:0 12px 24px rgba(0,0,0,.16);display:flex;align-items:center;justify-content:center;padding:16px;overflow:hidden;animation:stwPop .55s cubic-bezier(.2,1.4,.4,1) both;">
          <img id="vtw-result-img" src="" style="max-width:118%;max-height:118%;object-fit:contain;">
          <span id="vtw-result-emoji" style="display:none;font-size:64px;line-height:1;"></span>
        </div>
        <div style="font-family:'Caveat Brush',cursive;color:#F16266;font-size:26px;line-height:1;margin-top:16px;">you won&hellip;</div>
        <h1 id="vtw-result-title" style="font-family:'Passion One',sans-serif;font-weight:900;font-size:clamp(38px,10vw,52px);color:#141414;margin:2px 0 0;line-height:.94;text-transform:uppercase;letter-spacing:.5px;"></h1>

        <!-- REDEEM VIA APP (default — no counter code) -->
        <div id="vtw-redeem" style="display:none;">
          <p style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:18px;line-height:1.4;color:rgba(20,20,20,.85);margin:12px 0 0;">To redeem, you&rsquo;ll need the BIM&rsquo;S app. Don&rsquo;t have it yet? Download it and create an account below.</p>
          <a href="https://loyaltyapp.bims.co.uk" target="_blank" rel="noopener" style="display:block;width:100%;box-sizing:border-box;border:3px solid #141414;background:#fff;color:#141414;font-family:'Passion One',sans-serif;font-weight:900;font-size:19px;letter-spacing:.5px;text-transform:uppercase;text-decoration:none;text-align:center;padding:12px 18px;border-radius:14px;margin:16px 4px 0;box-shadow:4px 4px 0 rgba(0,0,0,.14);">Get the BIM&rsquo;S app</a>
          <p style="font-family:'Fredoka',sans-serif;font-weight:600;font-size:13px;line-height:1.45;color:#F16266;margin:14px 6px 0;">If the email or account details on your BIM&rsquo;S app don&rsquo;t match, we won&rsquo;t be able to send your gift. This code can only be redeemed once &mdash; once you tap Redeem now, it can&rsquo;t be used again.</p>
          <div style="position:relative;background:#fff;border:3px solid #141414;border-radius:22px;box-shadow:7px 7px 0 #141414;padding:24px 22px 20px;margin:18px 4px 0;transform:rotate(-1.2deg);">
            <div style="position:absolute;top:-16px;right:-10px;width:76px;height:76px;border-radius:50%;background:#F16266;border:3px solid #141414;box-shadow:3px 3px 0 #141414;display:flex;flex-direction:column;align-items:center;justify-content:center;transform:rotate(12deg);">
              <span style="font-family:'Passion One',sans-serif;font-weight:900;font-size:15px;color:#fff;line-height:.85;text-transform:uppercase;">Valid to</span>
              <span style="font-family:'Passion One',sans-serif;font-weight:900;font-size:15px;color:#fff;line-height:.85;text-transform:uppercase;">31 Jul</span>
            </div>
            <button id="vtw-redeem-link" type="button" style="display:block;width:100%;box-sizing:border-box;border:3px solid #141414;background:#141414;color:#FFCE27;font-family:'Passion One',sans-serif;font-weight:900;font-size:22px;letter-spacing:.6px;text-transform:uppercase;cursor:pointer;text-align:center;padding:14px 18px;border-radius:14px;">Redeem now</button>
            <div style="border-top:2.5px dashed rgba(0,0,0,.2);margin:16px 0 12px;"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;">
              <span style="font-family:'Passion One',sans-serif;font-weight:900;font-size:16px;color:#141414;">BIM&rsquo;S</span>
              <span id="vtw-redeem-stamp" style="font-family:'Fredoka',sans-serif;font-weight:500;font-size:12px;color:rgba(20,20,20,.55);"></span>
            </div>
          </div>
        </div>

        <!-- SHOW CODE AT COUNTER (fallback for prizes with no redeem link yet) -->
        <div id="vtw-code-block" style="display:none;">
          <p style="font-family:'Fredoka',sans-serif;font-weight:500;font-size:15px;line-height:1.4;color:rgba(20,20,20,.7);margin:12px 0 0;">Show this code at the counter to claim it.</p>
          <div style="position:relative;background:#fff;border:3px solid #141414;border-radius:22px;box-shadow:7px 7px 0 #141414;padding:24px 22px 20px;margin:26px 4px 0;transform:rotate(-1.2deg);">
            <div style="position:absolute;top:-16px;right:-10px;width:76px;height:76px;border-radius:50%;background:#F16266;border:3px solid #141414;box-shadow:3px 3px 0 #141414;display:flex;flex-direction:column;align-items:center;justify-content:center;transform:rotate(12deg);">
              <span style="font-family:'Passion One',sans-serif;font-weight:900;font-size:15px;color:#fff;line-height:.85;text-transform:uppercase;">Valid to</span>
              <span style="font-family:'Passion One',sans-serif;font-weight:900;font-size:15px;color:#fff;line-height:.85;text-transform:uppercase;">31 Jul</span>
            </div>
            <span style="display:block;font-family:'Fredoka',sans-serif;font-weight:600;font-size:11px;letter-spacing:2px;color:rgba(0,0,0,.5);text-transform:uppercase;text-align:left;">Show this code</span>
            <div id="vtw-code" style="font-family:'Passion One',sans-serif;font-weight:900;font-size:48px;letter-spacing:1px;color:#141414;margin-top:4px;text-align:left;"></div>
            <div style="border-top:2.5px dashed rgba(0,0,0,.2);margin:16px 0 12px;"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;">
              <span style="font-family:'Passion One',sans-serif;font-weight:900;font-size:16px;color:#141414;">BIM&rsquo;S</span>
              <span id="vtw-stamp" style="font-family:'Fredoka',sans-serif;font-weight:500;font-size:12px;color:rgba(20,20,20,.55);"></span>
            </div>
          </div>
        </div>
      </div>

      <p style="font-family:'Fredoka',sans-serif;font-weight:400;font-size:11px;line-height:1.4;color:rgba(20,20,20,.5);margin:22px 2px 0;text-align:center;" id="vtw-terms-4"></p>
    </div>
  </section>

  <!-- ===================== REDEEM MODAL (loads the redeem link in-page, no external tab) ===================== -->
  <div id="vtw-redeem-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(20,20,20,.72);align-items:center;justify-content:center;padding:18px;">
    <div style="position:relative;width:100%;max-width:480px;height:min(88vh,760px);background:#FBF6E8;border:3px solid #141414;border-radius:22px;box-shadow:0 20px 50px rgba(0,0,0,.35);overflow:hidden;display:flex;flex-direction:column;">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#141414;flex-shrink:0;">
        <span style="font-family:'Passion One',sans-serif;font-weight:900;font-size:15px;letter-spacing:.5px;color:#FFCE27;text-transform:uppercase;">Redeem your prize</span>
        <button id="vtw-redeem-close" aria-label="Close" style="border:none;background:transparent;color:#fff;font-size:22px;line-height:1;cursor:pointer;padding:4px 6px;">&times;</button>
      </div>
      <iframe id="vtw-redeem-frame" src="about:blank" title="Redeem your prize" style="flex:1;width:100%;border:0;background:#fff;"></iframe>
    </div>
  </div>

</div>
