<?php
/**
 * Template part for displaying a single post.
 *
 * The date and byline stay in the markup for search engines and for the
 * microformat classes, but are hidden: the sidebar already carries all of it
 * and printing it twice only competes with the title.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta entry-meta--hidden">
				<?php
				moghadam_posted_on();
				moghadam_posted_by();
				?>
			</div>
		<?php endif; ?>
	</header>

	<?php moghadam_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'moghadam' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
