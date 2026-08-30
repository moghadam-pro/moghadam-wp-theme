<?php
/**
 * The post sidebar.
 *
 * Blog posts and pages on the Default template get a sidebar built from a
 * small set of blocks rather than from widgets, so the same set can be the
 * site-wide default and still be overridden on any single post from the
 * editor.
 *
 * @package Moghadam
 * @since   1.4.0
 */

defined( 'ABSPATH' ) || exit;

const MOGHADAM_SIDEBAR_META   = '_moghadam_sidebar_blocks';
const MOGHADAM_SIDEBAR_CUSTOM = '_moghadam_sidebar_custom';
const MOGHADAM_SIDEBAR_MODE   = '_moghadam_sidebar_mode';

/**
 * The blocks a sidebar can be built from.
 *
 * @return array Block id => label, description and render callback.
 */
function moghadam_sidebar_blocks() {
	$blocks = array(
		'post-meta'  => array(
			'label'       => __( 'About this post', 'moghadam' ),
			'description' => __( 'Publish date, author with avatar, reading time, comment count, categories and tags.', 'moghadam' ),
			'render'      => 'moghadam_sidebar_block_post_meta',
			'singular'    => true,
		),
		'search'     => array(
			'label'       => __( 'Search', 'moghadam' ),
			'description' => __( 'The site search form.', 'moghadam' ),
			'render'      => 'moghadam_sidebar_block_search',
		),
		'categories' => array(
			'label'       => __( 'All categories', 'moghadam' ),
			'description' => __( 'Every category with its post count.', 'moghadam' ),
			'render'      => 'moghadam_sidebar_block_categories',
		),
		'custom'     => array(
			'label'       => __( 'Custom text', 'moghadam' ),
			'description' => __( 'Free text, written per post in the Sidebar box.', 'moghadam' ),
			'render'      => 'moghadam_sidebar_block_custom',
		),
	);

	/**
	 * Filters the blocks available to the post sidebar.
	 *
	 * @since 1.4.0
	 *
	 * @param array $blocks Block definitions.
	 */
	return apply_filters( 'moghadam_sidebar_blocks', $blocks );
}

/**
 * The site-wide default set, in order.
 *
 * @return array Block ids.
 */
function moghadam_sidebar_default_blocks() {
	$stored = get_theme_mod( 'moghadam_sidebar_blocks', null );

	if ( null === $stored ) {
		$stored = 'post-meta,search,categories';
	}

	return moghadam_sidebar_parse( $stored );
}

/**
 * Turn a stored comma separated list into known block ids, in order.
 *
 * @param string $value Raw value.
 * @return array
 */
function moghadam_sidebar_parse( $value ) {
	$known = array_keys( moghadam_sidebar_blocks() );
	$ids   = array_map( 'sanitize_key', array_map( 'trim', explode( ',', (string) $value ) ) );

	return array_values( array_intersect( $ids, $known ) );
}

/**
 * The blocks to render for one post.
 *
 * @param int $post_id Post ID.
 * @return array
 */
function moghadam_sidebar_blocks_for( $post_id ) {
	$mode = get_post_meta( $post_id, MOGHADAM_SIDEBAR_MODE, true );

	if ( 'custom' !== $mode ) {
		return moghadam_sidebar_default_blocks();
	}

	return moghadam_sidebar_parse( get_post_meta( $post_id, MOGHADAM_SIDEBAR_META, true ) );
}

/**
 * Whether the current request should render the post sidebar.
 *
 * @return bool
 */
function moghadam_has_post_sidebar() {
	if ( ! is_singular() || moghadam_is_canvas() ) {
		return false;
	}

	// Any template that opted into a wider layout has said no.
	if ( is_page_template() ) {
		return false;
	}

	$post_id = get_queried_object_id();

	return $post_id && ! empty( moghadam_sidebar_blocks_for( $post_id ) );
}

/**
 * Render the sidebar for the current post.
 */
function moghadam_post_sidebar() {
	if ( ! moghadam_has_post_sidebar() ) {
		return;
	}

	$post_id    = get_queried_object_id();
	$registry   = moghadam_sidebar_blocks();
	$selected   = moghadam_sidebar_blocks_for( $post_id );
	?>
	<aside id="secondary" class="post-sidebar" aria-label="<?php esc_attr_e( 'About this post', 'moghadam' ); ?>">
		<?php
		foreach ( $selected as $id ) {
			if ( empty( $registry[ $id ]['render'] ) || ! is_callable( $registry[ $id ]['render'] ) ) {
				continue;
			}

			if ( ! empty( $registry[ $id ]['singular'] ) && ! is_singular( 'post' ) ) {
				continue;
			}

			call_user_func( $registry[ $id ]['render'], $post_id );
		}
		?>
	</aside>
	<?php
}

/**
 * Minutes needed to read a post, at 200 words a minute.
 *
 * @param int $post_id Post ID.
 * @return int Always at least one.
 */
function moghadam_reading_time( $post_id ) {
	$words = str_word_count( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $post_id ) ) ) );

	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * The "about this post" block.
 *
 * @param int $post_id Post ID.
 */
function moghadam_sidebar_block_post_meta( $post_id ) {
	$author   = (int) get_post_field( 'post_author', $post_id );
	$comments = (int) get_comments_number( $post_id );
	$cats     = get_the_category_list( ', ', '', $post_id );
	$tags     = get_the_tag_list( '', ', ', '', $post_id );
	?>
	<section class="sidebar-block sidebar-block--meta">
		<h2 class="sidebar-block__title"><?php esc_html_e( 'About this post', 'moghadam' ); ?></h2>

		<div class="sidebar-author">
			<?php echo get_avatar( $author, 48, '', '', array( 'class' => 'sidebar-author__avatar' ) ); ?>
			<div>
				<span class="sidebar-author__name"><?php echo esc_html( get_the_author_meta( 'display_name', $author ) ); ?></span>
				<time class="sidebar-author__date" datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $post_id ) ); ?>">
					<?php echo esc_html( get_the_date( '', $post_id ) ); ?>
				</time>
			</div>
		</div>

		<dl class="sidebar-facts">
			<div>
				<dt><?php esc_html_e( 'Reading time', 'moghadam' ); ?></dt>
				<dd>
					<?php
					printf(
						/* translators: %d: number of minutes. */
						esc_html( _n( '%d minute', '%d minutes', moghadam_reading_time( $post_id ), 'moghadam' ) ),
						(int) moghadam_reading_time( $post_id )
					);
					?>
				</dd>
			</div>
			<div>
				<dt><?php esc_html_e( 'Comments', 'moghadam' ); ?></dt>
				<dd><?php echo esc_html( number_format_i18n( $comments ) ); ?></dd>
			</div>
			<?php if ( $cats ) : ?>
				<div>
					<dt><?php esc_html_e( 'Categories', 'moghadam' ); ?></dt>
					<dd><?php echo wp_kses_post( $cats ); ?></dd>
				</div>
			<?php endif; ?>
			<?php if ( $tags ) : ?>
				<div>
					<dt><?php esc_html_e( 'Tags', 'moghadam' ); ?></dt>
					<dd><?php echo wp_kses_post( $tags ); ?></dd>
				</div>
			<?php endif; ?>
		</dl>
	</section>
	<?php
}

/**
 * The search block.
 */
function moghadam_sidebar_block_search() {
	?>
	<section class="sidebar-block sidebar-block--search">
		<h2 class="sidebar-block__title"><?php esc_html_e( 'Search', 'moghadam' ); ?></h2>
		<?php get_search_form(); ?>
	</section>
	<?php
}

/**
 * The category list block.
 */
function moghadam_sidebar_block_categories() {
	$categories = get_categories( array( 'hide_empty' => true ) );

	if ( empty( $categories ) ) {
		return;
	}
	?>
	<section class="sidebar-block sidebar-block--categories">
		<h2 class="sidebar-block__title"><?php esc_html_e( 'Categories', 'moghadam' ); ?></h2>
		<ul class="sidebar-categories">
			<?php foreach ( $categories as $category ) : ?>
				<li>
					<a href="<?php echo esc_url( get_category_link( $category ) ); ?>">
						<?php echo esc_html( $category->name ); ?>
					</a>
					<span class="sidebar-categories__count"><?php echo esc_html( number_format_i18n( $category->count ) ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php
}

/**
 * The per-post free text block.
 *
 * @param int $post_id Post ID.
 */
function moghadam_sidebar_block_custom( $post_id ) {
	$content = get_post_meta( $post_id, MOGHADAM_SIDEBAR_CUSTOM, true );

	if ( '' === trim( (string) $content ) ) {
		return;
	}
	?>
	<section class="sidebar-block sidebar-block--custom">
		<?php echo wp_kses_post( wpautop( $content ) ); ?>
	</section>
	<?php
}
