<?php
/**
 * The template for displaying all single posts.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;

get_header();

$has_sidebar = moghadam_has_sidebar();
?>
	<div class="content-area<?php echo $has_sidebar ? ' content-area--sidebar' : ''; ?>">
		<main id="primary" <?php moghadam_main_class( 'default' ); ?>>
			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content/content', 'single' );
				get_template_part( 'template-parts/post/pagination' );
				get_template_part( 'template-parts/post/related' );

				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			endwhile;
			?>
		</main><!-- #primary -->

		<?php get_sidebar(); ?>
	</div>
<?php
get_footer();
