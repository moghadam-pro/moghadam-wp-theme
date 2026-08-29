<?php
/**
 * Social links.
 *
 * Stored once as plain text in the Customizer and reused by the hero and the
 * footer. Each entry is a block of three parts separated by a line of dashes:
 *
 *     LinkedIn
 *     https://www.linkedin.com/in/example
 *     <svg viewBox="0 0 24 24">...</svg>
 *     ---
 *     GitHub
 *     https://github.com/example
 *
 * The SVG is optional: leave it out and the icon is taken from the theme's
 * own sprite by matching the label, which covers every network the design
 * already ships an icon for.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The markup an author may use for an icon.
 *
 * @return array
 */
function moghadam_social_allowed_svg() {
	$attrs = array(
		'class'            => true,
		'fill'             => true,
		'stroke'           => true,
		'stroke-width'     => true,
		'stroke-linecap'   => true,
		'stroke-linejoin'  => true,
		'stroke-miterlimit'=> true,
		'transform'        => true,
		'opacity'          => true,
		'clip-rule'        => true,
		'fill-rule'        => true,
	);

	return array(
		'svg'      => array_merge(
			$attrs,
			array(
				'xmlns'   => true,
				'viewbox' => true,
				'width'   => true,
				'height'  => true,
				'role'    => true,
				'aria-hidden' => true,
			)
		),
		'g'        => $attrs,
		'title'    => array(),
		'path'     => array_merge( $attrs, array( 'd' => true ) ),
		'circle'   => array_merge( $attrs, array( 'cx' => true, 'cy' => true, 'r' => true ) ),
		'ellipse'  => array_merge( $attrs, array( 'cx' => true, 'cy' => true, 'rx' => true, 'ry' => true ) ),
		'rect'     => array_merge( $attrs, array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true ) ),
		'line'     => array_merge( $attrs, array( 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true ) ),
		'polyline' => array_merge( $attrs, array( 'points' => true ) ),
		'polygon'  => array_merge( $attrs, array( 'points' => true ) ),
		'defs'     => array(),
		'clippath' => array( 'id' => true ),
		'use'      => array( 'href' => true, 'xlink:href' => true, 'class' => true ),
	);
}

/**
 * Sanitize the raw Customizer value.
 *
 * @param string $value Raw textarea value.
 * @return string
 */
function moghadam_sanitize_social_links( $value ) {
	$entries = moghadam_parse_social_links( $value );
	$out     = array();

	foreach ( $entries as $entry ) {
		$out[] = $entry['label'] . "\n" . $entry['url'] . "\n" . $entry['svg'];
	}

	return implode( "\n---\n", $out );
}

/**
 * Parse the raw text into structured entries.
 *
 * @param string $raw Raw textarea value.
 * @return array List of label/url/svg arrays.
 */
function moghadam_parse_social_links( $raw ) {
	$blocks  = preg_split( '/^\s*-{3,}\s*$/m', (string) $raw );
	$entries = array();

	foreach ( (array) $blocks as $block ) {
		$lines = preg_split( '/\R/', trim( (string) $block ) );
		$lines = array_values( array_filter( array_map( 'trim', (array) $lines ), 'strlen' ) );

		if ( count( $lines ) < 2 ) {
			continue;
		}

		$label = sanitize_text_field( array_shift( $lines ) );
		$url   = esc_url_raw( array_shift( $lines ) );
		$svg   = wp_kses( implode( "\n", $lines ), moghadam_social_allowed_svg() );

		if ( '' === $label || '' === $url ) {
			continue;
		}

		$entries[] = array(
			'label' => $label,
			'url'   => $url,
			'svg'   => $svg,
			'key'   => sanitize_title( $label ),
		);
	}

	return $entries;
}

/**
 * All registered social links.
 *
 * @return array
 */
function moghadam_social_links() {
	return moghadam_parse_social_links( moghadam_home( 'social', 'links' ) );
}

/**
 * The subset a section asked for.
 *
 * @param string $labels Comma separated labels. Empty means everything.
 * @return array
 */
function moghadam_social_links_for( $labels = '' ) {
	$all = moghadam_social_links();
	$labels = trim( (string) $labels );

	if ( '' === $labels ) {
		return $all;
	}

	$wanted = array_filter( array_map( 'sanitize_title', array_map( 'trim', explode( ',', $labels ) ) ) );

	if ( empty( $wanted ) ) {
		return $all;
	}

	$picked = array();

	// Keep the order the author typed in the section field.
	foreach ( $wanted as $key ) {
		foreach ( $all as $entry ) {
			if ( $entry['key'] === $key ) {
				$picked[] = $entry;
				break;
			}
		}
	}

	return $picked;
}

/**
 * Render a social row.
 *
 * @param string $labels Comma separated labels, or empty for all.
 * @param array  $args   Optional. 'class' and 'anim'.
 */
function moghadam_social_row( $labels = '', $args = array() ) {
	$links = moghadam_social_links_for( $labels );

	if ( empty( $links ) ) {
		return;
	}

	$args = wp_parse_args(
		$args,
		array(
			'class' => 'social',
			'anim'  => '',
		)
	);

	printf(
		'<div class="%1$s"%2$s>',
		esc_attr( $args['class'] ),
		$args['anim'] ? ' data-anim="' . esc_attr( $args['anim'] ) . '"' : ''
	);

	foreach ( $links as $link ) {
		$icon = '' !== trim( $link['svg'] )
			? $link['svg']
			: moghadam_get_icon( $link['key'] );

		printf(
			'<a href="%1$s" aria-label="%2$s" rel="noopener">%3$s</a>',
			esc_url( $link['url'] ),
			esc_attr( $link['label'] ),
			$icon // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses'd markup or a theme sprite reference.
		);
	}

	echo '</div>';
}
