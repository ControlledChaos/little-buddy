<?php
/*
 * Template name: Full Width Content
 * Template Post Type: post, page
 *
 * @package    Little Buddy
 * @subpackage Templates
 * @category   Page Templates
 * @since      1.0.0
 */

get_header();

?>
<div id="primary" class="content-area bb-grid-cell">
	<main id="main" class="site-main">

		<?php
		if ( have_posts() ) :

			do_action( THEME_HOOK_PREFIX . '_template_parts_content_top' );

			while ( have_posts() ) :
				the_post();

				do_action( THEME_HOOK_PREFIX . '_single_template_part_content', 'page' );

			endwhile; // End of the loop.
		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;

		?>
	</main>
</div>
<?php

get_footer();
