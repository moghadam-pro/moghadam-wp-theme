<?php
/**
 * Customizer additions.
 *
 * Colour and typography are not configured here. They live in the theme's own
 * Variables screen, which is the single source of truth for every design token
 * (see the readme). What remains here is the live preview for the site title
 * and tagline, which belong to WordPress rather than to the theme.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Customizer settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 */
function moghadam_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->selective_refresh->add_partial(
		'blogname',
		array(
			'selector'        => '.site-title a',
			'render_callback' => function () {
				bloginfo( 'name' );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blogdescription',
		array(
			'selector'        => '.site-description',
			'render_callback' => function () {
				bloginfo( 'description' );
			},
		)
	);
}
add_action( 'customize_register', 'moghadam_customize_register' );

/**
 * Enqueue the Customizer live-preview script.
 */
function moghadam_customize_preview_js() {
	wp_enqueue_script(
		'moghadam-customizer',
		MOGHADAM_URI . '/assets/js/customizer.js',
		array( 'customize-preview', 'jquery' ),
		MOGHADAM_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'moghadam_customize_preview_js' );
