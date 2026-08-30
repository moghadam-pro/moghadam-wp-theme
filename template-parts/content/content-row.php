<?php
/**
 * One post as a row in an archive listing: small thumbnail, meta, title,
 * excerpt.
 *
 * @package Moghadam
 * @since   1.5.0
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-row' ); ?>>
	<a class="post-row__link" href="<?php the_permalink(); ?>">
		<span class="post-row__media">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
			<?php else : ?>
				<span class="post-row__placeholder" aria-hidden="true"></span>
			<?php endif; ?>
		</span>

		<span class="post-row__body">
			<span class="post-row__meta">
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				<?php
				$moghadam_cats = get_the_category();
				if ( ! empty( $moghadam_cats ) ) :
					?>
					<span class="post-row__sep" aria-hidden="true">&middot;</span>
					<span><?php echo esc_html( $moghadam_cats[0]->name ); ?></span>
				<?php endif; ?>
			</span>

			<h2 class="post-row__title"><?php the_title(); ?></h2>

			<span class="post-row__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></span>
		</span>
	</a>
</article>
