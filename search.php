<?php
/**
 * The template for displaying search results pages.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="primary" <?php moghadam_main_class( 'default' ); ?>>
		<?php if ( have_posts() ) : ?>
			<header class="page-header">
				<h1 class="page-title">
					<?php
					printf(
						/* translators: %s: search query. */
						esc_html__( 'Search Results for: %s', 'moghadam' ),
						'<span>' . esc_html( get_search_query() ) . '</span>'
					);
					?>
				</h1>
			</header>

			<div class="posts-list">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/content', 'row' );
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => esc_html__( 'Previous', 'moghadam' ),
					'next_text' => esc_html__( 'Next', 'moghadam' ),
				)
			);
			?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
		<?php endif; ?>
	</main><!-- #primary -->
<?php
get_sidebar();
get_footer();
