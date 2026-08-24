<?php
/**
 * Custom template tags for this theme.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'moghadam_posted_on' ) ) {
	/**
	 * Print HTML with the publish date of the current post.
	 */
	function moghadam_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		printf(
			'<span class="posted-on"><a href="%1$s" rel="bookmark">%2$s</a></span>',
			esc_url( get_permalink() ),
			$time_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
}

if ( ! function_exists( 'moghadam_posted_by' ) ) {
	/**
	 * Print HTML with the author of the current post.
	 */
	function moghadam_posted_by() {
		printf(
			'<span class="byline"><span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}
}

if ( ! function_exists( 'moghadam_entry_footer' ) ) {
	/**
	 * Print categories, tags and the comments link.
	 */
	function moghadam_entry_footer() {
		if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list( esc_html__( ', ', 'moghadam' ) );
			if ( $categories_list ) {
				printf(
					'<span class="cat-links">%s</span>',
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}

			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'moghadam' ) );
			if ( $tags_list ) {
				printf(
					'<span class="tags-links">%s</span>',
					$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				esc_html__( 'Leave a comment', 'moghadam' ),
				esc_html__( '1 Comment', 'moghadam' ),
				esc_html__( '% Comments', 'moghadam' )
			);
			echo '</span>';
		}

		edit_post_link(
			esc_html__( 'Edit', 'moghadam' ),
			'<span class="edit-link">',
			'</span>'
		);
	}
}

if ( ! function_exists( 'moghadam_post_thumbnail' ) ) {
	/**
	 * Display an optional post thumbnail.
	 */
	function moghadam_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) {
			echo '<figure class="post-thumbnail">';
			the_post_thumbnail( 'large' );
			echo '</figure>';
			return;
		}
		?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
		</a>
		<?php
	}
}
