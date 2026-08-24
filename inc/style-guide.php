<?php
/**
 * Style guide data.
 *
 * The single source of truth for what the "Theme" page template renders. In a
 * later release the same arrays will be fed from the theme settings, so the
 * style guide always reflects the values actually in use.
 *
 * @package Moghadam
 * @since   1.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Colour tokens shown in the style guide.
 *
 * @return array List of token arrays with 'var' and 'label' keys.
 */
function moghadam_style_guide_colors() {
	return apply_filters(
		'moghadam_style_guide_colors',
		array(
			array(
				'var'   => '--moghadam-color-accent',
				'label' => __( 'Accent', 'moghadam' ),
				'usage' => __( 'Links, buttons, focus rings, active states.', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-color-text',
				'label' => __( 'Text', 'moghadam' ),
				'usage' => __( 'Body copy and headings.', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-color-muted',
				'label' => __( 'Muted', 'moghadam' ),
				'usage' => __( 'Meta lines, captions, secondary copy.', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-color-bg',
				'label' => __( 'Background', 'moghadam' ),
				'usage' => __( 'Page background.', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-color-surface',
				'label' => __( 'Surface', 'moghadam' ),
				'usage' => __( 'Code blocks, wells, raised areas.', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-color-border',
				'label' => __( 'Border', 'moghadam' ),
				'usage' => __( 'Rules, table cells, input outlines.', 'moghadam' ),
			),
		)
	);
}

/**
 * Typography tokens shown in the style guide.
 *
 * @return array List of token arrays.
 */
function moghadam_style_guide_typography() {
	return apply_filters(
		'moghadam_style_guide_typography',
		array(
			array(
				'var'   => '--moghadam-font-body',
				'label' => __( 'Body font', 'moghadam' ),
				'usage' => __( 'Everything that is not code.', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-font-heading',
				'label' => __( 'Heading font', 'moghadam' ),
				'usage' => __( 'H1 through H6.', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-font-mono',
				'label' => __( 'Monospace font', 'moghadam' ),
				'usage' => __( 'Inline code and code blocks.', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-font-size-base',
				'label' => __( 'Base font size', 'moghadam' ),
				'usage' => __( 'Root size the scale is derived from.', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-line-height',
				'label' => __( 'Line height', 'moghadam' ),
				'usage' => __( 'Body copy leading.', 'moghadam' ),
			),
		)
	);
}

/**
 * Spacing and sizing tokens shown in the style guide.
 *
 * @return array List of token arrays.
 */
function moghadam_style_guide_spacing() {
	return apply_filters(
		'moghadam_style_guide_spacing',
		array(
			array(
				'var'   => '--moghadam-space-xs',
				'label' => __( 'Space XS', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-space-sm',
				'label' => __( 'Space SM', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-space-md',
				'label' => __( 'Space MD', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-space-lg',
				'label' => __( 'Space LG', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-space-xl',
				'label' => __( 'Space XL', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-radius',
				'label' => __( 'Corner radius', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-content',
				'label' => __( 'Content width', 'moghadam' ),
			),
			array(
				'var'   => '--moghadam-container',
				'label' => __( 'Container width', 'moghadam' ),
			),
		)
	);
}
