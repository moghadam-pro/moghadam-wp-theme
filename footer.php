<?php
/**
 * The template for displaying the footer.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;
?>
	<footer id="colophon" class="site-footer">
		<div class="container">
			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div class="footer-widgets">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'moghadam' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_id'        => 'footer-menu',
							'depth'          => 1,
							'container'      => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<div class="site-info">
				<?php
				printf(
					/* translators: 1: current year, 2: site name. */
					esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'moghadam' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
