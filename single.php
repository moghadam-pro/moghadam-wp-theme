<?php
/**
 * The template for displaying all single posts.
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

			get_template_part( 'template-parts/content/content', 'single' );

			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'moghadam' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'moghadam' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</main><!-- #primary -->
<?php
get_sidebar();
get_footer();
