<?php
/**
 * Little Buddy functions
 *
 * Core file for theme functionality.
 *
 * @package    Little Buddy
 * @subpackage Functions
 * @since      1.0.0
 */

namespace Little_Buddy;

/**
 * Constant: Theme file path
 *
 * @since 1.0.0
 * @var   string File path with trailing slash.
 */
$theme_path = get_stylesheet_directory();
define( 'LB_PATH', $theme_path . '/' );

/**
 * Constant: Theme file URL
 *
 * @since 1.0.0
 * @var   string
 */
$theme_url = get_stylesheet_directory_uri();
define( 'LB_URL', $theme_url );

/**
 * Get pluggable path
 *
 * Used to check for the `is_user_logged_in` function.
 */
if ( ( function_exists( 'is_multisite' ) && ! is_multisite() ) && file_exists( ABSPATH . 'wp-includes/pluggable.php' ) ) {
	include_once( ABSPATH . 'wp-includes/pluggable.php' );
}

/**
 * Get plugins path
 *
 * Used to check for active plugins with the `is_plugin_active` function.
 */
if ( ! function_exists( 'is_plugin_active' ) ) {
	if ( file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
}

// Stop here if the plugin functions file can not be accessed.
if ( ! function_exists( 'is_plugin_active' ) ) {
	return;
}

/**
 * If BuddyBoss screen
 *
 * @since  1.0.0
 * @return boolean Returns true if on a BuddyBoss screen.
 */
function is_buddyboss() {

	$is_buddyboss = false;
	if ( is_buddypress() || is_bbpress() ) {
		$is_buddyboss = true;
	}
	return $is_buddyboss;
}

/**
 * ACF is active
 *
 * Checks for the Advanced Custom Fields plugin
 *
 * @since  1.0.0
 * @return boolean Returns true if the plugin is installed & active.
 */
function active_acf() {

	if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
		return true;
	}
	return false;
}

/**
 * ACF PRO is active
 *
 * Checks for the Advanced Custom Fields PRO plugin
 *
 * @since  1.0.0
 * @return boolean Returns true if the plugin is installed & active.
 */
function active_acf_pro() {

	if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
		return true;
	}
	return false;
}

/**
 * ACFE is active
 *
 * Checks for the Advanced Custom Fields: Extended plugin
 *
 * @since  1.0.0
 * @return boolean Returns true if the plugin is installed & active.
 */
function active_acfe() {

	if ( is_plugin_active( 'acf-extended/acf-extended.php' ) ) {
		return true;
	}
	return false;
}

/**
 * ACFE PRO is active
 *
 * Checks for the Advanced Custom Fields: Extended PRO plugin
 *
 * @since  1.0.0
 * @return boolean Returns true if the plugin is installed & active.
 */
function active_acfe_pro() {

	if ( is_plugin_active( 'acf-extended-pro/acf-extended.php' ) ) {
		return true;
	}
	return false;
}

/**
 * ACF template suffix
 *
 * Returns a suffix used to get template parts
 * containing ACF functions if ACF is active.
 *
 * @since  1.0.0
 * @return boolean Returns the suffix if the plugin
 *                 is installed & active.
 */
function acf_suffix() {

	$suffix = '';
	if ( active_acf() || active_acf_pro() ) {
		$suffix = '-acf';
	}
	return $suffix;
}


// Load required files.
foreach ( glob( LB_PATH . 'includes/admin/*.php' ) as $filename ) {
	require $filename;
}

/**
 * Remove activation modal
 *
 * The parent theme displays a video in a
 * modal window when it or a child is
 * activated. This disable the video.
 *
 * @since  1.0.0
 * @return void
 */
function remove_about_theme_screen() {
	remove_action( 'admin_footer', 'about_theme_screen' );
}
add_action( 'admin_init', __NAMESPACE__ . '\\remove_about_theme_screen' );

/**
 * Body classes
 *
 * Adds custom classes to the array of body classes.
 *
 * @since  1.0.0
 * @param  array $classes Classes for the body element.
 * @return array Returns the array of body classes.
 */
function body_classes( $classes ) {

	// General theme class.
	$classes[] = 'buddyboss-child';

	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\\body_classes', 20 );

/**
 * Google fonts
 *
 * @todo Create a UI for selecting fonts.
 *
 * @since  1.0.0
 * @return string Returns the URL of the fonts stylesheet.
 */
function google_fonts() {

	$get_fonts = [
		'Roboto Slab' => [
			'100',
			'200',
			'300',
			'400',
			'500',
			'600',
			'700',
			'800',
			'900'
		],
		'Libre Franklin' => [
			'100',
			'200',
			'300',
			'400',
			'500',
			'600',
			'700',
			'800',
			'900'
		]
	];

	$fonts = [];
	foreach ( $get_fonts as $font => $font_weights ) {
		$fonts[] = sprintf( '%1$s:%2$s', $font, implode( ',', $font_weights ) );
	}

	return esc_url_raw(
		add_query_arg(
			[
				'family'  => rawurlencode( implode( '|', $fonts ) ),
				'subset'  => rawurlencode( 'latin,latin-ext' ),
				'display' => 'swap',
			],
			'https://fonts.googleapis.com/css'
		)
	);
}

/**
 * Frontend styles
 *
 * @since  1.0.0
 * @return void
 */
function frontend_styles() {

	// If in one of the debug modes do not minify.
	if (
		( defined( 'WP_DEBUG' ) && WP_DEBUG ) ||
		( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG )
	) {
		$suffix = '';
	} else {
		$suffix = '.min';
	}

	// Get Google fonts.
	wp_enqueue_style( 'google-fonts', google_fonts(), [], null );

	wp_enqueue_style( 'buddyboss-child', get_theme_file_uri( "/assets/css/style$suffix.css" ), [ 'buddyboss-theme-css' ], null, 'all' );
	if ( is_rtl() ) {
		wp_enqueue_style( 'buddyboss-child-rtl', get_theme_file_uri( "/assets/css/style-rtl$suffix.css" ), [ 'buddyboss-child' ], null, 'all' );
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\frontend_styles' );

/**
 * Admin styles
 *
 * Google fonts needed for custom formats in the editor.
 *
 * @since  1.0.0
 * @return void
 */
function admin_styles() {

	// Get Google fonts.
	wp_enqueue_style( 'google-fonts', google_fonts(), [], null );
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\admin_styles' );

/**
 * Rich text editor styles
 *
 * @since  1.0.0
 * @return void
 */
function editor_styles() {

	// If in one of the debug modes do not minify.
	if (
		( defined( 'WP_DEBUG' ) && WP_DEBUG ) ||
		( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG )
	) {
		$suffix = '';
	} else {
		$suffix = '.min';
	}

	add_editor_style( "assets/css/admin/editor$suffix.css", [], '', 'screen' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\editor_styles' );

/**
 * Remove admin pages
 *
 * @since  1.0.0
 * @return void
 */
function remove_admin_pages() {
	remove_action( 'admin_menu', [ 'BuddyBoss_Updater_Admin', 'admin_menu' ], 10 );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\remove_admin_pages', 999 );

if ( is_admin() ) {
	Content_Editors\setup();
}

/**
 * Front page redirect
 *
 * Redirects to the user activity feed
 * if the user is logged in.
 *
 * @since  1.0.0
 * @return void
 */
function front_page_redirect() {

	if (
		is_front_page() &&
		is_user_logged_in() &&
		function_exists( 'bp_loggedin_user_domain' )
	) {
		wp_redirect( bp_loggedin_user_domain() . 'activity/' );
		die();
	}
}
add_action( 'template_redirect', __NAMESPACE__ . '\\front_page_redirect' );
