<?php
/**
 * Customizer additions.
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

	$wp_customize->add_section(
		'moghadam_colors',
		array(
			'title'    => esc_html__( 'Theme Colors', 'moghadam' ),
			'priority' => 40,
		)
	);

	$wp_customize->add_setting(
		'moghadam_accent_color',
		array(
			'default'           => '#2563eb',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'moghadam_accent_color',
			array(
				'label'   => esc_html__( 'Accent Color', 'moghadam' ),
				'section' => 'moghadam_colors',
			)
		)
	);
}
add_action( 'customize_register', 'moghadam_customize_register' );

/**
 * Output the Customizer-driven CSS custom properties.
 */
function moghadam_customizer_css() {
	$accent = get_theme_mod( 'moghadam_accent_color', '#2563eb' );

	if ( ! $accent ) {
		return;
	}

	printf(
		'<style id="moghadam-customizer-css">:root{--moghadam-color-accent:%s;}</style>',
		esc_attr( $accent )
	);
}
add_action( 'wp_head', 'moghadam_customizer_css', 20 );

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
