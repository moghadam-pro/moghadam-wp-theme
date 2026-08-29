<?php
/**
 * The two counter-rotating bars under the hero.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

$rows = array(
	array(
		'items'     => moghadam_home_lines( 'marquee', 'row_one' ),
		'separator' => moghadam_home( 'marquee', 'row_one_sep' ),
		'modifier'  => 'dark',
		'anim'      => 'slide-l',
	),
	array(
		'items'     => moghadam_home_lines( 'marquee', 'row_two' ),
		'separator' => moghadam_home( 'marquee', 'row_two_sep' ),
		'modifier'  => 'yellow',
		'anim'      => 'slide-r',
	),
);
?>
<section class="marquee" aria-label="<?php esc_attr_e( 'Highlights', 'moghadam' ); ?>">
	<?php foreach ( $rows as $row ) : ?>
		<?php if ( empty( $row['items'] ) ) { continue; } ?>
		<div class="marquee__row marquee__row--<?php echo esc_attr( $row['modifier'] ); ?>" data-anim="<?php echo esc_attr( $row['anim'] ); ?>">
			<div class="marquee__track" data-marquee>
				<div class="marquee__item">
					<?php foreach ( $row['items'] as $item ) : ?>
						<span class="tag"><?php echo esc_html( $item ); ?></span>
						<span class="marquee__slash"><?php echo esc_html( $row['separator'] ); ?></span>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</section>
