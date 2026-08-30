<?php
/**
 * Layout helpers.
 *
 * Every template declares which layout it wants; the classes emitted here are
 * what the stylesheet keys off to set content width and sidebar behaviour.
 *
 * @package Moghadam
 * @since   1.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return the layouts this theme knows about.
 *
 * @return array Layout slug => description.
 */
function moghadam_get_layouts() {
	return array(
		'default'    => __( 'Content width, sidebar allowed.', 'moghadam' ),
		'full-width' => __( 'Container width, no sidebar.', 'moghadam' ),
		'home'       => __( 'Container width, no sidebar, home page hooks.', 'moghadam' ),
	);
}

/**
 * Build the class list for the main element.
 *
 * @param string $layout Layout slug. Defaults to 'default'.
 * @param array  $extra  Additional classes.
 * @return string Space separated class list.
 */
function moghadam_get_main_class( $layout = 'default', $extra = array() ) {
	$layouts = moghadam_get_layouts();

	if ( ! isset( $layouts[ $layout ] ) ) {
		$layout = 'default';
	}

	$classes = array_merge(
		array( 'site-main', 'container', 'site-main--' . $layout ),
		(array) $extra
	);

	/**
	 * Filters the classes applied to the main element.
	 *
	 * @since 1.1.0
	 *
	 * @param array  $classes Class names.
	 * @param string $layout  Layout slug.
	 */
	$classes = apply_filters( 'moghadam_main_class', $classes, $layout );

	return implode( ' ', array_unique( array_map( 'sanitize_html_class', $classes ) ) );
}

/**
 * Echo the class attribute for the main element.
 *
 * @param string $layout Layout slug.
 * @param array  $extra  Additional classes.
 */
function moghadam_main_class( $layout = 'default', $extra = array() ) {
	echo 'class="' . esc_attr( moghadam_get_main_class( $layout, $extra ) ) . '"';
}

/**
 * Whether the sidebar should render for the current request.
 *
 * Templates that opt out of the sidebar set the layout accordingly, so this
 * keeps get_sidebar() calls honest without each template repeating the check.
 *
 * @return bool
 */
function moghadam_has_sidebar() {
	/**
	 * Filters whether the sidebar renders.
	 *
	 * The post sidebar owns this now: it is built from blocks rather than
	 * widgets so it can be overridden per post.
	 *
	 * @since 1.1.0
	 *
	 * @param bool $has_sidebar Whether to render the sidebar.
	 */
	return (bool) apply_filters( 'moghadam_has_sidebar', moghadam_has_post_sidebar() );
}
