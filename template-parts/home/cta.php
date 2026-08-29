<?php
/**
 * The closing call to action.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="cta" id="contact">
	<?php moghadam_grid_lines(); ?>
	<div class="container cta__row">
		<h2 class="cta__title" data-anim="lines"><?php moghadam_home_html( 'cta', 'title' ); ?></h2>
		<a class="btn btn--accent" href="<?php echo esc_url( moghadam_home( 'cta', 'button_url' ) ); ?>" data-anim="fade">
			<?php echo esc_html( moghadam_home( 'cta', 'button_label' ) ); ?>
		</a>
	</div>
</section>
