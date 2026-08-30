<?php
/**
 * Single post furniture: related posts, the previous/next pair, and the
 * comment spam guard.
 *
 * @package Moghadam
 * @since   1.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Posts related to the given one.
 *
 * Every other published post is scored on what it shares with this one: a tag
 * in common is worth three, a category one. Tags carry more because they are
 * chosen per post and describe the subject, while a category is a bucket the
 * whole blog is sorted into. Ties break on recency.
 *
 * The scoring is done in SQL against the term relationships table, so it is
 * one query however many posts the site grows to. When it turns up fewer than
 * asked for, the newest posts from the same categories fill the rest.
 *
 * @param int $post_id Post to find relatives for.
 * @param int $limit   How many to return.
 * @return WP_Post[]
 */
function moghadam_related_posts( $post_id, $limit = 4 ) {
	global $wpdb;

	$post_id = (int) $post_id;
	$limit   = max( 1, (int) $limit );

	$tags       = wp_get_post_terms( $post_id, 'post_tag', array( 'fields' => 'tt_ids' ) );
	$categories = wp_get_post_terms( $post_id, 'category', array( 'fields' => 'tt_ids' ) );
	$tags       = is_wp_error( $tags ) ? array() : array_map( 'intval', $tags );
	$categories = is_wp_error( $categories ) ? array() : array_map( 'intval', $categories );

	$found = array();

	if ( $tags || $categories ) {
		$terms = array_merge( $tags, $categories );
		$slots = implode( ',', array_fill( 0, count( $terms ), '%d' ) );

		// One CASE per term id would be unreadable; instead the weight comes
		// from whether the term is in the tag list, passed as its own set.
		$tag_slots = $tags ? implode( ',', array_fill( 0, count( $tags ), '%d' ) ) : 'NULL';

		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared -- placeholders are built above and every value is cast to int.
		$sql = $wpdb->prepare(
			"SELECT p.ID,
			        SUM( CASE WHEN tr.term_taxonomy_id IN ( {$tag_slots} ) THEN 3 ELSE 1 END ) AS score
			   FROM {$wpdb->term_relationships} AS tr
			   JOIN {$wpdb->posts} AS p ON p.ID = tr.object_id
			  WHERE tr.term_taxonomy_id IN ( {$slots} )
			    AND p.ID <> %d
			    AND p.post_status = 'publish'
			    AND p.post_type = 'post'
			  GROUP BY p.ID
			  ORDER BY score DESC, p.post_date DESC
			  LIMIT %d",
			array_merge( $tags, $terms, array( $post_id, $limit ) )
		);

		$found = array_map( 'intval', (array) $wpdb->get_col( $sql ) );
		// phpcs:enable
	}

	if ( count( $found ) < $limit ) {
		$filler = get_posts(
			array(
				'post_type'        => 'post',
				'post_status'      => 'publish',
				'posts_per_page'   => $limit - count( $found ),
				'post__not_in'     => array_merge( array( $post_id ), $found ),
				'category__in'     => $categories ? wp_get_post_categories( $post_id ) : array(),
				'orderby'          => 'date',
				'order'            => 'DESC',
				'fields'           => 'ids',
				'suppress_filters' => false,
			)
		);

		$found = array_merge( $found, array_map( 'intval', $filler ) );
	}

	if ( count( $found ) < $limit ) {
		$filler = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => $limit - count( $found ),
				'post__not_in'   => array_merge( array( $post_id ), $found ),
				'orderby'        => 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
			)
		);

		$found = array_merge( $found, array_map( 'intval', $filler ) );
	}

	/**
	 * Filters the related post ids.
	 *
	 * @since 1.5.0
	 *
	 * @param int[] $found   Related post ids.
	 * @param int   $post_id The post they relate to.
	 */
	$found = apply_filters( 'moghadam_related_post_ids', array_slice( $found, 0, $limit ), $post_id );

	return array_filter( array_map( 'get_post', $found ) );
}

/*
 * -------------------------------------------------------------------------
 * Comment spam guard
 *
 * Two checks, both in the theme: a field bots fill in and people never see,
 * and the time between the form being served and the comment arriving.
 * Nothing is sent anywhere and nothing is stored about the commenter.
 * -------------------------------------------------------------------------
 */

/**
 * Name of the honeypot field.
 *
 * Deliberately plausible: bots fill anything that looks like a real field.
 */
const MOGHADAM_HONEYPOT = 'moghadam_website_url';

/**
 * The shortest believable time between loading a form and submitting it.
 *
 * @return int Seconds.
 */
function moghadam_comment_min_seconds() {
	return (int) apply_filters( 'moghadam_comment_min_seconds', 4 );
}

/**
 * Print the honeypot and the timestamp inside the comment form.
 */
function moghadam_comment_guard_fields() {
	$stamp = time();
	?>
	<p class="comment-form-website" aria-hidden="true">
		<label for="<?php echo esc_attr( MOGHADAM_HONEYPOT ); ?>"><?php esc_html_e( 'Leave this field empty', 'moghadam' ); ?></label>
		<input type="text" name="<?php echo esc_attr( MOGHADAM_HONEYPOT ); ?>"
			id="<?php echo esc_attr( MOGHADAM_HONEYPOT ); ?>" value="" tabindex="-1" autocomplete="off">
	</p>
	<input type="hidden" name="moghadam_comment_time" value="<?php echo esc_attr( wp_hash( $stamp ) . '|' . $stamp ); ?>">
	<?php
}
add_action( 'comment_form_after_fields', 'moghadam_comment_guard_fields' );
add_action( 'comment_form_logged_in_after', 'moghadam_comment_guard_fields' );

/**
 * Reject anything that trips either check.
 *
 * Runs before WordPress stores the comment.
 */
function moghadam_comment_guard() {
	if ( is_user_logged_in() && current_user_can( 'moderate_comments' ) ) {
		return;
	}

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- this is the public comment form; WordPress has no nonce here.
	$trap = isset( $_POST[ MOGHADAM_HONEYPOT ] ) ? trim( (string) wp_unslash( $_POST[ MOGHADAM_HONEYPOT ] ) ) : '';

	if ( '' !== $trap ) {
		wp_die(
			esc_html__( 'That comment looked automated. If you are a person, please try again with the hidden field left empty.', 'moghadam' ),
			esc_html__( 'Comment rejected', 'moghadam' ),
			array( 'response' => 403 )
		);
	}

	$token = isset( $_POST['moghadam_comment_time'] ) ? (string) wp_unslash( $_POST['moghadam_comment_time'] ) : '';
	// phpcs:enable

	$parts = explode( '|', $token );

	// A missing or tampered stamp means the form was not the one we served.
	if ( 2 !== count( $parts ) || ! hash_equals( wp_hash( $parts[1] ), $parts[0] ) ) {
		wp_die(
			esc_html__( 'That comment could not be verified. Please reload the page and try again.', 'moghadam' ),
			esc_html__( 'Comment rejected', 'moghadam' ),
			array( 'response' => 403 )
		);
	}

	if ( time() - (int) $parts[1] < moghadam_comment_min_seconds() ) {
		wp_die(
			esc_html__( 'That comment arrived faster than a person could write it. Please wait a moment and try again.', 'moghadam' ),
			esc_html__( 'Comment rejected', 'moghadam' ),
			array( 'response' => 403 )
		);
	}
}
add_action( 'pre_comment_on_post', 'moghadam_comment_guard' );
