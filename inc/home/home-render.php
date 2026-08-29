<?php
/**
 * Rendering the home page.
 *
 * The hero always paints on its own; everything below it lives inside #rest,
 * which stays hidden until the visitor's first scroll. front-page.php and the
 * Home Page template both come through here.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The sections rendered below the hero, in order.
 *
 * @return array Section slug => partial name.
 */
function moghadam_home_sections() {
	/**
	 * Filters the home page section list.
	 *
	 * Keys are schema slugs used for the enabled toggle; values are the
	 * partial under template-parts/home.
	 *
	 * @since 1.3.0
	 *
	 * @param array $sections Section slug => partial.
	 */
	return apply_filters(
		'moghadam_home_sections',
		array(
			'marquee' => 'marquee',
			'about'   => 'about',
			'cases'   => 'cases',
			'works'   => 'works',
			'how'     => 'how',
			'cta'     => 'cta',
		)
	);
}

/**
 * Print the whole home page.
 *
 * The footer belongs to the deferred half of the page, so #rest is closed on
 * the moghadam_footer_end hook rather than here.
 */
function moghadam_render_home() {
	get_template_part( 'template-parts/home/hero' );

	add_action( 'moghadam_footer_end', 'moghadam_close_home_wrapper' );

	echo '<div id="rest">';
	echo '<main id="primary" ' . moghadam_get_main_attr( 'home' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( moghadam_home_sections() as $slug => $partial ) {
		if ( ! moghadam_home_enabled( $slug ) ) {
			continue;
		}

		get_template_part( 'template-parts/home/' . $partial );
	}

	/**
	 * Fires after the last home section, inside the main element.
	 *
	 * @since 1.3.0
	 */
	do_action( 'moghadam_home_after_sections' );

	echo '</main>';
}

/**
 * The class attribute for the home main element.
 *
 * @param string $layout Layout slug.
 * @return string
 */
function moghadam_get_main_attr( $layout ) {
	return 'class="' . esc_attr( str_replace( 'container', '', moghadam_get_main_class( $layout ) ) ) . '"';
}

/**
 * Close #rest once the footer has been printed.
 */
function moghadam_close_home_wrapper() {
	echo '</div><!-- #rest -->';
}
