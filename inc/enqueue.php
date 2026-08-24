<?php
/**
 * Scripts and styles.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue front-end assets.
 */
function moghadam_scripts() {
	wp_enqueue_style(
		'moghadam-style',
		get_stylesheet_uri(),
		array(),
		MOGHADAM_VERSION
	);
	wp_enqueue_style(
		'moghadam-main',
		MOGHADAM_URI . '/assets/css/main.css',
		array( 'moghadam-style' ),
		MOGHADAM_VERSION
	);

	if ( is_rtl() ) {
		wp_enqueue_style(
			'moghadam-rtl',
			MOGHADAM_URI . '/rtl.css',
			array( 'moghadam-main' ),
			MOGHADAM_VERSION
		);
	}

	wp_enqueue_script(
		'moghadam-navigation',
		MOGHADAM_URI . '/assets/js/navigation.js',
		array(),
		MOGHADAM_VERSION,
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'moghadam_scripts' );
