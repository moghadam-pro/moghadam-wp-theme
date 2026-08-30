<?php
/**
 * Theme settings screen.
 *
 * A top-level dashboard menu built on the Settings API. The page is driven by
 * a tab registry so further sections can be added without touching the
 * rendering code.
 *
 * @package Moghadam
 * @since   1.2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Menu slug of the settings screen.
 */
const MOGHADAM_SETTINGS_PAGE = 'moghadam-settings';

/**
 * The capability required to change theme settings.
 *
 * @return string
 */
function moghadam_settings_capability() {
	return apply_filters( 'moghadam_settings_capability', 'edit_theme_options' );
}

/**
 * Registered settings tabs.
 *
 * @return array Tab slug => array with 'label' and 'callback' keys.
 */
function moghadam_settings_tabs() {
	$tabs = array(
		'variables' => array(
			'label'    => __( 'Variables', 'moghadam' ),
			'callback' => 'moghadam_render_variables_tab',
		),
	);

	/**
	 * Filters the settings tabs.
	 *
	 * @since 1.2.0
	 *
	 * @param array $tabs Registered tabs.
	 */
	return apply_filters( 'moghadam_settings_tabs', $tabs );
}

/**
 * The tab being viewed.
 *
 * @return string
 */
function moghadam_current_tab() {
	$tabs = moghadam_settings_tabs();

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only navigation.
	$requested = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : '';

	if ( isset( $tabs[ $requested ] ) ) {
		return $requested;
	}

	return (string) array_key_first( $tabs );
}

/**
 * Add the dashboard menu.
 */
function moghadam_settings_menu() {
	add_menu_page(
		__( 'Moghadam Theme', 'moghadam' ),
		__( 'Moghadam', 'moghadam' ),
		moghadam_settings_capability(),
		MOGHADAM_SETTINGS_PAGE,
		'moghadam_render_settings_page',
		'dashicons-admin-customizer',
		59
	);
}
add_action( 'admin_menu', 'moghadam_settings_menu' );

/**
 * Register the option with the Settings API.
 */
function moghadam_register_settings() {
	register_setting(
		'moghadam_settings_group',
		MOGHADAM_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'moghadam_sanitize_settings',
			'default'           => array( 'variables' => moghadam_variables_defaults() ),
			'show_in_rest'      => false,
		)
	);
}
add_action( 'admin_init', 'moghadam_register_settings' );

/**
 * Handle the "restore defaults" action.
 */
function moghadam_handle_reset() {
	if ( ! current_user_can( moghadam_settings_capability() ) ) {
		wp_die( esc_html__( 'You are not allowed to change theme settings.', 'moghadam' ) );
	}

	check_admin_referer( 'moghadam_reset_settings' );

	delete_option( MOGHADAM_OPTION );

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'  => MOGHADAM_SETTINGS_PAGE,
				'reset' => 'done',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_moghadam_reset_settings', 'moghadam_handle_reset' );

/**
 * Load the settings screen assets.
 *
 * @param string $hook Current admin page hook.
 */
function moghadam_settings_assets( $hook ) {
	if ( 'toplevel_page_' . MOGHADAM_SETTINGS_PAGE !== $hook ) {
		return;
	}

	wp_enqueue_style( 'wp-color-picker' );

	wp_enqueue_style(
		'moghadam-admin',
		MOGHADAM_URI . '/assets/css/admin.css',
		array( 'wp-color-picker' ),
		MOGHADAM_VERSION
	);

	wp_enqueue_script(
		'moghadam-admin',
		MOGHADAM_URI . '/assets/js/admin.js',
		array( 'wp-color-picker', 'jquery' ),
		MOGHADAM_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'moghadam_settings_assets' );

/**
 * Render the settings screen.
 */
function moghadam_render_settings_page() {
	if ( ! current_user_can( moghadam_settings_capability() ) ) {
		return;
	}

	$tabs    = moghadam_settings_tabs();
	$current = moghadam_current_tab();

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only notice flag.
	$was_reset = isset( $_GET['reset'] ) && 'done' === $_GET['reset'];
	?>
	<div class="wrap moghadam-settings">
		<h1><?php esc_html_e( 'Moghadam Theme', 'moghadam' ); ?></h1>

		<?php if ( $was_reset ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><?php esc_html_e( 'Settings restored to their defaults.', 'moghadam' ); ?></p>
			</div>
		<?php endif; ?>

		<?php settings_errors(); ?>

		<nav class="nav-tab-wrapper">
			<?php foreach ( $tabs as $slug => $tab ) : ?>
				<a
					href="<?php echo esc_url( add_query_arg( array( 'page' => MOGHADAM_SETTINGS_PAGE, 'tab' => $slug ), admin_url( 'admin.php' ) ) ); ?>"
					class="nav-tab <?php echo $slug === $current ? 'nav-tab-active' : ''; ?>"
				>
					<?php echo esc_html( $tab['label'] ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<form action="options.php" method="post">
			<?php
			settings_fields( 'moghadam_settings_group' );

			if ( isset( $tabs[ $current ]['callback'] ) && is_callable( $tabs[ $current ]['callback'] ) ) {
				call_user_func( $tabs[ $current ]['callback'] );
			}

			submit_button( __( 'Save settings', 'moghadam' ) );
			?>
		</form>

		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="moghadam-reset-form">
			<?php wp_nonce_field( 'moghadam_reset_settings' ); ?>
			<input type="hidden" name="action" value="moghadam_reset_settings">
			<?php
			submit_button(
				__( 'Restore defaults', 'moghadam' ),
				'delete',
				'submit',
				false,
				array( 'onclick' => 'return confirm("' . esc_attr__( 'Discard every saved value and restore the theme defaults?', 'moghadam' ) . '");' )
			);
			?>
		</form>
	</div>
	<?php
}

/**
 * Render the Variables tab.
 */
function moghadam_render_variables_tab() {
	$variables = moghadam_get_settings()['variables'];

	foreach ( moghadam_variables_schema() as $group_key => $group ) {
		?>
		<h2 class="moghadam-group-title"><?php echo esc_html( $group['label'] ); ?></h2>
		<?php if ( ! empty( $group['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $group['description'] ); ?></p>
		<?php endif; ?>

		<table class="widefat striped moghadam-variables-table">
			<thead>
				<tr>
					<th scope="col" class="column-token"><?php esc_html_e( 'Token', 'moghadam' ); ?></th>
					<?php if ( ! empty( $group['per_mode'] ) ) : ?>
						<th scope="col" class="column-value"><?php esc_html_e( 'Light', 'moghadam' ); ?></th>
						<th scope="col" class="column-value"><?php esc_html_e( 'Dark', 'moghadam' ); ?></th>
					<?php else : ?>
						<th scope="col" class="column-value-wide"><?php esc_html_e( 'Value', 'moghadam' ); ?></th>
					<?php endif; ?>
					<th scope="col"><?php esc_html_e( 'Used for', 'moghadam' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $group['tokens'] as $token_key => $token ) : ?>
					<tr>
						<th scope="row">
							<?php if ( ! empty( $group['per_mode'] ) ) : ?>
								<strong><?php echo esc_html( $token['label'] ); ?></strong>
							<?php else : ?>
								<label for="<?php echo esc_attr( 'moghadam-' . $group_key . '-' . $token_key ); ?>">
									<?php echo esc_html( $token['label'] ); ?>
								</label>
							<?php endif; ?>
							<code><?php echo esc_html( $token['var'] ); ?></code>
						</th>

						<?php
						$control = isset( $token['control'] ) ? $token['control'] : $group['control'];

						if ( ! empty( $group['per_mode'] ) ) :
							foreach ( array( 'light', 'dark' ) as $mode ) :
								$name  = sprintf(
									'%s[variables][%s][%s][%s]',
									MOGHADAM_OPTION,
									$group_key,
									$mode,
									$token_key
								);
								$value = isset( $variables[ $group_key ][ $mode ][ $token_key ] )
									? $variables[ $group_key ][ $mode ][ $token_key ]
									: '';
								?>
								<td>
									<?php
									// Only colour tokens get the picker. Per-mode groups used to
									// be colours only, so this was unconditional and would have
									// mangled a size or a multiplier.
									$is_color = 'color' === $control;
									?>
									<input
										type="text"
										id="<?php echo esc_attr( 'moghadam-' . $group_key . '-' . $mode . '-' . $token_key ); ?>"
										name="<?php echo esc_attr( $name ); ?>"
										value="<?php echo esc_attr( $value ); ?>"
										class="<?php echo $is_color ? 'moghadam-color-field' : 'regular-text code'; ?>"
										<?php if ( $is_color ) : ?>
											data-default-color="<?php echo esc_attr( $token[ $mode ] ); ?>"
										<?php else : ?>
											placeholder="<?php echo esc_attr( $token[ $mode ] ); ?>"
										<?php endif; ?>
										aria-label="<?php echo esc_attr( sprintf( '%1$s (%2$s)', $token['label'], 'light' === $mode ? __( 'Light', 'moghadam' ) : __( 'Dark', 'moghadam' ) ) ); ?>"
									>
								</td>
								<?php
							endforeach;
						else :
							$name  = sprintf(
								'%s[variables][%s][%s]',
								MOGHADAM_OPTION,
								$group_key,
								$token_key
							);
							$value = isset( $variables[ $group_key ][ $token_key ] )
								? $variables[ $group_key ][ $token_key ]
								: '';
							?>
							<td>
								<input
									type="text"
									id="<?php echo esc_attr( 'moghadam-' . $group_key . '-' . $token_key ); ?>"
									name="<?php echo esc_attr( $name ); ?>"
									value="<?php echo esc_attr( $value ); ?>"
									class="regular-text <?php echo 'size' === $control ? 'moghadam-size-field' : 'moghadam-text-field'; ?>"
									placeholder="<?php echo esc_attr( $token['default'] ); ?>"
								>
							</td>
							<?php
						endif;
						?>

						<td class="moghadam-usage"><?php echo esc_html( $token['usage'] ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}
	?>
	<p class="description moghadam-footnote">
		<?php
		printf(
			/* translators: %s: name of the Theme page template. */
			esc_html__( 'Assign the %s page template to a page to see every token rendered with its live value.', 'moghadam' ),
			'<strong>' . esc_html__( 'Theme', 'moghadam' ) . '</strong>'
		);
		?>
	</p>
	<?php
}
