<?php
/**
 * Section 04 — selected visual work.
 *
 * Every filter is rendered server-side with its own newest items, so switching
 * filters is instant and each one always shows a full set rather than a subset
 * of one shared query. The section is skipped when no portfolio plugin is
 * active.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! moghadam_portfolio_is_available() ) {
	return;
}

$filters = moghadam_portfolio_filters();
$groups  = array();

foreach ( $filters as $filter ) {
	$items = moghadam_portfolio_items( $filter['slug'] );

	if ( ! empty( $items ) ) {
		$groups[ $filter['slug'] ] = $items;
	}
}

if ( empty( $groups ) ) {
	return;
}

$filters = array_values(
	array_filter(
		$filters,
		function ( $filter ) use ( $groups ) {
			return isset( $groups[ $filter['slug'] ] );
		}
	)
);

$active = $filters[0]['slug'];
?>
<section class="section works" id="works">
	<?php moghadam_grid_lines(); ?>
	<div class="container">
		<div class="section-head">
			<?php
			moghadam_eyebrow( moghadam_home( 'works', 'eyebrow_number' ), moghadam_home( 'works', 'eyebrow_label' ) );
			moghadam_section_link( moghadam_home( 'works', 'link_label' ), moghadam_home( 'works', 'link_url' ) );
			?>
		</div>

		<?php if ( count( $filters ) > 1 ) : ?>
			<div class="works__filters" data-anim="fade" role="tablist"
				aria-label="<?php esc_attr_e( 'Filter visual work', 'moghadam' ); ?>">
				<?php foreach ( $filters as $i => $filter ) : ?>
					<?php if ( $i > 0 ) : ?>
						<span class="filter-rule" aria-hidden="true"></span>
					<?php endif; ?>
					<button class="filter<?php echo $filter['slug'] === $active ? ' is-active' : ''; ?>" type="button"
						role="tab" data-filter="<?php echo esc_attr( $filter['slug'] ); ?>"
						aria-selected="<?php echo $filter['slug'] === $active ? 'true' : 'false'; ?>">
						<?php echo esc_html( $filter['label'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div data-works>
			<?php foreach ( $groups as $slug => $items ) : ?>
				<div class="works__grid" data-group="<?php echo esc_attr( $slug ); ?>"
					<?php echo $slug === $active ? ' data-anim="cards"' : ' hidden'; ?>>
					<?php foreach ( $items as $i => $item ) : ?>
						<a class="work-card<?php echo 2 === $i ? ' work-card--tall' : ''; ?>"
							href="<?php echo esc_url( $item['permalink'] ); ?>">
							<?php if ( $item['thumbnail'] ) : ?>
								<?php echo $item['thumbnail']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span class="screen-reader-text"><?php echo esc_html( $item['title'] ); ?></span>
							<?php else : ?>
								<?php echo esc_html( $item['number'] ); ?>
							<?php endif; ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
