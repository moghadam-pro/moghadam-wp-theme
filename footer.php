<?php
/**
 * The template for displaying the footer.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;
?>
	<footer class="footer" id="colophon">
		<?php moghadam_grid_lines(); ?>
		<div class="container">
			<div class="footer__top">
				<?php moghadam_nav( 'footer', 'footer__nav', __( 'Footer Menu', 'moghadam' ), array( 'container_class' => 'footer__nav' ) ); ?>

				<?php if ( has_nav_menu( 'footer_more' ) ) : ?>
					<div class="footer__menu">
						<button class="footer__more" type="button" data-more aria-expanded="false" aria-controls="footerExtra">
							<?php echo esc_html( moghadam_home( 'footer', 'more_label' ) ); ?>
							<?php moghadam_icon( 'chevron-down', 'icon-chev' ); ?>
						</button>
						<?php
						moghadam_nav(
							'footer_more',
							'footer__extra',
							__( 'More Links', 'moghadam' ),
							array(
								'container_id' => 'footerExtra',
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>

			<div class="footer__rule" role="presentation" data-anim="draw-x"></div>

			<div class="footer__bottom">
				<p class="footer__legal" data-anim="fade">
					<span><?php echo esc_html( moghadam_home( 'footer', 'legal' ) ); ?></span>
					<span class="rule" aria-hidden="true"></span>
					<span><?php moghadam_home_html( 'footer', 'made_with' ); ?></span>
				</p>
				<?php moghadam_social_row( moghadam_home( 'footer', 'social_labels' ), array( 'anim' => 'stagger' ) ); ?>
			</div>
		</div>
	</footer><!-- #colophon -->

	<?php
	/**
	 * Fires after the footer, before the page wrapper closes.
	 *
	 * The home page uses this to close the deferred #rest wrapper.
	 *
	 * @since 1.3.0
	 */
	do_action( 'moghadam_footer_end' );
	?>
</div><!-- #page -->

<?php
moghadam_drawer();
wp_footer();
?>
</body>
</html>
