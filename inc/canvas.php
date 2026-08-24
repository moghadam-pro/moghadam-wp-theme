<?php
/**
 * Canvas template behaviour.
 *
 * Strips the theme's own presentation from pages using the Canvas template so
 * hand-written HTML, CSS and JS render in isolation, while leaving wp_head()
 * and wp_footer() intact for SEO and plugin output.
 *
 * @package Moghadam
 * @since   1.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Relative path of the Canvas page template.
 */
const MOGHADAM_CANVAS_TEMPLATE = 'page-templates/template-canvas.php';

/**
 * Whether the current request renders the Canvas template.
 *
 * @return bool
 */
function moghadam_is_canvas() {
	return is_page_template( MOGHADAM_CANVAS_TEMPLATE );
}

/**
 * Apply the Canvas exceptions once the main query is resolved.
 */
function moghadam_canvas_init() {
	if ( ! moghadam_is_canvas() ) {
		return;
	}

	// Keep hand-written markup untouched.
	remove_filter( 'the_content', 'wpautop' );
	remove_filter( 'the_content', 'shortcode_unautop' );

	// The admin bar pushes the document down by 32px and injects its own CSS.
	add_filter( 'show_admin_bar', '__return_false' );

	add_action( 'wp_enqueue_scripts', 'moghadam_canvas_dequeue', 100 );
}
add_action( 'template_redirect', 'moghadam_canvas_init' );

/**
 * Remove the theme's stylesheets, scripts and generated global styles.
 *
 * Only presentation that originates from this theme is removed. Plugin assets
 * are left alone, since a plugin may be what the page depends on.
 */
function moghadam_canvas_dequeue() {
	/**
	 * Filters the asset handles removed on the Canvas template.
	 *
	 * @since 1.1.0
	 *
	 * @param array $handles {
	 *     @type array $styles  Stylesheet handles to dequeue.
	 *     @type array $scripts Script handles to dequeue.
	 * }
	 */
	$handles = apply_filters(
		'moghadam_canvas_dequeue_handles',
		array(
			'styles'  => array(
				'moghadam-style',
				'moghadam-main',
				'moghadam-rtl',
				'global-styles',
				'classic-theme-styles',
			),
			'scripts' => array(
				'moghadam-navigation',
			),
		)
	);

	foreach ( $handles['styles'] as $handle ) {
		wp_dequeue_style( $handle );
		wp_deregister_style( $handle );
	}

	foreach ( $handles['scripts'] as $handle ) {
		wp_dequeue_script( $handle );
		wp_deregister_script( $handle );
	}
}
