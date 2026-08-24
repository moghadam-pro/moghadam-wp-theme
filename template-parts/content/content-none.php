<?php
/**
 * Template part for displaying a message when no posts are found.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing here', 'moghadam' ); ?></h1>
	</header>

	<div class="page-content">
		<?php if ( is_search() ) : ?>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'moghadam' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'moghadam' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
