<?php
/**
 * Section 03 — case studies.
 *
 * Rows come from posts in the configured category: the title becomes the row
 * name, the post tags become the bracketed line, and two custom fields fill
 * the right-hand column. The whole row links to the single post.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

$posts = moghadam_case_study_posts();

if ( empty( $posts ) && ! current_user_can( 'edit_posts' ) ) {
	return;
}
?>
<section class="section cases" id="cases">
	<?php moghadam_grid_lines(); ?>
	<div class="container">
		<div class="section-head">
			<?php
			moghadam_eyebrow( moghadam_home( 'cases', 'eyebrow_number' ), moghadam_home( 'cases', 'eyebrow_label' ) );
			moghadam_section_link( moghadam_home( 'cases', 'link_label' ), moghadam_home( 'cases', 'link_url' ) );
			?>
		</div>
		<div class="cases__intro">
			<h2 class="cases__title" data-anim="lines"><?php moghadam_home_html( 'cases', 'title' ); ?></h2>
			<p class="cases__note" data-anim="fade"><?php moghadam_home_html( 'cases', 'note' ); ?></p>
		</div>
	</div>

	<?php if ( empty( $posts ) ) : ?>
		<div class="container">
			<p class="cases__empty" data-anim="fade"><?php echo esc_html( moghadam_home( 'cases', 'empty_notice' ) ); ?></p>
		</div>
	<?php else : ?>
		<div class="cases__list">
			<?php
			$index = 0;
			foreach ( $posts as $case ) :
				$index++;
				$meta = moghadam_case_study_meta( $case->ID );
				$info = moghadam_case_study_info( $case->ID );
				?>
				<a class="case-row" href="<?php echo esc_url( get_permalink( $case ) ); ?>" data-anim="row">
					<span class="case-row__inner">
						<span class="case-row__left">
							<span class="case-row__meta"><?php echo esc_html( $meta ); ?></span>
							<span class="case-row__name"><?php echo esc_html( get_the_title( $case ) ); ?></span>
						</span>
						<span class="case-row__num">
							<i class="case-row__line" aria-hidden="true"></i>
							<span class="case-row__tag"><?php echo esc_html( str_pad( (string) $index, 2, '0', STR_PAD_LEFT ) ); ?></span>
						</span>
						<span class="case-row__right">
							<span class="case-row__info">
								<?php foreach ( $info as $line ) : ?>
									<span><?php echo esc_html( $line ); ?></span>
								<?php endforeach; ?>
							</span>
							<span class="case-row__arrow"><?php moghadam_icon( 'arrow-up-right' ); ?></span>
						</span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>
