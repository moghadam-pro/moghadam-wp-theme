<?php
/**
 * Appearance > Customize > Edit Home.
 *
 * The whole panel is generated from moghadam_home_schema(), so the editor and
 * the templates can never disagree about what exists.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sanitize callback for a schema field type.
 *
 * @param string $type Field type.
 * @return callable
 */
function moghadam_home_sanitizer( $type ) {
	switch ( $type ) {
		case 'url':
		case 'image':
			return 'moghadam_sanitize_link';
		case 'number':
			return 'absint';
		case 'checkbox':
			return 'moghadam_sanitize_checkbox';
		case 'html':
		case 'textarea':
			return 'moghadam_sanitize_html_field';
		case 'lines':
			return 'moghadam_sanitize_lines';
		case 'social':
			return 'moghadam_sanitize_social_links';
		default:
			return 'sanitize_text_field';
	}
}

/**
 * Allow in-page anchors as well as real URLs.
 *
 * @param string $value Raw value.
 * @return string
 */
function moghadam_sanitize_link( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	if ( '#' === $value[0] ) {
		return '#' . sanitize_title( substr( $value, 1 ) );
	}

	return esc_url_raw( $value );
}

/**
 * Sanitize a checkbox.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function moghadam_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Sanitize a rich-ish text field down to the markup the design uses.
 *
 * @param string $value Raw value.
 * @return string
 */
function moghadam_sanitize_html_field( $value ) {
	return wp_kses( (string) $value, moghadam_home_allowed_html() );
}

/**
 * Sanitize a one-per-line list.
 *
 * @param string $value Raw value.
 * @return string
 */
function moghadam_sanitize_lines( $value ) {
	$lines = preg_split( '/\R/', (string) $value );
	$lines = array_map( 'sanitize_text_field', (array) $lines );
	$lines = array_filter( array_map( 'trim', $lines ), 'strlen' );

	return implode( "\n", $lines );
}

/**
 * Register the Edit Home panel.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function moghadam_home_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'moghadam_home',
		array(
			'title'       => __( 'Edit Home', 'moghadam' ),
			'description' => __( 'Every piece of copy on the home page. Sections that read from posts or plugins say so in their own description.', 'moghadam' ),
			'priority'    => 25,
		)
	);

	foreach ( moghadam_home_schema() as $section_slug => $section ) {
		$section_id = 'moghadam_home_' . $section_slug;

		$wp_customize->add_section(
			$section_id,
			array(
				'title'       => $section['title'],
				'description' => isset( $section['description'] ) ? $section['description'] : '',
				'panel'       => 'moghadam_home',
				'priority'    => isset( $section['priority'] ) ? $section['priority'] : 10,
			)
		);

		foreach ( $section['fields'] as $field_slug => $field ) {
			$setting_id = moghadam_home_setting_id( $section_slug, $field_slug );

			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => $field['default'],
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'transport'         => 'refresh',
					'sanitize_callback' => moghadam_home_sanitizer( $field['type'] ),
				)
			);

			$args = array(
				'label'       => $field['label'],
				'description' => isset( $field['description'] ) ? $field['description'] : '',
				'section'     => $section_id,
				'settings'    => $setting_id,
			);

			switch ( $field['type'] ) {
				case 'image':
					$wp_customize->add_control(
						new WP_Customize_Image_Control( $wp_customize, $setting_id, $args )
					);
					break;

				case 'textarea':
				case 'html':
				case 'lines':
				case 'social':
					$args['type']            = 'textarea';
					$args['input_attrs']     = array( 'rows' => 'social' === $field['type'] ? 12 : 5 );
					$wp_customize->add_control( $setting_id, $args );
					break;

				case 'checkbox':
					$args['type'] = 'checkbox';
					$wp_customize->add_control( $setting_id, $args );
					break;

				case 'number':
					$args['type']        = 'number';
					$args['input_attrs'] = array(
						'min'  => 1,
						'step' => 1,
					);
					$wp_customize->add_control( $setting_id, $args );
					break;

				case 'url':
					$args['type'] = 'text';
					$wp_customize->add_control( $setting_id, $args );
					break;

				default:
					$args['type'] = 'text';
					$wp_customize->add_control( $setting_id, $args );
			}
		}
	}
}
add_action( 'customize_register', 'moghadam_home_customize_register' );
