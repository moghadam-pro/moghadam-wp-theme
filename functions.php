<?php
/**
 * Moghadam theme functions and definitions.
 *
 * @package Moghadam
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'MOGHADAM_VERSION' ) ) {
	define( 'MOGHADAM_VERSION', '1.3.0' );
}

if ( ! defined( 'MOGHADAM_DIR' ) ) {
	define( 'MOGHADAM_DIR', get_template_directory() );
}

if ( ! defined( 'MOGHADAM_URI' ) ) {
	define( 'MOGHADAM_URI', get_template_directory_uri() );
}

require_once MOGHADAM_DIR . '/inc/variables.php';
require_once MOGHADAM_DIR . '/inc/setup.php';
require_once MOGHADAM_DIR . '/inc/enqueue.php';
require_once MOGHADAM_DIR . '/inc/layout.php';
require_once MOGHADAM_DIR . '/inc/canvas.php';
require_once MOGHADAM_DIR . '/inc/style-guide.php';
require_once MOGHADAM_DIR . '/inc/template-tags.php';
require_once MOGHADAM_DIR . '/inc/icons.php';
require_once MOGHADAM_DIR . '/inc/home/home-settings.php';
require_once MOGHADAM_DIR . '/inc/home/home-render.php';
require_once MOGHADAM_DIR . '/inc/design.php';
require_once MOGHADAM_DIR . '/inc/design-tags.php';
require_once MOGHADAM_DIR . '/inc/social.php';
require_once MOGHADAM_DIR . '/inc/case-studies.php';
require_once MOGHADAM_DIR . '/inc/portfolio.php';
require_once MOGHADAM_DIR . '/inc/customizer.php';
require_once MOGHADAM_DIR . '/inc/home/customizer-home.php';

if ( is_admin() ) {
	require_once MOGHADAM_DIR . '/inc/settings.php';
}
