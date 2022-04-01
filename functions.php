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
			array(
				'family'  => rawurlencode( implode( '|', $fonts ) ),
				'subset'  => rawurlencode( 'latin,latin-ext' ),
				'display' => 'swap',
			),
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
 * Remove admin pages
 *
 * @since  1.0.0
 * @return void
 */
function remove_admin_pages() {

	remove_action( 'admin_menu', [ 'BuddyBoss_Updater_Admin', 'admin_menu' ], 10 );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\remove_admin_pages', 999 );
