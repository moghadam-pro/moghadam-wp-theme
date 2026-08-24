<?php
/**
 * The template for displaying all pages.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="primary" class="site-main container">
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
get_sidebar();
get_footer();
