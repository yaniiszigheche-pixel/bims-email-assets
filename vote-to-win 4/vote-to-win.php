<?php
/**
 * Plugin Name: BIM'S Vote to Win
 * Description: Spin-the-wheel promo. Hands out ONE unique code per prize from your CSV pools (never a double, even under simultaneous spins).
 * Version:     1.4.3
 * Author:      BIM'S
 * License:     GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // no direct access

define( 'VTW_VER', '1.4.3' );
define( 'VTW_DIR', plugin_dir_path( __FILE__ ) );
define( 'VTW_URL', plugin_dir_url( __FILE__ ) );

// Max spins allowed per browser (tracked in the vtw_spun cookie). Set 0 to disable.
if ( ! defined( 'VTW_MAX_PER_COOKIE' ) ) define( 'VTW_MAX_PER_COOKIE', 2 );

// Max spins allowed per IP address per day (network throttle). Set to 0 to disable
// the IP limit entirely. The per-browser cookie limit above is enforced too.
if ( ! defined( 'VTW_IP_DAILY_CAP' ) ) define( 'VTW_IP_DAILY_CAP', 6 );

// How long the "spun" cookie lasts (days).
if ( ! defined( 'VTW_SPUN_COOKIE_DAYS' ) ) define( 'VTW_SPUN_COOKIE_DAYS', 60 );

/**
 * Map each CSV file (in /codes) to the wheel prize key.
 * NOTE: the wheel's "BURGER" slice (shown to customers as "Nashville Burger")
 * still draws from the original bims_algerian_chicken_supreme.csv pool — that
 * file just holds plain redemption numbers, so relabelling the prize is safe
 * and doesn't touch already-redeemed codes tied to the 'burger' prize key.
 * 'mealdeal' draws from bims_medium_meal.csv (auto-generated unique codes).
 */
function vtw_pool_map() {
	return array(
		'burger'   => 'bims_algerian_chicken_supreme.csv',
		'fries'    => 'bims_fries.csv',
		'drink'    => 'bims_any_drink.csv',
		'applepie' => 'bims_apple_pie.csv',
		'swirl'    => 'bims_swirl_ice.csv',
		'churros'  => 'bims_churros.csv',
		'mealdeal' => 'bims_medium_meal.csv',
	);
}

/**
 * Redeem links (loyaltyapp.bims.co.uk deep links via pitcy.com), one per prize.
 * Winners no longer get a counter code for these — they redeem in the BIM'S
 * loyalty app instead. Any prize left out of this map (e.g. while waiting on
 * its link) automatically falls back to the old CSV code-pool flow below, so
 * nothing breaks for a prize until its link is added here.
 */
function vtw_redeem_links() {
	return array(
		'mealdeal' => 'https://pitcy.com/kH1WR9',
		'churros'  => 'https://pitcy.com/7ZnVIP',
		'drink'    => 'https://pitcy.com/1RwxoW',
		'fries'    => 'https://pitcy.com/wa24iu',
		'swirl'    => 'https://pitcy.com/6BpSll',
		'burger'   => 'https://pitcy.com/Qc4lRf',
		'applepie' => 'https://pitcy.com/jyYoP5',
	);
}

/** Weighted odds (total 100 = percentages). Nashville Burger + Medium Meal rarest. */
function vtw_odds() {
	return array(
		'burger'   => 7,   // Nashville Burger — premium item, 7%
		'fries'    => 18,
		'drink'    => 18,
		'applepie' => 18,
		'swirl'    => 18,
		'churros'  => 19,  // absorbs the 3% freed up by dropping mealdeal 5% -> 2%
		'mealdeal' => 2,   // Free medium meal of your choice — rarest, biggest prize
	);
}

function vtw_codes_table() { global $wpdb; return $wpdb->prefix . 'vtw_codes'; }
/**
 * The underlying DB table is intentionally left as its original name
 * (wp_vtw_ps5) — it already holds real customer entries collected while
 * the old prize draw was live, and renaming a live table risks orphaning
 * that data. This accessor is the only thing anything else refers to,
 * and every admin-facing label now says "legacy draw", not the old name.
 */
function vtw_legacy_draw_table() { global $wpdb; return $wpdb->prefix . 'vtw_ps5'; }

/* =========================================================================
 * ACTIVATION: create tables + import codes
 * ========================================================================= */
register_activation_hook( __FILE__, 'vtw_activate' );
function vtw_activate() {
	global $wpdb;
	$charset = $wpdb->get_charset_collate();
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$codes  = vtw_codes_table();
	$legacy = vtw_legacy_draw_table();

	dbDelta( "CREATE TABLE $codes (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		prize VARCHAR(32) NOT NULL,
		code VARCHAR(64) NOT NULL,
		used TINYINT(1) NOT NULL DEFAULT 0,
		used_at DATETIME NULL,
		claim_token VARCHAR(64) NULL,
		PRIMARY KEY (id),
		KEY prize_used (prize, used),
		UNIQUE KEY prize_code (prize, code)
	) $charset;" );

	dbDelta( "CREATE TABLE $legacy (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		first_name VARCHAR(120) NOT NULL,
		last_name VARCHAR(120) NOT NULL,
		phone VARCHAR(40) NOT NULL,
		email VARCHAR(190) NOT NULL,
		created_at DATETIME NOT NULL,
		PRIMARY KEY (id)
	) $charset;" );

	vtw_import_codes();
}

/**
 * Import codes from /codes CSVs. Only imports prizes that have no rows yet,
 * so re-activating is safe and never duplicates. Use the admin "Re-import"
 * button to force a fresh import.
 */
function vtw_import_codes( $force = false ) {
	global $wpdb;
	$table = vtw_codes_table();
	$report = array();

	foreach ( vtw_pool_map() as $prize => $file ) {
		$path = VTW_DIR . 'codes/' . $file;

		$existing = (int) $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM $table WHERE prize = %s", $prize
		) );

		if ( $existing > 0 && ! $force ) {
			$report[ $prize ] = "skipped (already $existing rows)";
			continue;
		}
		if ( $force ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM $table WHERE prize = %s", $prize ) );
		}
		if ( ! file_exists( $path ) ) {
			$report[ $prize ] = "MISSING FILE: $file";
			continue;
		}

		$lines = file( $path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
		$values = array();
		$now = current_time( 'mysql' );
		$count = 0;

		foreach ( $lines as $i => $line ) {
			$code = trim( $line );
			if ( $code === '' ) continue;
			// skip header row
			if ( $i === 0 && ! ctype_digit( $code ) ) continue;
			$values[] = $wpdb->prepare( '(%s,%s,0)', $prize, $code );
			$count++;

			if ( count( $values ) >= 500 ) {
				$wpdb->query( "INSERT IGNORE INTO $table (prize, code, used) VALUES " . implode( ',', $values ) );
				$values = array();
			}
		}
		if ( $values ) {
			$wpdb->query( "INSERT IGNORE INTO $table (prize, code, used) VALUES " . implode( ',', $values ) );
		}
		$report[ $prize ] = "imported $count";
	}
	return $report;
}

/* =========================================================================
 * CODE DISPENSING (atomic — no doubles, even with concurrent spins)
 * ========================================================================= */
function vtw_remaining( $prize ) {
	global $wpdb; $t = vtw_codes_table();
	return (int) $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(*) FROM $t WHERE prize = %s AND used = 0", $prize
	) );
}

function vtw_claim_code( $prize ) {
	global $wpdb; $t = vtw_codes_table();
	$token = wp_generate_uuid4();
	// Single atomic UPDATE reserves exactly one unused row for this claim.
	$updated = $wpdb->query( $wpdb->prepare(
		"UPDATE $t SET used = 1, used_at = %s, claim_token = %s
		 WHERE prize = %s AND used = 0 ORDER BY RAND() LIMIT 1",
		current_time( 'mysql' ), $token, $prize
	) );
	if ( $updated ) {
		return $wpdb->get_var( $wpdb->prepare(
			"SELECT code FROM $t WHERE claim_token = %s", $token
		) );
	}
	return null; // pool exhausted
}

/**
 * Best-effort real client IP, aware of Cloudflare / reverse proxies.
 * (X-Forwarded-For is spoofable, so this is a throttle, not hard security.)
 */
function vtw_client_ip() {
	foreach ( array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' ) as $k ) {
		if ( ! empty( $_SERVER[ $k ] ) ) {
			$ip = (string) $_SERVER[ $k ];
			if ( strpos( $ip, ',' ) !== false ) $ip = trim( explode( ',', $ip )[0] );
			$ip = preg_replace( '/[^0-9a-fA-F:\.]/', '', $ip );
			if ( $ip ) return $ip;
		}
	}
	return '0.0.0.0';
}

/* =========================================================================
 * AJAX: spin  ->  decide prize server-side + claim code
 * ========================================================================= */
add_action( 'wp_ajax_vtw_spin', 'vtw_ajax_spin' );
add_action( 'wp_ajax_nopriv_vtw_spin', 'vtw_ajax_spin' );
function vtw_ajax_spin() {
	check_ajax_referer( 'vtw', 'nonce' );

	// ---- spin enforcement (balanced: per-browser cookie count + per-IP daily throttle) ----
	$per_cookie = (int) apply_filters( 'vtw_max_per_cookie', (int) VTW_MAX_PER_COOKIE );
	$spun       = isset( $_COOKIE['vtw_spun'] ) ? max( 0, (int) $_COOKIE['vtw_spun'] ) : 0;
	if ( $per_cookie > 0 && $spun >= $per_cookie ) {
		wp_send_json_error( array(
			'reason'  => 'spun',
			'message' => 'Looks like you’ve used all your spins on this device.',
		) );
	}
	$ip      = vtw_client_ip();
	$cap     = (int) apply_filters( 'vtw_ip_daily_cap', (int) VTW_IP_DAILY_CAP );
	$ip_key  = 'vtw_ip_' . md5( $ip . '|' . gmdate( 'Y-m-d' ) );
	$ipcount = (int) get_transient( $ip_key );
	if ( $cap > 0 && $ipcount >= $cap ) {
		wp_send_json_error( array(
			'reason'  => 'ip',
			'message' => 'We’ve had a lot of spins from your network today — please try again tomorrow.',
		) );
	}

	// Grant the spin: bump this browser's count + the network counter BEFORE any output.
	setcookie( 'vtw_spun', (string) ( $spun + 1 ), time() + ( (int) VTW_SPUN_COOKIE_DAYS * DAY_IN_SECONDS ), '/', '', is_ssl(), true );
	$_COOKIE['vtw_spun'] = (string) ( $spun + 1 );
	set_transient( $ip_key, $ipcount + 1, DAY_IN_SECONDS );

	$odds  = vtw_odds();
	$links = vtw_redeem_links();

	// Drop any code-pool prize whose pool is empty so we never promise a code we
	// can't give. Prizes with a redeem link are unlimited, so they're never dropped.
	foreach ( array_keys( $odds ) as $prize ) {
		if ( ! isset( $links[ $prize ] ) && vtw_remaining( $prize ) <= 0 ) unset( $odds[ $prize ] );
	}
	if ( empty( $odds ) ) {
		wp_send_json_error( array(
			'reason'  => 'soldout',
			'message' => 'All our prizes have been claimed — check back soon!',
		) );
	}

	// Weighted random pick.
	$total = array_sum( $odds );
	$r = mt_rand( 1, $total );
	reset( $odds );
	$prize = key( $odds );
	foreach ( $odds as $k => $w ) {
		if ( $r <= $w ) { $prize = $k; break; }
		$r -= $w;
	}

	$stamp = date_i18n( 'd/m/Y, H:i', current_time( 'timestamp' ) );

	// Prize has a redeem link -> app-redeem flow, no counter code needed.
	if ( isset( $links[ $prize ] ) ) {
		wp_send_json_success( array(
			'prize'     => $prize,
			'redeemUrl' => $links[ $prize ],
			'stamp'     => $stamp,
		) );
	}

	// Otherwise fall back to the old CSV code-pool flow (e.g. Apple Pie for now).
	$code = vtw_claim_code( $prize );
	if ( $code === null ) {
		// Rare race: pool emptied between the check above and the claim — ask them to try again.
		wp_send_json_error( array(
			'reason'  => 'soldout',
			'message' => 'That prize just ran out — please try spinning again.',
		) );
	}
	wp_send_json_success( array(
		'prize' => $prize,
		'code'  => $code,
		'stamp' => $stamp,
	) );
}

/* =========================================================================
 * SHORTCODE: [vote_to_win]
 * ========================================================================= */
add_shortcode( 'vote_to_win', 'vtw_shortcode' );
function vtw_shortcode() {
	wp_enqueue_style( 'vtw', VTW_URL . 'assets/vtw.css', array(), VTW_VER );
	// Google fonts (Passion One / Fredoka / Caveat Brush)
	wp_enqueue_style( 'vtw-fonts', 'https://fonts.googleapis.com/css2?family=Passion+One:wght@400;700;900&family=Fredoka:wght@400;500;600;700&family=Caveat+Brush&display=swap', array(), null );
	wp_enqueue_script( 'vtw', VTW_URL . 'assets/vtw.js', array(), VTW_VER, true );
	wp_localize_script( 'vtw', 'VTW', array(
		'ajax'      => admin_url( 'admin-ajax.php' ),
		'nonce'     => wp_create_nonce( 'vtw' ),
		'assets'    => VTW_URL . 'assets/',
		'voteUrl'   => 'https://www.ubereatsawards.com/?r=bims',
		'unlockSecs'=> 20,
		'spinSecs'  => 5,
	) );

	ob_start();
	include VTW_DIR . 'template.php';
	return ob_get_clean();
}

/* =========================================================================
 * ADMIN: tabbed backend — overview, per-treat redeemed codes, legacy draw
 * entries, with CSV export on every tab.
 * ========================================================================= */
add_action( 'admin_menu', 'vtw_admin_menu' );
function vtw_admin_menu() {
	add_menu_page( 'Vote to Win', 'Vote to Win', 'manage_options', 'vtw', 'vtw_admin_page', 'dashicons-tickets-alt', 58 );
}

/** Human labels for each prize tab. */
function vtw_prize_labels() {
	return array(
		'burger'   => 'Nashville Burger',
		'fries'    => 'Fries',
		'drink'    => 'Any Drink',
		'applepie' => 'Apple Pie',
		'swirl'    => 'Swirl Ice',
		'churros'  => '3 Churros',
		'mealdeal' => 'Medium Meal (Any Choice)',
	);
}

/**
 * Self-healing import: whenever a new prize is added to vtw_pool_map() (like
 * 'mealdeal'), fill its codes automatically on the next admin page load.
 * Safe to run every time — vtw_import_codes(false) only touches prizes that
 * have zero rows, so already-redeemed data for existing prizes is untouched.
 */
add_action( 'admin_init', 'vtw_auto_import_new_prizes' );
function vtw_auto_import_new_prizes() {
	if ( ! is_admin() || wp_doing_ajax() ) return;
	vtw_import_codes( false );
}

/**
 * Handle CSV downloads EARLY (admin_init) — before any admin HTML is sent,
 * otherwise the file would be dumped into the middle of the page.
 */
add_action( 'admin_init', 'vtw_handle_downloads' );
function vtw_handle_downloads() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) return;
	if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'vtw' ) return;
	global $wpdb;

	// Legacy prize draw entries (archived — no longer collected)
	if ( isset( $_GET['vtw_export'] ) && check_admin_referer( 'vtw_export' ) ) {
		$rows = $wpdb->get_results( 'SELECT first_name,last_name,phone,email,created_at FROM ' . vtw_legacy_draw_table() . ' ORDER BY id DESC', ARRAY_A );
		vtw_send_csv( 'legacy-draw-entries.csv', array( 'First name', 'Last name', 'Mobile', 'Email', 'Entered' ), $rows );
	}

	// Per-prize codes (redeemed only, or all)
	if ( isset( $_GET['vtw_codes'] ) ) {
		$prize  = isset( $_GET['view'] ) ? sanitize_key( $_GET['view'] ) : '';
		$labels = vtw_prize_labels();
		if ( ! isset( $labels[ $prize ] ) ) return;
		check_admin_referer( 'vtw_codes_' . $prize );
		$t = vtw_codes_table();

		if ( $_GET['vtw_codes'] === 'all' ) {
			$rows = $wpdb->get_results( $wpdb->prepare(
				"SELECT code, CASE WHEN used = 1 THEN 'redeemed' ELSE 'available' END AS status, used_at
				 FROM $t WHERE prize = %s ORDER BY used DESC, id ASC", $prize ), ARRAY_A );
			vtw_send_csv( $prize . '-all-codes.csv', array( 'Code', 'Status', 'Redeemed at' ), $rows );
		} else {
			$rows = $wpdb->get_results( $wpdb->prepare(
				"SELECT code, used_at FROM $t WHERE prize = %s AND used = 1 ORDER BY used_at DESC", $prize ), ARRAY_A );
			vtw_send_csv( $prize . '-redeemed-codes.csv', array( 'Code', 'Redeemed at' ), $rows );
		}
	}
}

function vtw_send_csv( $filename, $headers, $rows ) {
	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=' . $filename );
	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, $headers );
	foreach ( $rows as $r ) fputcsv( $out, $r );
	fclose( $out );
	exit;
}

function vtw_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) return;
	$labels = vtw_prize_labels();

	$view = isset( $_GET['view'] ) ? sanitize_key( $_GET['view'] ) : 'overview';
	if ( $view !== 'overview' && $view !== 'legacy' && ! isset( $labels[ $view ] ) ) $view = 'overview';

	// Re-import (overview only)
	if ( isset( $_POST['vtw_reimport'] ) && check_admin_referer( 'vtw_admin' ) ) {
		$report = vtw_import_codes( true );
		echo '<div class="notice notice-success"><p>Re-imported: ' . esc_html( wp_json_encode( $report ) ) . '</p></div>';
	}

	$base = admin_url( 'admin.php?page=vtw' );
	echo '<div class="wrap"><h1>Vote to Win</h1>';

	// Tabs
	echo '<h2 class="nav-tab-wrapper" style="margin-bottom:18px">';
	printf( '<a class="nav-tab %s" href="%s">Overview</a>', $view === 'overview' ? 'nav-tab-active' : '', esc_url( $base ) );
	foreach ( $labels as $k => $lab ) {
		printf( '<a class="nav-tab %s" href="%s">%s</a>',
			$view === $k ? 'nav-tab-active' : '', esc_url( $base . '&view=' . $k ), esc_html( $lab ) );
	}
	printf( '<a class="nav-tab %s" href="%s">Legacy draw entries (archived)</a>', $view === 'legacy' ? 'nav-tab-active' : '', esc_url( $base . '&view=legacy' ) );
	echo '</h2>';

	if ( $view === 'overview' )      vtw_admin_overview();
	elseif ( $view === 'legacy' )    vtw_admin_legacy_draw();
	else                             vtw_admin_prize( $view, $labels[ $view ] );

	echo '</div>';
}

function vtw_admin_overview() {
	global $wpdb;
	$ct     = vtw_codes_table();
	$labels = vtw_prize_labels();
	$stats  = $wpdb->get_results( "SELECT prize, COUNT(*) total, SUM(used) used FROM $ct GROUP BY prize", ARRAY_A );
	$bykey  = array();
	foreach ( $stats as $s ) $bykey[ $s['prize'] ] = $s;
	$legacy_count = (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . vtw_legacy_draw_table() );

	echo '<h2>Code pools</h2>';
	echo '<table class="widefat striped" style="max-width:760px"><thead><tr><th>Prize</th><th>Total</th><th>Redeemed</th><th>Remaining</th><th></th></tr></thead><tbody>';
	foreach ( $labels as $k => $lab ) {
		$total = isset( $bykey[ $k ] ) ? (int) $bykey[ $k ]['total'] : 0;
		$used  = isset( $bykey[ $k ] ) ? (int) $bykey[ $k ]['used'] : 0;
		printf( '<tr><td>%s</td><td>%d</td><td>%d</td><td><strong>%d</strong></td><td><a class="button button-small" href="%s">View codes</a></td></tr>',
			esc_html( $lab ), $total, $used, $total - $used,
			esc_url( admin_url( 'admin.php?page=vtw&view=' . $k ) ) );
	}
	printf( '<tr><td><strong>Legacy prize draw (archived — no longer running)</strong></td><td colspan="3">%d entries</td><td><a class="button button-small" href="%s">View entries</a></td></tr>',
		$legacy_count, esc_url( admin_url( 'admin.php?page=vtw&view=legacy' ) ) );
	echo '</tbody></table>';

	echo '<form method="post" style="margin-top:16px">';
	wp_nonce_field( 'vtw_admin' );
	echo '<button class="button" name="vtw_reimport" value="1" onclick="return confirm(\'Wipe redeemed status and re-import all codes from the CSVs? Only do this before the promo goes live.\')">Re-import codes from CSVs</button>';
	echo '</form>';
}

function vtw_admin_prize( $prize, $label ) {
	global $wpdb;
	$t     = vtw_codes_table();
	$total = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $t WHERE prize = %s", $prize ) );
	$used  = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $t WHERE prize = %s AND used = 1", $prize ) );
	$rows  = $wpdb->get_results( $wpdb->prepare(
		"SELECT code, used_at FROM $t WHERE prize = %s AND used = 1 ORDER BY used_at DESC LIMIT 500", $prize ), ARRAY_A );

	$exp_used = wp_nonce_url( admin_url( 'admin.php?page=vtw&view=' . $prize . '&vtw_codes=used' ), 'vtw_codes_' . $prize );
	$exp_all  = wp_nonce_url( admin_url( 'admin.php?page=vtw&view=' . $prize . '&vtw_codes=all' ),  'vtw_codes_' . $prize );

	printf( '<h2>%s</h2>', esc_html( $label ) );
	printf( '<p style="font-size:14px"><strong>%d</strong> redeemed &nbsp;&middot;&nbsp; <strong>%d</strong> remaining &nbsp;&middot;&nbsp; %d total</p>', $used, $total - $used, $total );
	printf( '<p><a class="button button-primary" href="%s">Export redeemed codes (CSV)</a> &nbsp; <a class="button" href="%s">Export all codes (CSV)</a></p>',
		esc_url( $exp_used ), esc_url( $exp_all ) );

	echo '<h3 style="margin-top:22px">Redeemed codes</h3>';
	echo '<table class="widefat striped" style="max-width:520px"><thead><tr><th>Code</th><th>Redeemed at</th></tr></thead><tbody>';
	if ( $rows ) {
		foreach ( $rows as $r ) {
			printf( '<tr><td style="font-family:monospace;font-size:15px;font-weight:600">%s</td><td>%s</td></tr>',
				esc_html( $r['code'] ), esc_html( $r['used_at'] ) );
		}
	} else {
		echo '<tr><td colspan="2">No codes redeemed yet.</td></tr>';
	}
	echo '</tbody></table>';
	if ( $used > 500 ) echo '<p style="color:#666">Showing latest 500 of ' . (int) $used . '. Use Export for the full list.</p>';
}

function vtw_admin_legacy_draw() {
	global $wpdb;
	$count   = (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . vtw_legacy_draw_table() );
	$entries = $wpdb->get_results( 'SELECT * FROM ' . vtw_legacy_draw_table() . ' ORDER BY id DESC LIMIT 500', ARRAY_A );
	$exp     = wp_nonce_url( admin_url( 'admin.php?page=vtw&vtw_export=1' ), 'vtw_export' );

	printf( '<h2>Legacy prize draw entries — archived (%d)</h2>', $count );
	echo '<p style="color:#666">This prize draw is no longer part of the wheel. These are the entries collected while it was active.</p>';
	printf( '<p><a class="button button-primary" href="%s">Export all entries (CSV)</a></p>', esc_url( $exp ) );
	echo '<table class="widefat striped"><thead><tr><th>First</th><th>Last</th><th>Mobile</th><th>Email</th><th>Entered</th></tr></thead><tbody>';
	if ( $entries ) {
		foreach ( $entries as $e ) {
			printf( '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
				esc_html( $e['first_name'] ), esc_html( $e['last_name'] ), esc_html( $e['phone'] ),
				esc_html( $e['email'] ), esc_html( $e['created_at'] ) );
		}
	} else {
		echo '<tr><td colspan="5">No entries yet.</td></tr>';
	}
	echo '</tbody></table>';
	if ( $count > 500 ) echo '<p style="color:#666">Showing latest 500 of ' . (int) $count . '. Use Export for the full list.</p>';
}
