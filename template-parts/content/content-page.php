<?php
/**
 * Template part for displaying a page.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
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

	<?php
	edit_post_link(
		esc_html__( 'Edit', 'moghadam' ),
		'<footer class="entry-footer"><span class="edit-link">',
		'</span></footer>'
	);
	?>
</article><!-- #post-<?php the_ID(); ?> -->
