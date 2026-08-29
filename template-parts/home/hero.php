<?php
/**
 * Section 01 — hero.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

$terminal = trim( (string) moghadam_home( 'hero', 'terminal' ) );
?>
<section class="hero" id="top">
	<?php moghadam_grid_lines(); ?>

	<header class="header header--hero" id="heroHeader">
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

	<div class="hero__body">
		<div class="container">
			<div class="hero__content">
				<div class="hero__eyebrow">
					<?php moghadam_eyebrow( moghadam_home( 'hero', 'eyebrow_number' ), moghadam_home( 'hero', 'eyebrow_label' ) ); ?>
				</div>

				<p class="hero__greeting" data-anim="fade">
					<?php echo esc_html( moghadam_home( 'hero', 'greeting' ) ); ?>
					<em><?php echo esc_html( moghadam_home( 'hero', 'name' ) ); ?></em>
				</p>

				<h1 class="hero__title" data-anim="lines"><?php echo esc_html( moghadam_home( 'hero', 'headline' ) ); ?></h1>

				<p class="hero__lead" data-anim="fade"><?php moghadam_home_html( 'hero', 'lead' ); ?></p>

				<?php if ( '' !== $terminal ) : ?>
					<div class="terminal" data-anim="fade" aria-hidden="true">
						<?php echo do_shortcode( $terminal ); ?>
					</div>
				<?php endif; ?>

				<div class="hero__actions" data-anim="fade">
					<div class="hero__cta">
						<a class="btn btn--primary" href="<?php echo esc_url( moghadam_home( 'hero', 'cta_primary_url' ) ); ?>">
							<?php echo esc_html( moghadam_home( 'hero', 'cta_primary_label' ) ); ?>
						</a>
						<a class="btn btn--accent" href="<?php echo esc_url( moghadam_home( 'hero', 'cta_secondary_url' ) ); ?>">
							<?php echo esc_html( moghadam_home( 'hero', 'cta_secondary_label' ) ); ?>
						</a>
					</div>
					<?php moghadam_social_row( moghadam_home( 'hero', 'social_labels' ) ); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="hero__foot">
		<div class="container">
			<button class="scroll-hint" type="button" data-scroll-hint>
				<span class="scroll-hint__bar"><span class="scroll-hint__fill"></span></span>
				<span><?php echo esc_html( moghadam_home( 'hero', 'scroll_text' ) ); ?></span>
			</button>
		</div>
	</div>
</section>
