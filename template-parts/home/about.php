<?php
/**
 * Section 02 — about.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

$image = moghadam_home( 'about', 'image' );

if ( '' === trim( (string) $image ) ) {
	$image = MOGHADAM_URI . '/assets/img/about-img@2x.png';
}
?>
<section class="section about" id="about">
	<?php moghadam_grid_lines(); ?>
	<div class="container">
		<div class="section-head">
			<?php
			moghadam_eyebrow( moghadam_home( 'about', 'eyebrow_number' ), moghadam_home( 'about', 'eyebrow_label' ) );
			moghadam_section_link( moghadam_home( 'about', 'link_label' ), moghadam_home( 'about', 'link_url' ) );
			?>
		</div>

		<div class="about__body">
			<div class="about__main">
				<p class="about__bio" data-anim="fade"><?php moghadam_home_html( 'about', 'bio' ); ?></p>
				<h2 class="about__quote" data-anim="lines"><?php moghadam_home_html( 'about', 'quote' ); ?></h2>
				<p class="about__desc" data-anim="fade"><?php moghadam_home_html( 'about', 'description' ); ?></p>
			</div>

			<aside class="about__aside">
				<figure class="portrait" data-anim="wipe">
					<img src="<?php echo esc_url( $image ); ?>"
						alt="<?php echo esc_attr( moghadam_home( 'about', 'image_alt' ) ); ?>"
						width="280" height="210" loading="lazy">
				</figure>
				<p class="about__ai" data-anim="fade"><?php moghadam_home_html( 'about', 'aside_text' ); ?></p>
				<a class="btn btn--ghost" href="<?php echo esc_url( moghadam_home( 'about', 'resume_url' ) ); ?>" data-anim="fade">
					<?php moghadam_icon( 'download', 'icon-download' ); ?>
					<?php echo esc_html( moghadam_home( 'about', 'resume_label' ) ); ?>
				</a>
			</aside>
		</div>
	</div>
</section>
