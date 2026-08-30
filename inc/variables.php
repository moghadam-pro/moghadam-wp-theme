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
			'label'       => __( 'Colors', 'moghadam' ),
			'description' => __( 'Each color is defined twice, once per mode. Both sets are always written to the page; which one applies is decided at runtime.', 'moghadam' ),
			'per_mode'    => true,
			'control'     => 'color',
			'tokens'      => array(
				'accent'  => array(
					'var'   => '--moghadam-color-accent',
					'label' => __( 'Accent', 'moghadam' ),
					'usage' => __( 'Links, buttons, focus rings, active menu items, blockquote border.', 'moghadam' ),
					'light' => '#2563eb',
					'dark'  => '#6ea8fe',
				),
				'text'    => array(
					'var'   => '--moghadam-color-text',
					'label' => __( 'Text', 'moghadam' ),
					'usage' => __( 'Body copy, headings, navigation labels.', 'moghadam' ),
					'light' => '#1f2328',
					'dark'  => '#e6edf3',
				),
				'muted'   => array(
					'var'   => '--moghadam-color-muted',
					'label' => __( 'Muted', 'moghadam' ),
					'usage' => __( 'Entry meta, captions, footer text, secondary copy.', 'moghadam' ),
					'light' => '#6a737d',
					'dark'  => '#8b949e',
				),
				'background' => array(
					'var'   => '--moghadam-color-bg',
					'label' => __( 'Background', 'moghadam' ),
					'usage' => __( 'Page background, input fields, mobile menu panel.', 'moghadam' ),
					'light' => '#ffffff',
					'dark'  => '#0d1117',
				),
				'surface' => array(
					'var'   => '--moghadam-color-surface',
					'label' => __( 'Surface', 'moghadam' ),
					'usage' => __( 'Code blocks, inline code, raised areas.', 'moghadam' ),
					'light' => '#f6f8fa',
					'dark'  => '#161b22',
				),
				'border'  => array(
					'var'   => '--moghadam-color-border',
					'label' => __( 'Border', 'moghadam' ),
					'usage' => __( 'Header and footer rules, table cells, input outlines, post separators.', 'moghadam' ),
					'light' => '#e1e4e8',
					'dark'  => '#30363d',
				),
			),
		),
		'glass'      => array(
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
		'typography' => array(
			'label'       => __( 'Typography', 'moghadam' ),
			'description' => __( 'Shared by both modes. Font stacks accept any CSS font-family value; keep a system fallback at the end.', 'moghadam' ),
			'per_mode'    => false,
			'control'     => 'text',
			'tokens'      => array(
				'font-body'      => array(
					'var'     => '--moghadam-font-body',
					'label'   => __( 'Body font', 'moghadam' ),
					'usage'   => __( 'Everything that is not code.', 'moghadam' ),
					'default' => 'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
				),
				'font-heading'   => array(
					'var'     => '--moghadam-font-heading',
					'label'   => __( 'Heading font', 'moghadam' ),
					'usage'   => __( 'H1 through H6.', 'moghadam' ),
					'default' => 'var(--moghadam-font-body)',
				),
				'font-mono'      => array(
					'var'     => '--moghadam-font-mono',
					'label'   => __( 'Monospace font', 'moghadam' ),
					'usage'   => __( 'Inline code, code blocks, keyboard input.', 'moghadam' ),
					'default' => 'ui-monospace, SFMono-Regular, Menlo, Consolas, monospace',
				),
				'font-size-base' => array(
					'var'     => '--moghadam-font-size-base',
					'label'   => __( 'Base font size', 'moghadam' ),
					'usage'   => __( 'Root size the whole scale is derived from.', 'moghadam' ),
					'default' => '1rem',
					'control' => 'size',
				),
				'line-height'    => array(
					'var'     => '--moghadam-line-height',
					'label'   => __( 'Line height', 'moghadam' ),
					'usage'   => __( 'Body copy leading. Unitless.', 'moghadam' ),
					'default' => '1.7',
					'control' => 'size',
				),
			),
		),
		'layout'     => array(
			'label'       => __( 'Spacing and sizing', 'moghadam' ),
			'description' => __( 'Shared by both modes. Any CSS length is accepted.', 'moghadam' ),
			'per_mode'    => false,
			'control'     => 'size',
			'tokens'      => array(
				'space-xs'  => array(
					'var'     => '--moghadam-space-xs',
					'label'   => __( 'Space XS', 'moghadam' ),
					'usage'   => __( 'Table cells, tight gaps, gallery gutters.', 'moghadam' ),
					'default' => '0.5rem',
				),
				'space-sm'  => array(
					'var'     => '--moghadam-space-sm',
					'label'   => __( 'Space SM', 'moghadam' ),
					'usage'   => __( 'Container padding, header padding, heading margins.', 'moghadam' ),
					'default' => '1rem',
				),
				'space-md'  => array(
					'var'     => '--moghadam-space-md',
					'label'   => __( 'Space MD', 'moghadam' ),
					'usage'   => __( 'Paragraph and block spacing, navigation gaps.', 'moghadam' ),
					'default' => '1.5rem',
				),
				'space-lg'  => array(
					'var'     => '--moghadam-space-lg',
					'label'   => __( 'Space LG', 'moghadam' ),
					'usage'   => __( 'Section padding, post separation, widget spacing.', 'moghadam' ),
					'default' => '2.5rem',
				),
				'space-xl'  => array(
					'var'     => '--moghadam-space-xl',
					'label'   => __( 'Space XL', 'moghadam' ),
					'usage'   => __( 'Comments area offset.', 'moghadam' ),
					'default' => '4rem',
				),
				'radius'    => array(
					'var'     => '--moghadam-radius',
					'label'   => __( 'Corner radius', 'moghadam' ),
					'usage'   => __( 'Buttons, inputs, images, code blocks.', 'moghadam' ),
					'default' => '6px',
				),
				'content'   => array(
					'var'     => '--moghadam-content',
					'label'   => __( 'Content width', 'moghadam' ),
					'usage'   => __( 'The reading measure on the default template.', 'moghadam' ),
					'default' => '800px',
				),
				'container' => array(
					'var'     => '--moghadam-container',
					'label'   => __( 'Container width', 'moghadam' ),
					'usage'   => __( 'Outer page width and wide alignments.', 'moghadam' ),
					'default' => '1100px',
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
	$schema   = moghadam_variables_schema();
	$defaults = moghadam_variables_defaults();
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

			$declarations[] = "\t" . $token['var'] . ': ' . $value . ';';
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

	$css  = ":root {\n" . $light . "\n}\n";
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
