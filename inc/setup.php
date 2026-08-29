<?php
/**
 * Theme setup: supports, menus, sidebars, content width.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme support and register nav menus.
 */
function moghadam_setup() {
	load_theme_textdomain( 'moghadam', MOGHADAM_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 300,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'ffffff',
		)
	);

	register_nav_menus(
		array(
			'home'        => esc_html__( 'Home Menu (anchor links)', 'moghadam' ),
			'primary'     => esc_html__( 'Main Menu', 'moghadam' ),
			'footer'      => esc_html__( 'Footer Menu', 'moghadam' ),
			'footer_more' => esc_html__( 'Footer More Links', 'moghadam' ),
		)
	);
}
add_action( 'after_setup_theme', 'moghadam_setup' );

/**
 * Set the content width in pixels.
 */
function moghadam_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'moghadam_content_width', 800 );
}
add_action( 'after_setup_theme', 'moghadam_content_width', 0 );

/**
 * Register widget areas.
 */
function moghadam_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'moghadam' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Widgets added here appear in the main sidebar.', 'moghadam' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer', 'moghadam' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Widgets added here appear in the site footer.', 'moghadam' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'moghadam_widgets_init' );

/**
 * Add a pingback header on singular pages.
 */
function moghadam_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'moghadam_pingback_header' );

/**
 * Add useful classes to the body element.
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function moghadam_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'moghadam_body_classes' );
