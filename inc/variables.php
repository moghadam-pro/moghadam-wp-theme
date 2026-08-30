<?php
/**
 * Design variables.
 *
 * The schema below is the single source of truth for every design token the
 * theme exposes. The settings screen renders from it, the front-end CSS is
 * generated from it, and the block editor palette is filtered from it, so the
 * three can never drift apart.
 *
 * @package Moghadam
 * @since   1.2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Name of the option holding every theme setting.
 */
const MOGHADAM_OPTION = 'moghadam_settings';

/**
 * The design token schema.
 *
 * Groups marked 'per_mode' hold a separate value for light and dark. The rest
 * hold one value shared by both.
 *
 * @return array
 */
function moghadam_variables_schema() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$schema = array(
		'colors'     => array(
			'tab'         => 'colors',
			'label'       => __( 'Semantic Colors', 'moghadam' ),
			'description' => __( 'What the page is actually painted with. Each is defined twice, once per mode; both sets are written to the page and the runtime toggle decides which applies.', 'moghadam' ),
			'per_mode'    => true,
			'control'     => 'color',
			'tokens'      => array(
				'accent'      => array(
					'var'   => '--yellow',
					'alias' => array( '--moghadam-color-accent' ),
					'label' => __( 'Accent', 'moghadam' ),
					'usage' => __( 'Buttons, the marquee bar, active filters, link hovers, the beam of light on the grid.', 'moghadam' ),
					'light' => '#eeaa00',
					'dark'  => '#eeaa00',
				),
				'link'        => array(
					'var'   => '--veryperi-400',
					'label' => __( 'Link', 'moghadam' ),
					'usage' => __( 'The name in the hero greeting and highlighted phrases in body copy.', 'moghadam' ),
					'light' => '#7374bf',
					'dark'  => '#9394d6',
				),
				'background'  => array(
					'var'   => '--bg',
					'alias' => array( '--moghadam-color-bg' ),
					'label' => __( 'Background', 'moghadam' ),
					'usage' => __( 'The page itself.', 'moghadam' ),
					'light' => '#fffdfa',
					'dark'  => '#0b0b0c',
				),
				'background-inverse' => array(
					'var'   => '--bg-inverse',
					'label' => __( 'Inverted background', 'moghadam' ),
					'usage' => __( 'The dark bands: the primary button and the closing call to action.', 'moghadam' ),
					'light' => '#111112',
					'dark'  => '#fffdfa',
				),
				'text'        => array(
					'var'   => '--text',
					'alias' => array( '--moghadam-color-text' ),
					'label' => __( 'Text', 'moghadam' ),
					'usage' => __( 'Headings and anything that has to carry weight.', 'moghadam' ),
					'light' => '#111112',
					'dark'  => '#f6f6f7',
				),
				'text-strong' => array(
					'var'   => '--text-strong',
					'label' => __( 'Text, strong', 'moghadam' ),
					'usage' => __( 'The hero greeting, one step softer than headings.', 'moghadam' ),
					'light' => '#2a2a2d',
					'dark'  => '#e9e9eb',
				),
				'text-body'   => array(
					'var'   => '--text-body',
					'label' => __( 'Text, body', 'moghadam' ),
					'usage' => __( 'Paragraphs, menu labels, the skills list.', 'moghadam' ),
					'light' => '#454549',
					'dark'  => '#b6b6bc',
				),
				'text-muted'  => array(
					'var'   => '--text-muted',
					'alias' => array( '--moghadam-color-muted' ),
					'label' => __( 'Text, muted', 'moghadam' ),
					'usage' => __( 'The header status line, the clock, social icons.', 'moghadam' ),
					'light' => '#77777e',
					'dark'  => '#8a8a92',
				),
				'text-faint'  => array(
					'var'   => '--text-faint',
					'label' => __( 'Text, faint', 'moghadam' ),
					'usage' => __( 'Section numbers and labels, corner links, footer legal line.', 'moghadam' ),
					'light' => '#939399',
					'dark'  => '#6d6d75',
				),
				'text-ghost'  => array(
					'var'   => '--text-ghost',
					'label' => __( 'Text, ghost', 'moghadam' ),
					'usage' => __( 'The oversized step number behind How I Work.', 'moghadam' ),
					'light' => '#f2f2f3',
					'dark'  => '#1b1b1e',
				),
				'surface'     => array(
					'var'   => '--card',
					'alias' => array( '--moghadam-color-surface' ),
					'label' => __( 'Surface', 'moghadam' ),
					'usage' => __( 'Visual work cards, code blocks, raised areas.', 'moghadam' ),
					'light' => '#f8f8f8',
					'dark'  => '#131315',
				),
				'surface-hover' => array(
					'var'   => '--card-hover',
					'label' => __( 'Surface, hover', 'moghadam' ),
					'usage' => __( 'A work card under the cursor.', 'moghadam' ),
					'light' => '#e5e5e6',
					'dark'  => '#1d1d21',
				),
				'border'      => array(
					'var'   => '--line',
					'alias' => array( '--moghadam-color-border' ),
					'label' => __( 'Hairline', 'moghadam' ),
					'usage' => __( 'Section rules, separators, ghost button outlines.', 'moghadam' ),
					'light' => '#e5e5e6',
					'dark'  => '#26262a',
				),
				'border-strong' => array(
					'var'   => '--line-strong',
					'label' => __( 'Hairline, strong', 'moghadam' ),
					'usage' => __( 'The dotted footer rule and the dots between skills.', 'moghadam' ),
					'light' => '#c8c8cb',
					'dark'  => '#33333a',
				),
				'grid-line'   => array(
					'var'     => '--grid-line',
					'label'   => __( 'Background grid', 'moghadam' ),
					'usage'   => __( 'The twelve guide lines behind every section. Takes any colour value, so it can carry an alpha.', 'moghadam' ),
					'control' => 'text',
					'palette' => false,
					'light'   => 'rgba(17, 17, 18, .055)',
					'dark'    => 'rgba(255, 255, 255, .05)',
				),
				'beam'        => array(
					'var'     => '--beam',
					'label'   => __( 'Beam', 'moghadam' ),
					'usage'   => __( 'Head of the light that travels down a guide line.', 'moghadam' ),
					'control' => 'text',
					'palette' => false,
					'light'   => 'rgba(238, 170, 0, .9)',
					'dark'    => 'rgba(255, 255, 255, .85)',
				),
				'beam-soft'   => array(
					'var'     => '--beam-soft',
					'label'   => __( 'Beam halo', 'moghadam' ),
					'usage'   => __( 'The glow either side of that light.', 'moghadam' ),
					'control' => 'text',
					'palette' => false,
					'light'   => 'rgba(238, 170, 0, .22)',
					'dark'    => 'rgba(255, 255, 255, .18)',
				),
			),
		),
		'palette'    => array(
			'tab'         => 'colors',
			'description' => __( 'The raw ramp the semantic colours are drawn from. Shared by both modes.', 'moghadam' ),
			'label'       => __( 'Palette', 'moghadam' ),
			'per_mode'    => false,
			'control'     => 'color',
			'tokens'      => array(
				'orange' => array(
					'var'     => '--orange',
					'label'   => __( 'Orange', 'moghadam' ),
					'usage'   => __( 'The plate behind the About portrait.', 'moghadam' ),
					'palette' => false,
					'default' => '#e35b0c',
				),
				'moon'   => array(
					'var'     => '--moon',
					'label'   => __( 'Moon', 'moghadam' ),
					'usage'   => __( 'The theme switch icon once dark mode is on.', 'moghadam' ),
					'palette' => false,
					'default' => '#9fbafe',
				),
				'heart'  => array(
					'var'     => '--heart',
					'label'   => __( 'Heart', 'moghadam' ),
					'usage'   => __( 'The heart in the footer colophon.', 'moghadam' ),
					'palette' => false,
					'default' => '#e8422f',
				),
			),
		),
		'typography' => array(
			'tab'         => 'typography',
			'label'       => __( 'Typography', 'moghadam' ),
			'description' => __( 'Shared by both modes. Font stacks accept any CSS font-family value; keep a system fallback at the end.', 'moghadam' ),
			'per_mode'    => false,
			'control'     => 'text',
			'tokens'      => array(
				'font-display'   => array(
					'var'     => '--font-display',
					'alias'   => array( '--moghadam-font-heading' ),
					'label'   => __( 'Display font', 'moghadam' ),
					'usage'   => __( 'Every headline: the hero, section titles, project names, step titles.', 'moghadam' ),
					'default' => '"Averia Sans Libre", "Trebuchet MS", sans-serif',
				),
				'font-mono'      => array(
					'var'     => '--font-mono',
					'alias'   => array( '--moghadam-font-body', '--moghadam-font-mono' ),
					'label'   => __( 'Body font', 'moghadam' ),
					'usage'   => __( 'The monospace face that carries all body copy, menus and metadata.', 'moghadam' ),
					'default' => '"JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Consolas, monospace',
				),
				'font-ui'        => array(
					'var'     => '--font-ui',
					'label'   => __( 'Interface font', 'moghadam' ),
					'usage'   => __( 'Button labels only.', 'moghadam' ),
					'default' => '"Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
				),
				'fs-display'     => array(
					'var'     => '--fs-display',
					'label'   => __( 'Hero headline', 'moghadam' ),
					'usage'   => __( 'The H1. Steps down at each breakpoint.', 'moghadam' ),
					'control' => 'size',
					'default' => '48px',
				),
				'fs-h3'          => array(
					'var'     => '--fs-h3',
					'label'   => __( 'Large headline', 'moghadam' ),
					'usage'   => __( 'How I Work step titles.', 'moghadam' ),
					'control' => 'size',
					'default' => '40px',
				),
				'fs-h2'          => array(
					'var'     => '--fs-h2',
					'label'   => __( 'Section headline', 'moghadam' ),
					'usage'   => __( 'About quote, case studies title, call to action.', 'moghadam' ),
					'control' => 'size',
					'default' => '36px',
				),
				'fs-case'        => array(
					'var'     => '--fs-case',
					'label'   => __( 'Project name', 'moghadam' ),
					'usage'   => __( 'The names in the case study rows.', 'moghadam' ),
					'control' => 'size',
					'default' => '32px',
				),
				'fs-lead'        => array(
					'var'     => '--fs-lead',
					'label'   => __( 'Lead', 'moghadam' ),
					'usage'   => __( 'The hero greeting line.', 'moghadam' ),
					'control' => 'size',
					'default' => '24px',
				),
				'fs-btn'         => array(
					'var'     => '--fs-btn',
					'label'   => __( 'Button label', 'moghadam' ),
					'usage'   => __( 'Text inside buttons.', 'moghadam' ),
					'control' => 'size',
					'default' => '15px',
				),
				'fs-body'        => array(
					'var'     => '--fs-body',
					'alias'   => array( '--moghadam-font-size-base' ),
					'label'   => __( 'Body', 'moghadam' ),
					'usage'   => __( 'Paragraphs, menus, lists. The base of the whole scale.', 'moghadam' ),
					'control' => 'size',
					'default' => '14px',
				),
				'lh-body'        => array(
					'var'     => '--lh-body',
					'label'   => __( 'Body leading', 'moghadam' ),
					'usage'   => __( 'Line height for body copy. Deliberately generous.', 'moghadam' ),
					'control' => 'size',
					'default' => '28px',
				),
				'fs-meta'        => array(
					'var'     => '--fs-meta',
					'label'   => __( 'Metadata', 'moghadam' ),
					'usage'   => __( 'Section numbers and labels, the clock, the footer legal line.', 'moghadam' ),
					'control' => 'size',
					'default' => '12px',
				),
				'line-height'    => array(
					'var'     => '--moghadam-line-height',
					'label'   => __( 'Prose leading', 'moghadam' ),
					'usage'   => __( 'Unitless line height for post and page content.', 'moghadam' ),
					'control' => 'size',
					'default' => '1.7',
				),
			),
		),
		'layout'     => array(
			'tab'         => 'spacing',
			'label'       => __( 'Spacing and sizing', 'moghadam' ),
			'description' => __( 'Shared by both modes. The container and the section padding set the rhythm of the whole page.', 'moghadam' ),
			'per_mode'    => false,
			'control'     => 'size',
			'tokens'      => array(
				'container'   => array(
					'var'     => '--container',
					'alias'   => array( '--moghadam-container' ),
					'label'   => __( 'Container', 'moghadam' ),
					'usage'   => __( 'Width of the content column and of the twelve background guide lines.', 'moghadam' ),
					'default' => '1200px',
				),
				'gutter'      => array(
					'var'     => '--gutter',
					'label'   => __( 'Gutter', 'moghadam' ),
					'usage'   => __( 'Inner padding of the container, and the grid gap between work cards.', 'moghadam' ),
					'default' => '16px',
				),
				'section-pad' => array(
					'var'     => '--section-pad',
					'label'   => __( 'Section padding', 'moghadam' ),
					'usage'   => __( 'Fixed space above and below every numbered section.', 'moghadam' ),
					'default' => '120px',
				),
				'radius'      => array(
					'var'     => '--radius',
					'alias'   => array( '--moghadam-radius' ),
					'label'   => __( 'Corner radius', 'moghadam' ),
					'usage'   => __( 'Buttons and work cards.', 'moghadam' ),
					'default' => '8px',
				),
				'content'     => array(
					'var'     => '--moghadam-content',
					'label'   => __( 'Reading measure', 'moghadam' ),
					'usage'   => __( 'Width of prose on posts and pages.', 'moghadam' ),
					'default' => '800px',
				),
				'space-xs'    => array(
					'var'     => '--moghadam-space-xs',
					'label'   => __( 'Space, extra small', 'moghadam' ),
					'usage'   => __( 'Tight gaps inside post and page content.', 'moghadam' ),
					'default' => '0.5rem',
				),
				'space-sm'    => array(
					'var'     => '--moghadam-space-sm',
					'label'   => __( 'Space, small', 'moghadam' ),
					'usage'   => __( 'Gaps between related elements in prose.', 'moghadam' ),
					'default' => '1rem',
				),
				'space-md'    => array(
					'var'     => '--moghadam-space-md',
					'label'   => __( 'Space, medium', 'moghadam' ),
					'usage'   => __( 'Paragraph rhythm.', 'moghadam' ),
					'default' => '1.5rem',
				),
				'space-lg'    => array(
					'var'     => '--moghadam-space-lg',
					'label'   => __( 'Space, large', 'moghadam' ),
					'usage'   => __( 'Space around blocks of prose.', 'moghadam' ),
					'default' => '2.5rem',
				),
				'space-xl'    => array(
					'var'     => '--moghadam-space-xl',
					'label'   => __( 'Space, extra large', 'moghadam' ),
					'usage'   => __( 'Space between major regions on inner pages.', 'moghadam' ),
					'default' => '4rem',
				),
			),
		),
		'glass'      => array(
			'tab'         => 'sticky-header',
			'label'       => __( 'Sticky Header Glass', 'moghadam' ),
			'description' => __( 'The floating bar is a lens over the page. Raise the blur and the tint until the bar\'s own labels stay readable over the busiest section; lower them to let more of the page through. Defined twice, once per mode.', 'moghadam' ),
			'per_mode'    => true,
			'control'     => 'text',
			'tokens'      => array(
				'blur'         => array(
					'var'     => '--glass-blur',
					'label'   => __( 'Blur', 'moghadam' ),
					'usage'   => __( 'How far the page behind the bar is smeared. The main lever for legibility.', 'moghadam' ),
					'control' => 'size',
					'light'   => '32px',
					'dark'    => '32px',
				),
				'blur-lens'    => array(
					'var'     => '--glass-blur-lens',
					'label'   => __( 'Blur with refraction', 'moghadam' ),
					'usage'   => __( 'Used instead of the above when the refraction lens is active, since the lens adds its own softening.', 'moghadam' ),
					'control' => 'size',
					'light'   => '26px',
					'dark'    => '26px',
				),
				'tint'         => array(
					'var'     => '--glass-tint',
					'label'   => __( 'Tint', 'moghadam' ),
					'usage'   => __( 'The colour mixed over the blurred backdrop. Near-white frosts, near-black smokes.', 'moghadam' ),
					'control' => 'color',
					'light'   => '#fffdfa',
					'dark'    => '#17171a',
				),
				'tint-amount'  => array(
					'var'     => '--glass-tint-amount',
					'label'   => __( 'Tint strength', 'moghadam' ),
					'usage'   => __( 'How much of that colour is mixed in. Higher hides more of the page.', 'moghadam' ),
					'control' => 'size',
					'light'   => '66%',
					'dark'    => '66%',
				),
				'saturation'   => array(
					'var'     => '--glass-saturation',
					'label'   => __( 'Saturation', 'moghadam' ),
					'usage'   => __( 'Colour lift applied to the backdrop, which is what keeps glass from looking grey.', 'moghadam' ),
					'control' => 'size',
					'light'   => '180%',
					'dark'    => '160%',
				),
				'reflex-light' => array(
					'var'     => '--glass-reflex-light',
					'label'   => __( 'Highlight strength', 'moghadam' ),
					'usage'   => __( 'Multiplier for the bright bevel along the lit edge. 0 removes it.', 'moghadam' ),
					'control' => 'size',
					'light'   => '1',
					'dark'    => '.3',
				),
				'reflex-dark'  => array(
					'var'     => '--glass-reflex-dark',
					'label'   => __( 'Shade strength', 'moghadam' ),
					'usage'   => __( 'Multiplier for the shaded bevel and the drop shadow.', 'moghadam' ),
					'control' => 'size',
					'light'   => '1',
					'dark'    => '2',
				),
			),
		),
		'glass_lens' => array(
			'tab'         => 'sticky-header',
			'label'       => __( 'Sticky Header Refraction', 'moghadam' ),
			'description' => __( 'An SVG lens that bends the page behind the bar at its edges. Chromium only, desktop only, and skipped under reduced motion; everywhere else the blur above is used on its own.', 'moghadam' ),
			'per_mode'    => false,
			'control'     => 'text',
			'tokens'      => array(
				'lens-scale'  => array(
					'var'     => '--glass-lens-scale',
					'label'   => __( 'Refraction strength', 'moghadam' ),
					'usage'   => __( 'How far the edges bend what is behind them. 0 is flat, 0.42 is the default, above about 0.8 it warps.', 'moghadam' ),
					'control' => 'size',
					'default' => '0.42',
				),
				'lens-soften' => array(
					'var'     => '--glass-lens-soften',
					'label'   => __( 'Lens softening', 'moghadam' ),
					'usage'   => __( 'Blur applied inside the lens before it bends, relative to the bar. Keep it small.', 'moghadam' ),
					'control' => 'size',
					'default' => '0.03',
				),
			),
		),
	);

	/**
	 * Filters the design token schema.
	 *
	 * @since 1.2.0
	 *
	 * @param array $schema Token groups.
	 */
	$cache = apply_filters( 'moghadam_variables_schema', $schema );

	return $cache;
}

/**
 * Default value for every token, in storage shape.
 *
 * @return array
 */
function moghadam_variables_defaults() {
	$defaults = array();

	foreach ( moghadam_variables_schema() as $group_key => $group ) {
		if ( ! empty( $group['per_mode'] ) ) {
			$defaults[ $group_key ] = array(
				'light' => array(),
				'dark'  => array(),
			);

			foreach ( $group['tokens'] as $token_key => $token ) {
				$defaults[ $group_key ]['light'][ $token_key ] = $token['light'];
				$defaults[ $group_key ]['dark'][ $token_key ]  = $token['dark'];
			}

			continue;
		}

		$defaults[ $group_key ] = array();

		foreach ( $group['tokens'] as $token_key => $token ) {
			$defaults[ $group_key ][ $token_key ] = $token['default'];
		}
	}

	return $defaults;
}

/**
 * Every stored setting, merged over the defaults.
 *
 * @return array
 */
function moghadam_get_settings() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$stored = get_option( MOGHADAM_OPTION, array() );

	if ( ! is_array( $stored ) ) {
		$stored = array();
	}

	$settings = array(
		'variables' => moghadam_variables_defaults(),
	);

	if ( isset( $stored['variables'] ) && is_array( $stored['variables'] ) ) {
		foreach ( $settings['variables'] as $group_key => $group_defaults ) {
			if ( ! isset( $stored['variables'][ $group_key ] ) || ! is_array( $stored['variables'][ $group_key ] ) ) {
				continue;
			}

			$settings['variables'][ $group_key ] = moghadam_merge_stored(
				$group_defaults,
				$stored['variables'][ $group_key ]
			);
		}
	}

	$cache = $settings;

	return $cache;
}

/**
 * Merge stored values over defaults, one or two levels deep, keeping only keys
 * the defaults define and discarding empty values.
 *
 * @param array $defaults Default values.
 * @param array $stored   Stored values.
 * @return array
 */
function moghadam_merge_stored( $defaults, $stored ) {
	$merged = $defaults;

	foreach ( $defaults as $key => $value ) {
		if ( ! isset( $stored[ $key ] ) ) {
			continue;
		}

		if ( is_array( $value ) ) {
			$merged[ $key ] = moghadam_merge_stored( $value, (array) $stored[ $key ] );
			continue;
		}

		if ( '' !== trim( (string) $stored[ $key ] ) ) {
			$merged[ $key ] = $stored[ $key ];
		}
	}

	return $merged;
}

/**
 * Value of a single token.
 *
 * @param string $group Group key.
 * @param string $token Token key.
 * @param string $mode  Mode key for per-mode groups: 'light' or 'dark'.
 * @return string Empty string when the token is unknown.
 */
function moghadam_get_variable( $group, $token, $mode = 'light' ) {
	$variables = moghadam_get_settings()['variables'];

	if ( ! isset( $variables[ $group ] ) ) {
		return '';
	}

	if ( isset( $variables[ $group ][ $mode ][ $token ] ) ) {
		return $variables[ $group ][ $mode ][ $token ];
	}

	return isset( $variables[ $group ][ $token ] ) ? $variables[ $group ][ $token ] : '';
}

/*
 * -------------------------------------------------------------------------
 * Sanitization
 * -------------------------------------------------------------------------
 */

/**
 * Sanitize a value destined for a CSS declaration.
 *
 * Values are written into a style block, so anything that could terminate a
 * declaration, open a rule, or pull in an external resource is removed. Commas
 * and quotes survive, because font stacks need them.
 *
 * @param string $value Raw value.
 * @return string Sanitized value, or an empty string if it cannot be made safe.
 */
function moghadam_sanitize_css_value( $value ) {
	$value = wp_strip_all_tags( (string) $value );
	$value = preg_replace( '#/\*.*?\*/#s', '', $value );
	$value = str_replace( array( '{', '}', ';', '<', '>', '\\' ), '', $value );
	$value = trim( $value );

	if ( preg_match( '/(url\s*\(|expression\s*\(|javascript\s*:|@import|behavior\s*:)/i', $value ) ) {
		return '';
	}

	return $value;
}

/**
 * Sanitize a CSS length or unitless number.
 *
 * @param string $value Raw value.
 * @return string Sanitized value, or an empty string when malformed.
 */
function moghadam_sanitize_css_size( $value ) {
	$value = trim( (string) $value );

	if ( preg_match( '/^-?(\d+(\.\d+)?|\.\d+)(px|rem|em|%|vw|vh|vmin|vmax|ch|ex|pt)?$/', $value ) ) {
		return $value;
	}

	// Allow calc(), clamp(), min(), max() and var() built from safe characters.
	if ( preg_match( '/^(calc|clamp|min|max|var)\([a-zA-Z0-9\s\.,%()\-+*\/_]*\)$/', $value ) ) {
		return $value;
	}

	return '';
}

/**
 * Sanitize the whole option before it is written.
 *
 * Anything unrecognised is dropped rather than stored, and any value that fails
 * validation falls back to its default instead of leaving a gap.
 *
 * @param mixed $input Raw submitted value.
 * @return array
 */
function moghadam_sanitize_settings( $input ) {
	$schema = moghadam_variables_schema();

	// The screen posts one tab at a time, so anything missing from this
	// request has to fall back to what is already stored rather than to the
	// factory default, or saving Colors would reset Typography.
	$defaults = moghadam_get_settings()['variables'];
	$clean    = array( 'variables' => array() );

	$input = is_array( $input ) ? $input : array();
	$raw   = isset( $input['variables'] ) && is_array( $input['variables'] ) ? $input['variables'] : array();

	foreach ( $schema as $group_key => $group ) {
		$group_raw = isset( $raw[ $group_key ] ) && is_array( $raw[ $group_key ] ) ? $raw[ $group_key ] : array();

		if ( ! empty( $group['per_mode'] ) ) {
			foreach ( array( 'light', 'dark' ) as $mode ) {
				$mode_raw = isset( $group_raw[ $mode ] ) && is_array( $group_raw[ $mode ] ) ? $group_raw[ $mode ] : array();

				foreach ( $group['tokens'] as $token_key => $token ) {
					$value = isset( $mode_raw[ $token_key ] ) ? $mode_raw[ $token_key ] : '';
					$value = moghadam_sanitize_token( $value, $group, $token );

					$clean['variables'][ $group_key ][ $mode ][ $token_key ] =
						'' === $value ? $defaults[ $group_key ][ $mode ][ $token_key ] : $value;
				}
			}

			continue;
		}

		foreach ( $group['tokens'] as $token_key => $token ) {
			$value = isset( $group_raw[ $token_key ] ) ? $group_raw[ $token_key ] : '';
			$value = moghadam_sanitize_token( $value, $group, $token );

			$clean['variables'][ $group_key ][ $token_key ] =
				'' === $value ? $defaults[ $group_key ][ $token_key ] : $value;
		}
	}

	return $clean;
}

/**
 * Sanitize one token according to its control type.
 *
 * @param string $value Raw value.
 * @param array  $group Group definition.
 * @param array  $token Token definition.
 * @return string
 */
function moghadam_sanitize_token( $value, $group, $token ) {
	$control = isset( $token['control'] ) ? $token['control'] : $group['control'];

	switch ( $control ) {
		case 'color':
			return (string) sanitize_hex_color( $value );

		case 'size':
			return moghadam_sanitize_css_size( $value );

		default:
			return moghadam_sanitize_css_value( $value );
	}
}

/*
 * -------------------------------------------------------------------------
 * Output
 * -------------------------------------------------------------------------
 */

/**
 * Build the custom property declarations for one mode.
 *
 * @param string $mode 'light' or 'dark'.
 * @return string Declarations, newline separated.
 */
function moghadam_build_declarations( $mode ) {
	$variables    = moghadam_get_settings()['variables'];
	$declarations = array();

	foreach ( moghadam_variables_schema() as $group_key => $group ) {
		foreach ( $group['tokens'] as $token_key => $token ) {
			if ( ! empty( $group['per_mode'] ) ) {
				$value = isset( $variables[ $group_key ][ $mode ][ $token_key ] )
					? $variables[ $group_key ][ $mode ][ $token_key ]
					: '';
			} elseif ( 'light' === $mode ) {
				// Shared tokens are written once, with the light set.
				$value = isset( $variables[ $group_key ][ $token_key ] )
					? $variables[ $group_key ][ $token_key ]
					: '';
			} else {
				continue;
			}

			if ( '' === $value ) {
				continue;
			}

			// A token can publish under more than one name: the design owns
			// the short names and the older --moghadam-* names are kept as
			// aliases so the base stylesheet keeps resolving.
			$names = array_merge(
				array( $token['var'] ),
				isset( $token['alias'] ) ? (array) $token['alias'] : array()
			);

			foreach ( $names as $name ) {
				$declarations[] = "\t" . $name . ': ' . $value . ';';
			}
		}
	}

	return implode( "\n", $declarations );
}

/**
 * The complete generated stylesheet.
 *
 * Three blocks, in this order: the light set on bare :root, the dark set behind
 * prefers-color-scheme guarded against an explicit light choice, and the dark
 * set again under an explicit data-theme attribute. Written this way the
 * runtime toggle added in a later release wins in both directions.
 *
 * @return string
 */
function moghadam_variables_css() {
	$light = moghadam_build_declarations( 'light' );
	$dark  = moghadam_build_declarations( 'dark' );

	// :root:root, not :root. design.css declares the same tokens so the design
	// still holds if this stylesheet is dequeued (the Canvas template does
	// exactly that), and it loads afterwards, so the stored values need the
	// extra specificity to win.
	$css  = ":root:root {\n" . $light . "\n}\n";
	$nested_dark = "\t" . str_replace( "\n", "\n\t", $dark );

	$css .= "\n@media (prefers-color-scheme: dark) {\n\t:root:not([data-theme=\"light\"]) {\n" . $nested_dark . "\n\t}\n}\n";
	$css .= "\n:root[data-theme=\"dark\"] {\n" . $dark . "\n}\n";

	/**
	 * Filters the generated variables stylesheet.
	 *
	 * @since 1.2.0
	 *
	 * @param string $css Generated CSS.
	 */
	return apply_filters( 'moghadam_variables_css', $css );
}

/**
 * Attach the generated stylesheet after the theme's own styles.
 *
 * Inline rather than a separate request, and attached to moghadam-main so the
 * cascade order is explicit. Canvas dequeues that handle, which correctly takes
 * the variables with it.
 */
function moghadam_enqueue_variables() {
	wp_add_inline_style( 'moghadam-main', moghadam_variables_css() );
}
add_action( 'wp_enqueue_scripts', 'moghadam_enqueue_variables', 20 );

/**
 * Feed the stored palette into the block editor.
 *
 * theme.json stays in the repository as the default; this overlays the values
 * actually in use, so the editor palette follows the settings instead of being
 * maintained by hand. theme.json has no concept of modes, so the editor gets
 * the light set.
 *
 * Requires WordPress 6.1, where this filter was introduced. On older versions
 * the static theme.json values apply unchanged.
 *
 * @param WP_Theme_JSON_Data $theme_json Theme JSON data.
 * @return WP_Theme_JSON_Data
 */
function moghadam_filter_theme_json( $theme_json ) {
	$schema    = moghadam_variables_schema();
	$variables = moghadam_get_settings()['variables'];
	$palette   = array();

	if ( ! isset( $schema['colors']['tokens'] ) ) {
		return $theme_json;
	}

	foreach ( $schema['colors']['tokens'] as $token_key => $token ) {
		// Tokens that carry an alpha or a shadow are not palette entries.
		if ( isset( $token['palette'] ) && false === $token['palette'] ) {
			continue;
		}

		$palette[] = array(
			'slug'  => $token_key,
			'name'  => $token['label'],
			'color' => $variables['colors']['light'][ $token_key ],
		);
	}

	$data = array(
		'version'  => 2,
		'settings' => array(
			'color'  => array(
				'palette' => $palette,
			),
			'layout' => array(
				'contentSize' => $variables['layout']['content'],
				'wideSize'    => $variables['layout']['container'],
			),
		),
	);

	return $theme_json->update_with( $data );
}
add_filter( 'wp_theme_json_data_theme', 'moghadam_filter_theme_json' );
