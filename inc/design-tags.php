<?php
/**
 * Template tags for the design layer: header chrome, menus, section furniture.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print a nav menu for one of the theme's locations.
 *
 * Renders nothing when the location has no menu assigned, so the markup never
 * shows an empty shell.
 *
 * @param string $location Registered nav menu location.
 * @param string $class    Class for the nav element.
 * @param string $aria     Accessible label.
 * @param array  $extra    Optional extra wp_nav_menu arguments.
 */
function moghadam_nav( $location, $class = 'nav', $aria = '', $extra = array() ) {
	if ( ! has_nav_menu( $location ) ) {
		return;
	}

	$args = wp_parse_args(
		$extra,
		array(
			'theme_location'       => $location,
			'container'            => 'nav',
			'container_class'      => $class,
			'container_aria_label' => $aria ? $aria : $location,
			'depth'                => 1,
			'items_wrap'           => '%3$s',
			'fallback_cb'          => false,
		)
	);

	wp_nav_menu( $args );
}

/**
 * The theme switch button.
 */
function moghadam_theme_toggle() {
	?>
	<button class="theme-toggle" type="button" data-theme-toggle
		aria-label="<?php esc_attr_e( 'Toggle colour theme', 'moghadam' ); ?>">
		<?php
		moghadam_icon( 'sun', 'icon-sun' );
		moghadam_icon( 'moon', 'icon-moon' );
		?>
	</button>
	<?php
}

/**
 * The location dot and the clock in the header.
 */
function moghadam_header_status() {
	if ( moghadam_home( 'hero', 'show_status' ) ) {
		printf(
			'<p class="status"><span class="status__dot" aria-hidden="true"></span> %s</p>',
			esc_html( moghadam_home( 'hero', 'status_text' ) )
		);
	}

	if ( moghadam_home( 'hero', 'show_clock' ) ) {
		printf(
			'<p class="clock" data-clock>--:-- %s</p>',
			esc_html( moghadam_home( 'hero', 'clock_suffix' ) )
		);
	}
}

/**
 * The full-screen mobile menu.
 */
function moghadam_drawer() {
	?>
	<div class="drawer" id="drawer" hidden>
		<div class="drawer__top">
			<span class="brand"><?php moghadam_icon( 'logotype', 'icon-logotype' ); ?></span>
			<button class="nav-toggle" type="button" data-drawer-close style="display:flex"
				aria-label="<?php esc_attr_e( 'Close menu', 'moghadam' ); ?>">
				<?php moghadam_icon( 'close' ); ?>
			</button>
		</div>
		<?php moghadam_nav( moghadam_main_menu_location(), 'drawer__nav', __( 'Mobile Menu', 'moghadam' ) ); ?>
		<div class="drawer__foot">
			<?php moghadam_header_status(); ?>
		</div>
	</div>
	<?php
}
