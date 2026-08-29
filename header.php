<?php
/**
 * The header for our theme.
 *
 * Two headers are printed: the bar that sits inside the hero and the floating
 * pill that takes over on scroll. Both read the same nav menu location.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'moghadam' ); ?></a>

<header class="header header--sticky" id="stickyHeader">
	<div class="header__pill">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"
			aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php moghadam_icon( 'logomark', 'icon-logomark' ); ?>
		</a>
		<span class="brand-sep" aria-hidden="true"></span>
		<?php moghadam_nav( moghadam_main_menu_location(), 'nav', __( 'Sticky Menu', 'moghadam' ) ); ?>
		<?php moghadam_theme_toggle(); ?>
		<button class="nav-toggle" type="button" data-drawer-open aria-label="<?php esc_attr_e( 'Open menu', 'moghadam' ); ?>">
			<?php moghadam_icon( 'menu' ); ?>
		</button>
	</div>
</header>

<div id="page" class="site">
<?php if ( ! moghadam_is_home_layout() ) : ?>
	<header class="header header--inner" id="heroHeader">
		<?php moghadam_grid_lines(); ?>
		<div class="container header__row">
			<div class="header__left">
				<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"
					aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php moghadam_icon( 'logotype', 'icon-logotype' ); ?>
				</a>
				<span class="brand-sep" aria-hidden="true"></span>
				<?php moghadam_nav( moghadam_main_menu_location(), 'nav', __( 'Primary Menu', 'moghadam' ) ); ?>
			</div>
			<div class="header__right">
				<?php moghadam_header_status(); ?>
				<?php moghadam_theme_toggle(); ?>
				<button class="nav-toggle" type="button" data-drawer-open aria-label="<?php esc_attr_e( 'Open menu', 'moghadam' ); ?>">
					<?php moghadam_icon( 'menu' ); ?>
				</button>
			</div>
		</div>
	</header>
<?php endif; ?>
