<?php
/**
 * Portfolio bridge for section 04.
 *
 * The section belongs to whatever portfolio plugin is installed. This file
 * finds one, reads its post type and taxonomy, and hands the template a plain
 * array. When no plugin answers, moghadam_portfolio_is_available() returns
 * false and the section is skipped entirely.
 *
 * A plugin that is not in the list below can register itself with the
 * moghadam_portfolio_source filter.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Post types, each with its grouping taxonomy, in the order we try them.
 *
 * @return array
 */
function moghadam_portfolio_candidates() {
	return array(
		'jetpack-portfolio' => 'jetpack-portfolio-type',
		'portfolio'         => 'portfolio_category',
		'mpro_work'         => 'mpro_work_category',
		'project'           => 'project_category',
		'avada_portfolio'   => 'portfolio_category',
	);
}

/**
 * The active portfolio source, or null.
 *
 * @return array|null Array with 'post_type' and 'taxonomy'.
 */
function moghadam_portfolio_source() {
	static $source = false;

	if ( false !== $source ) {
		return $source;
	}

	$source = null;

	foreach ( moghadam_portfolio_candidates() as $post_type => $taxonomy ) {
		if ( post_type_exists( $post_type ) ) {
			$source = array(
				'post_type' => $post_type,
				'taxonomy'  => taxonomy_exists( $taxonomy ) ? $taxonomy : '',
			);
			break;
		}
	}

	/**
	 * Filters the portfolio source.
	 *
	 * Return an array with 'post_type' and optionally 'taxonomy' to point the
	 * section at a plugin the theme does not know about, or null to switch the
	 * section off.
	 *
	 * @since 1.3.0
	 *
	 * @param array|null $source Detected source.
	 */
	$source = apply_filters( 'moghadam_portfolio_source', $source );

	return $source;
}

/**
 * Whether section 04 has anything to show.
 *
 * @return bool
 */
function moghadam_portfolio_is_available() {
	$source = moghadam_portfolio_source();

	if ( empty( $source['post_type'] ) || ! post_type_exists( $source['post_type'] ) ) {
		return false;
	}

	$count = wp_count_posts( $source['post_type'] );

	return ! empty( $count->publish );
}

/**
 * The filter buttons: "all" plus every non-empty term.
 *
 * @return array List of slug/label arrays.
 */
function moghadam_portfolio_filters() {
	$source  = moghadam_portfolio_source();
	$filters = array(
		array(
			'slug'  => 'all',
			'label' => moghadam_home( 'works', 'all_label' ),
		),
	);

	if ( empty( $source['taxonomy'] ) || ! taxonomy_exists( $source['taxonomy'] ) ) {
		return $filters;
	}

	$terms = get_terms(
		array(
			'taxonomy'   => $source['taxonomy'],
			'hide_empty' => true,
		)
	);

	if ( is_wp_error( $terms ) ) {
		return $filters;
	}

	foreach ( $terms as $term ) {
		$filters[] = array(
			'slug'  => $term->slug,
			'label' => $term->name,
		);
	}

	return $filters;
}

/**
 * The newest items for one filter.
 *
 * Every filter always shows the same number of items — its own newest ones —
 * regardless of which filter is active, and fewer only when it holds fewer.
 *
 * @param string $filter Term slug, or 'all'.
 * @return array List of id/title/permalink/thumbnail arrays.
 */
function moghadam_portfolio_items( $filter = 'all' ) {
	$source = moghadam_portfolio_source();

	if ( empty( $source['post_type'] ) ) {
		return array();
	}

	$args = array(
		'post_type'           => $source['post_type'],
		'post_status'         => 'publish',
		'posts_per_page'      => max( 1, (int) moghadam_home( 'works', 'count' ) ),
		'orderby'             => 'date',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	if ( 'all' !== $filter && ! empty( $source['taxonomy'] ) ) {
		$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => $source['taxonomy'],
				'field'    => 'slug',
				'terms'    => $filter,
			),
		);
	}

	$query = new WP_Query( $args );
	$items = array();
	$index = 0;

	foreach ( $query->posts as $post ) {
		$index++;

		$items[] = array(
			'id'        => $post->ID,
			'title'     => get_the_title( $post ),
			'permalink' => get_permalink( $post ),
			'number'    => str_pad( (string) $index, 3, '0', STR_PAD_LEFT ),
			'thumbnail' => get_the_post_thumbnail( $post, 'large', array( 'loading' => 'lazy' ) ),
		);
	}

	/**
	 * Filters the items for one portfolio filter.
	 *
	 * @since 1.3.0
	 *
	 * @param array  $items  Items.
	 * @param string $filter Term slug or 'all'.
	 */
	return apply_filters( 'moghadam_portfolio_items', $items, $filter );
}
