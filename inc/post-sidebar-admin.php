<?php
/**
 * Editing the post sidebar: the site-wide default and the per-post override.
 *
 * @package Moghadam
 * @since   1.4.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The Customizer section holding the site-wide default.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function moghadam_sidebar_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'moghadam_post_sidebar',
		array(
			'title'       => __( 'Post Sidebar', 'moghadam' ),
			'description' => __( 'The default sidebar for posts and for pages on the Default template. Any single post can override this from its own Sidebar box.', 'moghadam' ),
			'priority'    => 30,
		)
	);

	$wp_customize->add_setting(
		'moghadam_sidebar_blocks',
		array(
			'default'           => 'post-meta,search,categories',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'sanitize_callback' => 'moghadam_sidebar_sanitize_blocks',
		)
	);

	$wp_customize->add_control(
		'moghadam_sidebar_blocks',
		array(
			'label'       => __( 'Blocks', 'moghadam' ),
			'description' => __( 'Comma separated, in the order they should appear. Available: post-meta, search, categories, custom.', 'moghadam' ),
			'section'     => 'moghadam_post_sidebar',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'moghadam_sidebar_customize_register' );

/**
 * Keep only block ids the theme knows about.
 *
 * @param string $value Raw value.
 * @return string
 */
function moghadam_sidebar_sanitize_blocks( $value ) {
	return implode( ',', moghadam_sidebar_parse( $value ) );
}

/**
 * The Sidebar meta box on posts and pages.
 */
function moghadam_sidebar_meta_box() {
	foreach ( array( 'post', 'page' ) as $screen ) {
		add_meta_box(
			'moghadam-sidebar',
			__( 'Sidebar', 'moghadam' ),
			'moghadam_sidebar_meta_box_render',
			$screen,
			'side',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'moghadam_sidebar_meta_box' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Current post.
 */
function moghadam_sidebar_meta_box_render( $post ) {
	wp_nonce_field( 'moghadam_sidebar', 'moghadam_sidebar_nonce' );

	$mode     = get_post_meta( $post->ID, MOGHADAM_SIDEBAR_MODE, true );
	$mode     = 'custom' === $mode ? 'custom' : 'default';
	$selected = moghadam_sidebar_parse( get_post_meta( $post->ID, MOGHADAM_SIDEBAR_META, true ) );

	if ( empty( $selected ) ) {
		$selected = moghadam_sidebar_default_blocks();
	}

	$custom = get_post_meta( $post->ID, MOGHADAM_SIDEBAR_CUSTOM, true );
	?>
	<p>
		<label>
			<input type="radio" name="moghadam_sidebar_mode" value="default" <?php checked( 'default', $mode ); ?>>
			<?php esc_html_e( 'Use the site default', 'moghadam' ); ?>
		</label><br>
		<label>
			<input type="radio" name="moghadam_sidebar_mode" value="custom" <?php checked( 'custom', $mode ); ?>>
			<?php esc_html_e( 'Choose for this post', 'moghadam' ); ?>
		</label>
	</p>

	<div class="moghadam-sidebar-blocks">
		<?php foreach ( moghadam_sidebar_blocks() as $id => $block ) : ?>
			<p>
				<label>
					<input type="checkbox" name="moghadam_sidebar_blocks[]"
						value="<?php echo esc_attr( $id ); ?>"
						<?php checked( in_array( $id, $selected, true ) ); ?>>
					<strong><?php echo esc_html( $block['label'] ); ?></strong>
				</label><br>
				<span class="description"><?php echo esc_html( $block['description'] ); ?></span>
			</p>
		<?php endforeach; ?>
	</div>

	<p>
		<label for="moghadam-sidebar-custom"><strong><?php esc_html_e( 'Custom text', 'moghadam' ); ?></strong></label>
		<textarea id="moghadam-sidebar-custom" name="moghadam_sidebar_custom" rows="4" class="widefat"><?php echo esc_textarea( $custom ); ?></textarea>
		<span class="description"><?php esc_html_e( 'Shown when the Custom text block is ticked.', 'moghadam' ); ?></span>
	</p>

	<p class="description">
		<?php esc_html_e( 'The sidebar only renders on the Default template.', 'moghadam' ); ?>
	</p>
	<?php
}

/**
 * Persist the meta box.
 *
 * @param int $post_id Post ID.
 */
function moghadam_sidebar_save( $post_id ) {
	if ( ! isset( $_POST['moghadam_sidebar_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['moghadam_sidebar_nonce'] ) ), 'moghadam_sidebar' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$mode = isset( $_POST['moghadam_sidebar_mode'] ) ? sanitize_key( wp_unslash( $_POST['moghadam_sidebar_mode'] ) ) : 'default';
	$mode = 'custom' === $mode ? 'custom' : 'default';
	update_post_meta( $post_id, MOGHADAM_SIDEBAR_MODE, $mode );

	$blocks = isset( $_POST['moghadam_sidebar_blocks'] ) ? (array) wp_unslash( $_POST['moghadam_sidebar_blocks'] ) : array();
	$blocks = moghadam_sidebar_parse( implode( ',', array_map( 'sanitize_key', $blocks ) ) );
	update_post_meta( $post_id, MOGHADAM_SIDEBAR_META, implode( ',', $blocks ) );

	$custom = isset( $_POST['moghadam_sidebar_custom'] ) ? wp_kses_post( wp_unslash( $_POST['moghadam_sidebar_custom'] ) ) : '';

	if ( '' === trim( $custom ) ) {
		delete_post_meta( $post_id, MOGHADAM_SIDEBAR_CUSTOM );
	} else {
		update_post_meta( $post_id, MOGHADAM_SIDEBAR_CUSTOM, $custom );
	}
}
add_action( 'save_post_post', 'moghadam_sidebar_save' );
add_action( 'save_post_page', 'moghadam_sidebar_save' );

/**
 * New pages start on the Full Width template.
 *
 * There is no core setting for a default page template, so the auto-draft the
 * editor creates is stamped as it is inserted; the editor reads it from there.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function moghadam_default_page_template( $post_id, $post ) {
	if ( 'page' !== $post->post_type || 'auto-draft' !== $post->post_status ) {
		return;
	}

	if ( get_post_meta( $post_id, '_wp_page_template', true ) ) {
		return;
	}

	update_post_meta( $post_id, '_wp_page_template', 'page-templates/template-full-width.php' );
}
add_action( 'wp_insert_post', 'moghadam_default_page_template', 10, 2 );
