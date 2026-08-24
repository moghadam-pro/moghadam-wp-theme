<?php
/**
 * Template part for displaying results in search pages.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php moghadam_post_thumbnail(); ?>

	<header class="entry-header">
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php moghadam_posted_on(); ?>
			</div>
		<?php endif; ?>
	</header>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>

	<footer class="entry-footer">
		<?php moghadam_entry_footer(); ?>
	</footer>
</article><!-- #post-<?php the_ID(); ?> -->
