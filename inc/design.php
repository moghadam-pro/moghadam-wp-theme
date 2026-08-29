<?php
/**
 * The Moghadam.pro design layer.
 *
 * Fonts, design tokens, the background grid, the reveal animations and the
 * scroll behaviour are theme-wide: the home page is only the busiest user of
 * them. Everything registered here loads on every request.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Google Fonts URL for the three families the design uses.
 *
 * @return string
 */
function moghadam_fonts_url() {
	$url = add_query_arg(
		array(
			'family'  => rawurlencode( 'Averia Sans Libre:wght@400;700' )
				. '&family=' . rawurlencode( 'Inter:wght@400;500;600' )
				. '&family=' . rawurlencode( 'JetBrains Mono:wght@400;500' ),
			'display' => 'swap',
		),
		'https://fonts.googleapis.com/css2'
	);

	/**
	 * Filters the webfont URL, so it can be swapped for a self-hosted set.
	 *
	 * @since 1.3.0
	 *
	 * @param string $url Stylesheet URL. Return an empty string to skip it.
	 */
	return apply_filters( 'moghadam_fonts_url', $url );
}

/**
 * The scroll and animation libraries.
 *
 * Served from a CDN by default. Point the filter at local copies to bundle
 * them; the design degrades to a plain scrolling page if they never arrive.
 *
 * @return array Handle => URL.
 */
function moghadam_motion_libraries() {
	return apply_filters(
		'moghadam_motion_libraries',
		array(
			'gsap'                => 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
			'gsap-scroll-trigger' => 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',
			'lenis'               => 'https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.min.js',
		)
	);
}

/**
 * Cache-busting version for a theme asset.
 *
 * The theme version alone is not enough: a hotfix shipped under the same
 * version would keep serving the cached file. The file's own timestamp is.
 *
 * @param string $relative Path relative to the theme root.
 * @return string
 */
function moghadam_asset_version( $relative ) {
	$file = MOGHADAM_DIR . '/' . ltrim( $relative, '/' );

	return file_exists( $file )
		? MOGHADAM_VERSION . '.' . filemtime( $file )
		: MOGHADAM_VERSION;
}

/**
 * Enqueue the design stylesheet and scripts.
 */
function moghadam_design_scripts() {
	$fonts = moghadam_fonts_url();

	if ( $fonts ) {
		wp_enqueue_style( 'moghadam-fonts', $fonts, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}

	wp_enqueue_style(
		'moghadam-design',
		MOGHADAM_URI . '/assets/css/design.css',
		array( 'moghadam-style' ),
		moghadam_asset_version( 'assets/css/design.css' )
	);

	$libraries = moghadam_motion_libraries();
	$deps      = array();

	foreach ( $libraries as $handle => $src ) {
		wp_enqueue_script( $handle, $src, $deps, null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		$deps[] = $handle;
	}

	wp_enqueue_script(
		'moghadam-design',
		MOGHADAM_URI . '/assets/js/design.js',
		$deps,
		moghadam_asset_version( 'assets/js/design.js' ),
		true
	);

	// Attached to the handle rather than hooked on wp_footer: footer scripts
	// print at priority 20, so a hooked fallback would run before gsap even
	// arrived and would strip the js/lock classes from every page.
	wp_add_inline_script(
		'moghadam-design',
		"if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined' || typeof Lenis === 'undefined') {"
			. " document.documentElement.classList.remove('js', 'lock'); }",
		'after'
	);

	wp_localize_script(
		'moghadam-design',
		'moghadamDesign',
		array(
			'marqueeSpeed' => max( 4, (int) moghadam_home( 'marquee', 'speed' ) ),
			'clock'        => array(
				'timeZone' => (string) moghadam_home( 'hero', 'clock_timezone' ),
				'suffix'   => (string) moghadam_home( 'hero', 'clock_suffix' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'moghadam_design_scripts', 20 );

/**
 * Add the js/lock classes before paint and restore the saved theme.
 *
 * Printed in the head on purpose: waiting for the stylesheet would flash the
 * light palette at anyone using the dark one.
 */
function moghadam_design_head_script() {
	// "lock" only on the home layout, where the page below the hero waits for
	// the visitor's first scroll.
	$classes = moghadam_is_home_layout() ? "'js', 'lock'" : "'js'";
	?>
	<script>
		(function () {
			try {
				var t = localStorage.getItem('mpro-theme');
				if (!t) t = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
				document.documentElement.setAttribute('data-theme', t);
			} catch (e) {}
			document.documentElement.classList.add(<?php echo $classes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>);
		})();
	</script>
	<?php
}
add_action( 'wp_head', 'moghadam_design_head_script', 1 );


/**
 * Whether the current request renders the home page layout.
 *
 * @return bool
 */
function moghadam_is_home_layout() {
	return is_front_page() || is_page_template( 'page-templates/template-home.php' );
}

/**
 * The nav menu location the main navigation should read.
 *
 * The home page links to anchors on itself; every other page links to real
 * pages. Falls back to the main menu when no home menu is assigned.
 *
 * @return string
 */
function moghadam_main_menu_location() {
	return ( moghadam_is_home_layout() && has_nav_menu( 'home' ) ) ? 'home' : 'primary';
}

/**
 * The background guide lines.
 *
 * Twelve identical hairlines at container width, tinted from the section they
 * sit in. Hidden below 768px by the stylesheet.
 */
function moghadam_grid_lines() {
	echo '<div class="grid-lines" aria-hidden="true"><div class="grid-lines__inner"></div></div>';
}

/**
 * The eyebrow row: number, rule, label.
 *
 * @param string $number Section number.
 * @param string $label  Section label.
 */
function moghadam_eyebrow( $number, $label ) {
	printf(
		'<p class="eyebrow" data-anim="fade"><span>%1$s</span><span class="eyebrow__rule"></span><span>%2$s</span></p>',
		esc_html( $number ),
		esc_html( $label )
	);
}

/**
 * The corner link on a section head.
 *
 * @param string $label Link label.
 * @param string $url   Link URL.
 */
function moghadam_section_link( $label, $url ) {
	if ( '' === trim( (string) $label ) ) {
		return;
	}

	printf(
		'<a class="section-link" href="%1$s" data-anim="fade">%2$s <i>/ &gt;</i></a>',
		esc_url( $url ),
		esc_html( $label )
	);
}

/**
 * Add design-related body classes.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function moghadam_design_body_classes( $classes ) {
	$classes[] = 'moghadam-design';

	if ( moghadam_is_home_layout() ) {
		$classes[] = 'is-home-layout';
	}

	return $classes;
}
add_filter( 'body_class', 'moghadam_design_body_classes' );
