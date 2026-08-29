<?php
/**
 * Section 05 — how I work.
 *
 * On desktop the section fills the viewport and pins while the left column
 * steps through the four blocks; below 1024px the pin is dropped and all four
 * are stacked.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

$steps = array();

for ( $i = 1; $i <= 4; $i++ ) {
	$title = trim( (string) moghadam_home( 'how', 'step_' . $i . '_title' ) );

	if ( '' === $title ) {
		continue;
	}

	$steps[] = array(
		'title' => $title,
		'text'  => moghadam_home( 'how', 'step_' . $i . '_text' ),
	);
}

$skills = moghadam_home_lines( 'how', 'skills' );

if ( empty( $steps ) ) {
	return;
}
?>
<section class="section how" id="how" data-steps-count="<?php echo esc_attr( count( $steps ) ); ?>">
	<?php moghadam_grid_lines(); ?>
	<div class="container">
		<div class="section-head">
			<?php
			moghadam_eyebrow( moghadam_home( 'how', 'eyebrow_number' ), moghadam_home( 'how', 'eyebrow_label' ) );
			moghadam_section_link( moghadam_home( 'how', 'link_label' ), moghadam_home( 'how', 'link_url' ) );
			?>
		</div>

		<div class="how__body">
			<div class="how__stage">
				<div class="how__ghost" aria-hidden="true" data-ghost><span>01</span></div>
				<div class="how__steps" data-steps>
					<?php foreach ( $steps as $i => $step ) : ?>
						<article class="how-step<?php echo 0 === $i ? ' is-active' : ''; ?>">
							<h3 class="how-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
							<p class="how-step__text"><?php echo wp_kses( $step['text'], moghadam_home_allowed_html() ); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( ! empty( $skills ) ) : ?>
				<div class="skills">
					<p class="skills__head">
						<?php echo esc_html( moghadam_home( 'how', 'skills_label' ) ); ?>
						<i class="skills__rule" data-anim="draw-x"></i>
					</p>
					<ul class="skills__list" data-anim="skills">
						<?php foreach ( $skills as $skill ) : ?>
							<li><?php echo esc_html( $skill ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
