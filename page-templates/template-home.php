<?php
/**
 * Template Name: Home Page
 * Template Post Type: page
 *
 * The landing page layout. Content comes from the block editor exactly like any
 * other page; the template only supplies the structure and two action hooks so
 * home-specific sections can be added later without touching the content.
 *
 * @package Moghadam
 * @since   1.1.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="primary" <?php moghadam_main_class( 'home' ); ?>>
		<?php
		/**
		 * Fires before the home page content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'moghadam_home_before_content' );

		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;

		/**
		 * Fires after the home page content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'moghadam_home_after_content' );
		?>
	</main><!-- #primary -->
<?php
get_footer();
