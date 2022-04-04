<?php
/**
 * Content editors
 *
 * @package    Little Buddy
 * @subpackage Admin
 * @category   Editors
 * @since      1.0.0
 */

namespace Little_Buddy\Content_Editors;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Constructor method
 *
 * @since  1.0.0
 * @return self
 */
function setup() {

	// Return namespaced function.
	$ns = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Add fresh editor styles.
	add_filter( 'mce_css', $ns( 'fresh_editor_style' ) );

	// Editor buttons & formats.
	add_filter( 'mce_buttons', $ns( 'editor_buttons' ) );
	add_filter( 'tiny_mce_before_init', $ns( 'editor_formats' ) );
}

/**
 * Fresh editor styles
 *
 * Add sa parameter of the last modified time to all editor stylesheets.
 *
 * Modified copy of `_WP_Editors::editor_settings()`.
 *
 * @since  1.0.0
 * @param  string $css Comma separated stylesheet URIs
 * @return string
 */
function fresh_editor_style( $css ) {

	global $editor_styles;

	if ( empty ( $css ) or empty ( $editor_styles ) ) {
		return $css;
	}

	$mce_css = [];

	// Load parent theme styles first, so the child theme can overwrite it.
	if ( is_child_theme() )	{
		refill_editor_styles(
			$mce_css,
			get_template_directory(),
			get_template_directory_uri()
		);
	}

	refill_editor_styles(
		$mce_css,
		get_stylesheet_directory(),
		get_stylesheet_directory_uri()
	);

	return implode( ',', $mce_css );
}

/**
 * Refill editor styles
 *
 * Adds version parameter to each stylesheet URI.
 *
 * @since  1.0.0
 * @param  array  $mce_css Passed by reference.
 * @param  string $dir
 * @param  string $uri
 * @return void
 */
function refill_editor_styles( &$mce_css, $dir, $uri ) {

	global $editor_styles;

	foreach ( $editor_styles as $file )	{

		if ( ! $file or ! file_exists( "$dir/$file" ) )	{
			continue;
		}

		$mce_css[] = add_query_arg(
			'version',
			filemtime( "$dir/$file" ),
			"$uri/$file"
		);
	}
}

/**
 * Add editor buttons
 *
 * @since  1.0.0
 * @param  array $buttons
 * @return array Returns an array of buttons.
 */
function editor_buttons( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}

/**
 * Editor formats
 *
 * @since  1.0.0
 * @param  array $init_array
 * @return array Returns an array of formats.
 */
function editor_formats( $init_array ) {

	// Define the formats array.
	$formats = [
		[
			'title'   => __( 'Font Smaller', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'font-size font-size-smaller',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Font Small', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'font-size font-size-small',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Font Regular', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'font-size font-size-regular',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Font Large', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'font-size font-size-large',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Font Larger', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'font-size font-size-larger',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Display Font Thin', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'family-display display-100',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Display Font Extra-Light', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'family-display display-200',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Display Font Light', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'family-display display-300',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Display Font Regular', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'family-display display-400',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Display Font Medium', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'family-display display-500',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Display Font Semi-Bold', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'family-display display-600',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Display Font Bold', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'family-display display-700',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Display Font Extra-Bold', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'family-display display-800',
			'wrapper' => true,
		],
		[
			'title'   => __( 'Display Font Black', 'bbtc' ),
			'inline'  => 'span',
			'classes' => 'family-display display-900',
			'wrapper' => true,
		]
	];

	// Insert the array, JSON ENCODED, into 'style_formats'.
	$init_array['style_formats'] = json_encode( $formats );

	return $init_array;
}
