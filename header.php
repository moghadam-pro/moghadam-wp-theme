<?php
/**
 * The header for our theme.
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
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'moghadam' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-header-inner container">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				}

				if ( is_front_page() && is_home() ) :
					?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php
				else :
					?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php
				endif;

				$moghadam_description = get_bloginfo( 'description', 'display' );
				if ( $moghadam_description || is_customize_preview() ) :
					?>
					<p class="site-description"><?php echo $moghadam_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<?php
				endif;
				?>
			</div><!-- .site-branding -->

			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'moghadam' ); ?>">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<span class="menu-toggle-bar" aria-hidden="true"></span>
						<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'moghadam' ); ?></span>
					</button>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
						)
					);
					?>
				</nav><!-- #site-navigation -->
			<?php endif; ?>
		</div><!-- .site-header-inner -->
	</header><!-- #masthead -->
