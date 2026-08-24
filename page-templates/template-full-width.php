<?php
/**
 * Template Name: Full Width
 * Template Post Type: page
 *
 * Site header and footer, no sidebar, content stretched to the full container
 * width. Wide and full alignments from the block editor are honoured.
 *
 * @package Moghadam
 * @since   1.1.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="primary" <?php moghadam_main_class( 'full-width' ); ?>>
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content/content', 'page' );

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</main><!-- #primary -->
<?php
get_footer();
