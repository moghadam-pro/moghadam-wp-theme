<?php
/**
 * The previous and next post, as two cards with their featured images.
 *
 * @package Moghadam
 * @since   1.5.0
 */

defined( 'ABSPATH' ) || exit;

$pairs = array(
	'prev' => array(
		'post'  => get_previous_post(),
		'label' => __( 'Previous post', 'moghadam' ),
	),
	'next' => array(
		'post'  => get_next_post(),
		'label' => __( 'Next post', 'moghadam' ),
	),
);

if ( empty( $pairs['prev']['post'] ) && empty( $pairs['next']['post'] ) ) {
	return;
}
?>
<nav class="post-nav" aria-label="<?php esc_attr_e( 'Continue reading', 'moghadam' ); ?>">
	<?php foreach ( $pairs as $key => $pair ) : ?>
		<?php if ( empty( $pair['post'] ) ) { continue; } ?>
		<a class="post-nav__link post-nav__link--<?php echo esc_attr( $key ); ?>"
			href="<?php echo esc_url( get_permalink( $pair['post'] ) ); ?>" rel="<?php echo esc_attr( $key ); ?>">
			<?php if ( has_post_thumbnail( $pair['post'] ) ) : ?>
				<span class="post-nav__media">
					<?php echo get_the_post_thumbnail( $pair['post'], 'medium', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
				</span>
			<?php endif; ?>
			<span class="post-nav__body">
				<span class="post-nav__label">
					<?php if ( 'prev' === $key ) : ?>
						<span class="post-nav__arrow" aria-hidden="true">&larr;</span>
					<?php endif; ?>
					<?php echo esc_html( $pair['label'] ); ?>
					<?php if ( 'next' === $key ) : ?>
						<span class="post-nav__arrow" aria-hidden="true">&rarr;</span>
					<?php endif; ?>
				</span>
				<span class="post-nav__title"><?php echo esc_html( get_the_title( $pair['post'] ) ); ?></span>
			</span>
		</a>
	<?php endforeach; ?>
</nav>
