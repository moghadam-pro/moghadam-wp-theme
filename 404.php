<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="primary" <?php moghadam_main_class( 'default' ); ?>>
		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'moghadam' ); ?></h1>
			</header>

			<div class="page-content">
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'moghadam' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		</section>
	</main><!-- #primary -->
<?php
get_footer();
