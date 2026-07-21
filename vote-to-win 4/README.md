# BIM'S Vote to Win — WordPress plugin

Spin-the-wheel promo for your existing WordPress site. Hands out **one unique code per prize** from your CSV pools (a code can never be given twice — the pick is done atomically in the database, so even two people spinning at the same instant can't collide). There is no draw/data-capture mechanic any more — every slice, including the top prize, is a redeemable code.

---

## What's inside
```
vote-to-win/
├── vote-to-win.php     ← main plugin (DB, code dispensing, admin)
├── template.php        ← the 4 screens (vote → wait → wheel → result)
├── assets/
│   ├── vtw.css
│   ├── vtw.js
│   └── (9 design images, already included)
└── codes/              ← your 7 CSVs (imported automatically on activation)
```

## Install (2 minutes)

Everything is bundled — all design images are already in `assets/`, and all 7 CSVs are in `codes/`. Nothing to add.

1. In WordPress: **Plugins → Add New → Upload Plugin →** choose `vote-to-win.zip` → **Install → Activate.**
   On activation it creates the database tables and imports all your codes. If you're **updating** an existing install, use **"Replace current with uploaded"** instead of deleting first — your redeemed codes are never touched either way (they live in the database, not the plugin files), and any brand-new prize (like the medium meal) gets its codes imported automatically the next time you open the admin page.

2. **Add the wheel to any page/post** with the shortcode:
   ```
   [vote_to_win]
   ```
   (Gutenberg: add a *Shortcode* block. Elementor/Divi: use a *Shortcode* widget.)
   Best on a blank/full-width page template since each screen is full-height.

3. **Manage it** under the new **Vote to Win** menu in wp-admin. It's tabbed:
   - **Overview** — codes total / redeemed / remaining per prize, plus the re-import button (only use before go-live — it resets redeemed status).
   - **One tab per prize** (Nashville Burger, Fries, Any Drink, Apple Pie, Swirl Ice, 3 Churros, Medium Meal) — lists the actual **redeemed codes with timestamps**, and buttons to **export redeemed codes** or **all codes** as CSV.
   - **Legacy draw entries (archived)** — an earlier version of this promo had a separate prize-draw mechanic; that's no longer part of the wheel, and this tab is read-only, just preserving the entries collected while it was live, with CSV export.

## Prize → CSV mapping
| Wheel slice | Codes from | Odds |
|---|---|---|
| NASHVILLE BURGER | `bims_algerian_chicken_supreme.csv` | 7% |
| FRIES | `bims_fries.csv` | 18% |
| ANY DRINK | `bims_any_drink.csv` | 18% |
| APPLE PIE | `bims_apple_pie.csv` | 18% |
| SWIRL ICE | `bims_swirl_ice.csv` | 18% |
| 3 CHURROS | `bims_churros.csv` | 16% |
| FREE MEDIUM MEAL | `bims_medium_meal.csv` | 5% |

Two notes on the mapping:
- The **Nashville Burger** slice still draws from the original `bims_algerian_chicken_supreme.csv` pool — that file only ever held plain redemption numbers (no product name printed on them), so relabelling the prize was safe and didn't disturb any already-redeemed codes.
- The **Free Medium Meal** codes in `bims_medium_meal.csv` are 2,000 auto-generated unique 5-digit numbers (same style as the others). If you'd rather these be synced to a specific list (e.g. from your Como/POS system), swap the file's contents for your own — same format, one code per line under a `Code` header.
- Two new images ship in `assets/`: `burger-sq.png` (Nashville Burger) and `medium-meal-sq.png` (the combo shot used for the top prize on screen 1 and the win screen).

To change any mapping or odds, edit `vtw_pool_map()` / `vtw_odds()` in `vote-to-win.php`.

## Notes / settings
- Vote link, unlock delay (20s) and spin length (5s) are set in `vtw_shortcode()` (the `wp_localize_script` array) in `vote-to-win.php`.
- **Caching:** if you run a full-page cache (WP Rocket, LiteSpeed, etc.), **exclude the promo page** from caching. The spin/entry uses a security nonce that can expire if the whole page is served stale.
- **Spin limits (balanced):** a persistent server-set cookie (`vtw_spun`) counts spins and caps each browser at **2** — enforced on the server, so clearing localStorage or using incognito won't reset it within the same browser. On top of that, spins are throttled **per IP per day** (default **6**) to stop cookie-clearing farming, without hard-blocking shared mobile/WiFi networks.
  - Tune via `wp-config.php`:
    `define('VTW_MAX_PER_COOKIE', 2);` — spins per browser (0 = unlimited)
    `define('VTW_IP_DAILY_CAP', 6);` — spins per IP per day (0 = disable IP limit)
    `define('VTW_SPUN_COOKIE_DAYS', 60);` — cookie lifetime
  - Note: a determined user can still switch to a *different device/browser*. Truly blocking the same person needs login; the one-time counter codes remain the ultimate backstop.
- Codes are used **only when actually won**, so unused ones stay available.
