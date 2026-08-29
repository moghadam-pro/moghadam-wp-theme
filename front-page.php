<?php
/**
 * The front page.
 *
 * Content is edited in Appearance > Customize > Edit Home; sections 03 and 04
 * additionally read from posts and from the portfolio plugin.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

/**
 * Fires before the home page sections.
 *
 * @since 1.1.0
 */
do_action( 'moghadam_home_before_content' );

moghadam_render_home();

/**
 * Fires after the home page sections.
 *
 * @since 1.1.0
 */
do_action( 'moghadam_home_after_content' );

get_footer();
