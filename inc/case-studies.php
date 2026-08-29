<?php
/**
 * Case study rows.
 *
 * Section 03 is built from ordinary posts in one category:
 *
 *   name  post title
 *   meta  the post's tags, joined with | inside square brackets
 *   info  two custom fields on the post, edited in the Case Study meta box
 *   link  the post permalink
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

const MOGHADAM_CASE_KIND    = '_moghadam_case_kind';
const MOGHADAM_CASE_SUMMARY = '_moghadam_case_summary';

/**
 * The posts that fill the case study rows.
 *
 * @return WP_Post[]
 */
function moghadam_case_study_posts() {
	$slug  = moghadam_home( 'cases', 'category' );
	$count = max( 1, (int) moghadam_home( 'cases', 'count' ) );

	if ( '' === trim( (string) $slug ) ) {
		return array();
	}

	$query = new WP_Query(
		array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'category_name'          => $slug,
			'posts_per_page'         => $count,
			'orderby'                => 'date',
			'order'                  => 'DESC',
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_term_cache' => true,
		)
	);

	return $query->posts;
}

/**
 * The bracketed tag line for a post.
 *
 * Produces "[ Dashboard | Product | Build ]" from the post's tags.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function moghadam_case_study_meta( $post_id ) {
	$tags = get_the_tags( $post_id );

	if ( empty( $tags ) || is_wp_error( $tags ) ) {
		return '';
	}

	$names = wp_list_pluck( $tags, 'name' );

	return '[ ' . implode( ' | ', $names ) . ' ]';
}

/**
 * The two right-hand lines for a post.
 *
 * @param int $post_id Post ID.
 * @return string[] Zero, one or two lines.
 */
function moghadam_case_study_info( $post_id ) {
	$lines = array(
		get_post_meta( $post_id, MOGHADAM_CASE_KIND, true ),
		get_post_meta( $post_id, MOGHADAM_CASE_SUMMARY, true ),
	);

	return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
}

/**
 * Register the Case Study meta box on posts.
 */
function moghadam_case_study_meta_box() {
	add_meta_box(
		'moghadam-case-study',
		__( 'Case Study', 'moghadam' ),
		'moghadam_case_study_meta_box_render',
		'post',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'moghadam_case_study_meta_box' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Current post.
 */
function moghadam_case_study_meta_box_render( $post ) {
	wp_nonce_field( 'moghadam_case_study', 'moghadam_case_study_nonce' );

	$kind    = get_post_meta( $post->ID, MOGHADAM_CASE_KIND, true );
	$summary = get_post_meta( $post->ID, MOGHADAM_CASE_SUMMARY, true );
	?>
	<p>
		<label for="moghadam-case-kind"><strong><?php esc_html_e( 'Kind', 'moghadam' ); ?></strong></label><br>
		<input type="text" id="moghadam-case-kind" name="moghadam_case_kind" class="widefat"
			value="<?php echo esc_attr( $kind ); ?>" placeholder="<?php esc_attr_e( 'AI Agent', 'moghadam' ); ?>">
		<span class="description"><?php esc_html_e( 'First line on the right of the row.', 'moghadam' ); ?></span>
	</p>
	<p>
		<label for="moghadam-case-summary"><strong><?php esc_html_e( 'Summary', 'moghadam' ); ?></strong></label><br>
		<input type="text" id="moghadam-case-summary" name="moghadam_case_summary" class="widefat"
			value="<?php echo esc_attr( $summary ); ?>" placeholder="<?php esc_attr_e( 'Market Intelligence, build end to end', 'moghadam' ); ?>">
		<span class="description"><?php esc_html_e( 'Second line. Leave empty for a single-line row.', 'moghadam' ); ?></span>
	</p>
	<p class="description">
		<?php esc_html_e( 'The row title comes from the post title and the bracketed line from the post tags.', 'moghadam' ); ?>
	</p>
	<?php
}

/**
 * Persist the meta box.
 *
 * @param int $post_id Post ID.
 */
function moghadam_case_study_save( $post_id ) {
	if ( ! isset( $_POST['moghadam_case_study_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['moghadam_case_study_nonce'] ) ), 'moghadam_case_study' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		MOGHADAM_CASE_KIND    => 'moghadam_case_kind',
		MOGHADAM_CASE_SUMMARY => 'moghadam_case_summary',
	);

	foreach ( $fields as $meta_key => $input ) {
		$value = isset( $_POST[ $input ] ) ? sanitize_text_field( wp_unslash( $_POST[ $input ] ) ) : '';

		if ( '' === $value ) {
			delete_post_meta( $post_id, $meta_key );
		} else {
			update_post_meta( $post_id, $meta_key, $value );
		}
	}
}
add_action( 'save_post_post', 'moghadam_case_study_save' );
