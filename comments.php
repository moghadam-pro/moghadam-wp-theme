<?php
/**
 * The template for displaying comments.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$moghadam_comment_count = get_comments_number();
			printf(
				/* translators: 1: comment count, 2: post title. */
				esc_html( _n( '%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $moghadam_comment_count, 'moghadam' ) ),
				esc_html( number_format_i18n( $moghadam_comment_count ) ),
				esc_html( get_the_title() )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => esc_html__( 'Older comments', 'moghadam' ),
				'next_text' => esc_html__( 'Newer comments', 'moghadam' ),
			)
		);

		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'moghadam' ); ?></p>
			<?php
		endif;
	endif;

	comment_form();
	?>
</div><!-- #comments -->
