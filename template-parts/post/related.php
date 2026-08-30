<?php
/**
 * Posts related to the one being read.
 *
 * @package Moghadam
 * @since   1.5.0
 */

defined( 'ABSPATH' ) || exit;

$related = moghadam_related_posts( get_the_ID(), 4 );

if ( empty( $related ) ) {
	return;
}
?>
<section class="related" aria-labelledby="related-title">
	<h2 class="related__title" id="related-title"><?php esc_html_e( 'Related reading', 'moghadam' ); ?></h2>

	<ul class="related__grid">
		<?php foreach ( $related as $post ) : ?>
			<li class="related__item">
				<a class="related__link" href="<?php echo esc_url( get_permalink( $post ) ); ?>">
					<span class="related__media">
						<?php if ( has_post_thumbnail( $post ) ) : ?>
							<?php echo get_the_post_thumbnail( $post, 'medium', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
						<?php else : ?>
							<span class="related__placeholder" aria-hidden="true"></span>
						<?php endif; ?>
					</span>
					<span class="related__body">
						<span class="related__date"><?php echo esc_html( get_the_date( '', $post ) ); ?></span>
						<span class="related__name"><?php echo esc_html( get_the_title( $post ) ); ?></span>
					</span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
