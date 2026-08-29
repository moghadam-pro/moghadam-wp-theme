<?php
/**
 * SVG icon sprite.
 *
 * Every file in assets/icons is exposed as <symbol id="i-{filename}">. The
 * sprite is printed once per request, right after <body>, and templates
 * reference symbols with moghadam_icon(). Hard-coded fills and strokes are
 * rewritten to currentColor so icons follow the theme.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icons the design needs that are not part of the exported set.
 *
 * @return array Symbol id => inner markup.
 */
function moghadam_inline_icons() {
	return array(
		'menu'          => '<path d="M3 6h18M3 12h18M3 18h18"/>',
		'close'         => '<path d="M18 6 6 18M6 6l12 12"/>',
		'chevron-down'  => '<path d="m6 9 6 6 6-6"/>',
	);
}

/**
 * Build the sprite markup from assets/icons plus the inline set.
 *
 * The result is cached in a transient because it means reading ~20 files.
 *
 * @return string
 */
function moghadam_icon_sprite() {
	$cache_key = 'moghadam_icon_sprite_' . MOGHADAM_VERSION;
	$cached    = get_transient( $cache_key );

	if ( is_string( $cached ) && '' !== $cached ) {
		return $cached;
	}

	$symbols = array();
	$files   = glob( MOGHADAM_DIR . '/assets/icons/*.svg' );

	if ( is_array( $files ) ) {
		sort( $files );

		foreach ( $files as $file ) {
			$raw = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

			if ( false === $raw ) {
				continue;
			}

			if ( ! preg_match( '/viewBox="([^"]+)"/', $raw, $view_box ) ) {
				continue;
			}

			$inner = preg_replace( '/^<svg[^>]*>/s', '', trim( $raw ) );
			$inner = str_replace( '</svg>', '', $inner );
			$inner = preg_replace( '/stroke="(?!none)[^"]*"/', 'stroke="currentColor"', $inner );
			$inner = preg_replace( '/fill="(?!none)[^"]*"/', 'fill="currentColor"', $inner );
			$inner = preg_replace( '/\s*id="[^"]*"/', '', $inner );

			$symbols[] = sprintf(
				'<symbol id="i-%1$s" viewBox="%2$s" fill="none">%3$s</symbol>',
				esc_attr( basename( $file, '.svg' ) ),
				esc_attr( $view_box[1] ),
				trim( $inner )
			);
		}
	}

	foreach ( moghadam_inline_icons() as $name => $body ) {
		$symbols[] = sprintf(
			'<symbol id="i-%1$s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">%2$s</symbol>',
			esc_attr( $name ),
			$body
		);
	}

	$sprite = '<svg class="u-sprite" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">' . implode( '', $symbols ) . '</svg>';

	set_transient( $cache_key, $sprite, DAY_IN_SECONDS );

	return $sprite;
}

/**
 * Print the sprite once, immediately after the opening body tag.
 */
function moghadam_print_icon_sprite() {
	echo moghadam_icon_sprite(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from theme files.
}
add_action( 'wp_body_open', 'moghadam_print_icon_sprite', 1 );

/**
 * Return an <svg><use> reference to a sprite symbol.
 *
 * @param string $name  Symbol name, without the i- prefix.
 * @param string $class Extra classes for the svg element.
 * @return string
 */
function moghadam_get_icon( $name, $class = '' ) {
	$classes = trim( 'icon ' . $class );

	return sprintf(
		'<svg class="%1$s" aria-hidden="true"><use href="#i-%2$s"></use></svg>',
		esc_attr( $classes ),
		esc_attr( $name )
	);
}

/**
 * Echo a sprite icon.
 *
 * @param string $name  Symbol name.
 * @param string $class Extra classes.
 */
function moghadam_icon( $name, $class = '' ) {
	echo moghadam_get_icon( $name, $class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Drop the cached sprite whenever the theme is switched or updated.
 */
function moghadam_flush_icon_sprite() {
	delete_transient( 'moghadam_icon_sprite_' . MOGHADAM_VERSION );
}
add_action( 'after_switch_theme', 'moghadam_flush_icon_sprite' );
add_action( 'upgrader_process_complete', 'moghadam_flush_icon_sprite' );
