<?php
/**
 * Home page content schema.
 *
 * One array describes every editable string on the home page. The Customizer
 * panel is generated from it and the templates read through moghadam_home(),
 * so a new field only has to be added in one place.
 *
 * @package Moghadam
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Prefix for every home theme mod.
 */
const MOGHADAM_HOME_PREFIX = 'moghadam_home_';

/**
 * The home page content schema.
 *
 * Field types: text, textarea, html, lines, url, image, number, checkbox.
 *
 * @return array
 */
function moghadam_home_schema() {
	static $schema = null;

	if ( null !== $schema ) {
		return $schema;
	}

	$schema = array(
		'social'  => array(
			'title'       => __( 'Social Links', 'moghadam' ),
			'description' => __( 'Defined once and reused by the hero and the footer.', 'moghadam' ),
			'priority'    => 5,
			'fields'      => array(
				'links' => array(
					'label'       => __( 'Social links', 'moghadam' ),
					'type'        => 'social',
					'description' => __( 'One entry per block, blocks separated by a line of three dashes. Line 1 is the label, line 2 is the URL, everything after is the SVG markup.', 'moghadam' ),
					'default'     => '',
				),
			),
		),
		'hero'    => array(
			'title'    => __( 'Hero', 'moghadam' ),
			'priority' => 10,
			'fields'   => array(
				'eyebrow_number'      => array(
					'label'   => __( 'Section number', 'moghadam' ),
					'type'    => 'text',
					'default' => '01',
				),
				'eyebrow_label'       => array(
					'label'   => __( 'Section label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Hero Section',
				),
				'greeting'            => array(
					'label'   => __( 'Greeting', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Hey, I&rsquo;m',
				),
				'name'                => array(
					'label'       => __( 'Name', 'moghadam' ),
					'type'        => 'text',
					'description' => __( 'Rendered in the accent colour next to the greeting.', 'moghadam' ),
					'default'     => 'Sayid Moghadam',
				),
				'headline'            => array(
					'label'   => __( 'Headline', 'moghadam' ),
					'type'    => 'textarea',
					'default' => 'I turn complex products into clear, scalable experiences',
				),
				'lead'                => array(
					'label'   => __( 'Lead paragraph', 'moghadam' ),
					'type'    => 'html',
					'default' => 'From uncertainty to a product ready to ship &mdash; combining strategy, research, UX, and systems thinking. Senior Product Designer specializing in complex SaaS products, design systems, and developer-ready interfaces.',
				),
				'terminal'            => array(
					'label'       => __( 'Terminal block', 'moghadam' ),
					'type'        => 'html',
					'description' => __( 'Paste the terminal plugin shortcode here. Hidden below 768px. Leave empty to drop the block.', 'moghadam' ),
					'default'     => '',
				),
				'cta_primary_label'   => array(
					'label'   => __( 'Primary button label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Let&rsquo;s Talk 🔥',
				),
				'cta_primary_url'     => array(
					'label'   => __( 'Primary button link', 'moghadam' ),
					'type'    => 'url',
					'default' => '#contact',
				),
				'cta_secondary_label' => array(
					'label'   => __( 'Secondary button label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Read Case Studies',
				),
				'cta_secondary_url'   => array(
					'label'   => __( 'Secondary button link', 'moghadam' ),
					'type'    => 'url',
					'default' => '#cases',
				),
				'social_labels'       => array(
					'label'       => __( 'Social links to show', 'moghadam' ),
					'type'        => 'text',
					'description' => __( 'Comma separated labels from the Social Links section. Leave empty to show them all.', 'moghadam' ),
					'default'     => '',
				),
				'show_status'         => array(
					'label'   => __( 'Show the location status', 'moghadam' ),
					'type'    => 'checkbox',
					'default' => true,
				),
				'status_text'         => array(
					'label'   => __( 'Location status', 'moghadam' ),
					'type'    => 'text',
					'default' => 'based in Turkey',
				),
				'show_clock'          => array(
					'label'   => __( 'Show the clock', 'moghadam' ),
					'type'    => 'checkbox',
					'default' => true,
				),
				'clock_timezone'      => array(
					'label'       => __( 'Clock time zone', 'moghadam' ),
					'type'        => 'text',
					'description' => __( 'An IANA identifier, for example Europe/Istanbul.', 'moghadam' ),
					'default'     => 'Europe/Istanbul',
				),
				'clock_suffix'        => array(
					'label'   => __( 'Clock suffix', 'moghadam' ),
					'type'    => 'text',
					'default' => 'IST',
				),
				'scroll_text'         => array(
					'label'   => __( 'Scroll hint', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Scroll Down',
				),
			),
		),
		'marquee' => array(
			'title'    => __( 'Marquee Bars', 'moghadam' ),
			'priority' => 20,
			'fields'   => array(
				'enabled'       => array(
					'label'   => __( 'Show the marquee', 'moghadam' ),
					'type'    => 'checkbox',
					'default' => true,
				),
				'row_one'       => array(
					'label'       => __( 'Dark row items', 'moghadam' ),
					'type'        => 'lines',
					'description' => __( 'One item per line.', 'moghadam' ),
					'default'     => "15+ Years of Experience\nDesign Systems at Scale\nEmpowering teams and products through complex AI-driven workflows\nFocus on AI SaaS and Complex Internal Apps",
				),
				'row_one_sep'   => array(
					'label'   => __( 'Dark row separator', 'moghadam' ),
					'type'    => 'text',
					'default' => '/',
				),
				'row_two'       => array(
					'label'   => __( 'Yellow row items', 'moghadam' ),
					'type'    => 'lines',
					'default' => "Focus on AI SaaS and Complex Internal Apps\nDesign Systems at Scale\n15+ Years of Experience\nEmpowering teams and products through complex AI-driven workflows",
				),
				'row_two_sep'   => array(
					'label'   => __( 'Yellow row separator', 'moghadam' ),
					'type'    => 'text',
					'default' => '\\',
				),
				'speed'         => array(
					'label'       => __( 'Speed', 'moghadam' ),
					'type'        => 'number',
					'description' => __( 'Pixels per second. Lower is slower.', 'moghadam' ),
					'default'     => 20,
				),
			),
		),
		'about'   => array(
			'title'    => __( 'About', 'moghadam' ),
			'priority' => 30,
			'fields'   => array(
				'enabled'       => array(
					'label'   => __( 'Show this section', 'moghadam' ),
					'type'    => 'checkbox',
					'default' => true,
				),
				'eyebrow_number' => array(
					'label'   => __( 'Section number', 'moghadam' ),
					'type'    => 'text',
					'default' => '02',
				),
				'eyebrow_label'  => array(
					'label'   => __( 'Section label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'About me',
				),
				'link_label'     => array(
					'label'   => __( 'Corner link label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Read Full Story',
				),
				'link_url'       => array(
					'label'   => __( 'Corner link URL', 'moghadam' ),
					'type'    => 'url',
					'default' => '#',
				),
				'bio'            => array(
					'label'   => __( 'Bio paragraph', 'moghadam' ),
					'type'    => 'html',
					'default' => 'When I&rsquo;m deeply involved in solving a problem or shaping a new product, I completely lose track of time. I believe having a career you genuinely enjoy is one of life&rsquo;s greatest privileges. Throughout my career, I&rsquo;ve helped design products used by millions of people across finance, business platforms, social apps, dashboards, robotics, infrastructure, and e-commerce.',
				),
				'quote'          => array(
					'label'       => __( 'Highlighted quote', 'moghadam' ),
					'type'        => 'html',
					'description' => __( 'Wrap a word in &lt;em&gt; to paint it in the accent colour.', 'moghadam' ),
					'default'     => 'I see myself as a <em>builder</em>&mdash;someone who turns ideas into real products.',
				),
				'description'    => array(
					'label'       => __( 'Description', 'moghadam' ),
					'type'        => 'html',
					'description' => __( 'Links get the accent underline automatically when given class="link-accent".', 'moghadam' ),
					'default'     => 'By <a class="link-accent" href="#">combining design with coding</a>, I turn complex ideas into simple, useful, and scalable digital products&mdash;balancing a developer&rsquo;s mindset, a user&rsquo;s perspective, and real business needs.',
				),
				'image'          => array(
					'label'       => __( 'Portrait', 'moghadam' ),
					'type'        => 'image',
					'description' => __( 'Export at 2x (560 x 420); it is always displayed at 280 x 210.', 'moghadam' ),
					'default'     => '',
				),
				'image_alt'      => array(
					'label'   => __( 'Portrait alt text', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Sayid Moghadam',
				),
				'aside_text'     => array(
					'label'   => __( 'Aside paragraph', 'moghadam' ),
					'type'    => 'html',
					'default' => 'I see AI less as a feature and more as a creative partner&mdash;something I think with, build with, and learn from. The best results come from a real conversation: curious, unexpected, and genuinely useful.',
				),
				'resume_label'   => array(
					'label'   => __( 'Resume button label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Download resume',
				),
				'resume_url'     => array(
					'label'   => __( 'Resume file URL', 'moghadam' ),
					'type'    => 'url',
					'default' => '#',
				),
			),
		),
		'cases'   => array(
			'title'       => __( 'Case Studies', 'moghadam' ),
			'description' => __( 'The rows are pulled from posts in the chosen category, newest first.', 'moghadam' ),
			'priority'    => 40,
			'fields'      => array(
				'enabled'        => array(
					'label'   => __( 'Show this section', 'moghadam' ),
					'type'    => 'checkbox',
					'default' => true,
				),
				'eyebrow_number' => array(
					'label'   => __( 'Section number', 'moghadam' ),
					'type'    => 'text',
					'default' => '03',
				),
				'eyebrow_label'  => array(
					'label'   => __( 'Section label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Selected Case Studies',
				),
				'link_label'     => array(
					'label'   => __( 'Corner link label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Read All Articles',
				),
				'link_url'       => array(
					'label'   => __( 'Corner link URL', 'moghadam' ),
					'type'    => 'url',
					'default' => '#',
				),
				'title'          => array(
					'label'       => __( 'Title', 'moghadam' ),
					'type'        => 'html',
					'description' => __( 'Use &lt;br&gt; to control the line break.', 'moghadam' ),
					'default'     => 'Every problem tells a story,<br>These are some of mine.',
				),
				'note'           => array(
					'label'   => __( 'Note', 'moghadam' ),
					'type'    => 'html',
					'default' => 'Selected product and system work &mdash; from complex problems to shipped solutions.',
				),
				'category'       => array(
					'label'       => __( 'Category slug', 'moghadam' ),
					'type'        => 'text',
					'description' => __( 'Posts in this category fill the rows.', 'moghadam' ),
					'default'     => 'case-study',
				),
				'count'          => array(
					'label'   => __( 'Number of rows', 'moghadam' ),
					'type'    => 'number',
					'default' => 4,
				),
				'empty_notice'   => array(
					'label'       => __( 'Empty state', 'moghadam' ),
					'type'        => 'text',
					'description' => __( 'Shown to editors when the category has no posts yet.', 'moghadam' ),
					'default'     => 'No case studies published yet.',
				),
			),
		),
		'works'   => array(
			'title'       => __( 'Visual Work', 'moghadam' ),
			'description' => __( 'Reads from the portfolio plugin. The whole section disappears when no plugin is active.', 'moghadam' ),
			'priority'    => 50,
			'fields'      => array(
				'enabled'        => array(
					'label'   => __( 'Show this section', 'moghadam' ),
					'type'    => 'checkbox',
					'default' => true,
				),
				'eyebrow_number' => array(
					'label'   => __( 'Section number', 'moghadam' ),
					'type'    => 'text',
					'default' => '04',
				),
				'eyebrow_label'  => array(
					'label'   => __( 'Section label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Selected Visual Work',
				),
				'link_label'     => array(
					'label'   => __( 'Corner link label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'In Focus',
				),
				'link_url'       => array(
					'label'   => __( 'Corner link URL', 'moghadam' ),
					'type'    => 'url',
					'default' => '#',
				),
				'all_label'      => array(
					'label'   => __( 'Label for the "all" filter', 'moghadam' ),
					'type'    => 'text',
					'default' => 'All',
				),
				'count'          => array(
					'label'       => __( 'Items per filter', 'moghadam' ),
					'type'        => 'number',
					'description' => __( 'Every filter shows this many of its newest items, or fewer if it has fewer.', 'moghadam' ),
					'default'     => 7,
				),
			),
		),
		'how'     => array(
			'title'    => __( 'How I Work', 'moghadam' ),
			'priority' => 60,
			'fields'   => array(
				'enabled'        => array(
					'label'   => __( 'Show this section', 'moghadam' ),
					'type'    => 'checkbox',
					'default' => true,
				),
				'eyebrow_number' => array(
					'label'   => __( 'Section number', 'moghadam' ),
					'type'    => 'text',
					'default' => '05',
				),
				'eyebrow_label'  => array(
					'label'   => __( 'Section label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'How I Work',
				),
				'link_label'     => array(
					'label'   => __( 'Corner link label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'LET&rsquo;S TALK',
				),
				'link_url'       => array(
					'label'   => __( 'Corner link URL', 'moghadam' ),
					'type'    => 'url',
					'default' => '#contact',
				),
				'step_1_title'   => array(
					'label'   => __( 'Step 1 title', 'moghadam' ),
					'type'    => 'text',
					'default' => 'I start with the business, not the deliverables.',
				),
				'step_1_text'    => array(
					'label'   => __( 'Step 1 text', 'moghadam' ),
					'type'    => 'html',
					'default' => 'I clarify product goals, user needs, and success metrics before deciding what should be designed or built.',
				),
				'step_2_title'   => array(
					'label'   => __( 'Step 2 title', 'moghadam' ),
					'type'    => 'text',
					'default' => 'I keep the team aligned on what&rsquo;s happening and why.',
				),
				'step_2_text'    => array(
					'label'   => __( 'Step 2 text', 'moghadam' ),
					'type'    => 'html',
					'default' => 'Clear updates, documented decisions, and regular check-ins keep progress visible and make course-correction easier.',
				),
				'step_3_title'   => array(
					'label'   => __( 'Step 3 title', 'moghadam' ),
					'type'    => 'text',
					'default' => 'I bring a point of view, not just execution.',
				),
				'step_3_text'    => array(
					'label'   => __( 'Step 3 text', 'moghadam' ),
					'type'    => 'html',
					'default' => 'I challenge assumptions, explain the trade-offs, and recommend the direction I believe will create the strongest product outcome.',
				),
				'step_4_title'   => array(
					'label'   => __( 'Step 4 title', 'moghadam' ),
					'type'    => 'text',
					'default' => 'I adapt to the team and the stage.',
				),
				'step_4_text'    => array(
					'label'   => __( 'Step 4 text', 'moghadam' ),
					'type'    => 'html',
					'default' => 'Every team works differently. I adjust my process to the product stage, constraints, tools, and pace without losing design rigor.',
				),
				'skills_label'   => array(
					'label'   => __( 'Skills heading', 'moghadam' ),
					'type'    => 'text',
					'default' => 'SKILLS',
				),
				'skills'         => array(
					'label'       => __( 'Skills', 'moghadam' ),
					'type'        => 'lines',
					'description' => __( 'One skill per line. They wrap into a dotted list.', 'moghadam' ),
					'default'     => "Product Strategy\nInteraction Design\nInformation Architecture & User Flows\nUX Research and Usability Testing\nUI & Visual Design\nDesign Systems & Design Tokens\nComplex SaaS & Enterprise UX\nPrototyping\nData-informed Design\nSystems Thinking\nMockups\nAI-assisted Product Workflows\nCross-Functional Collaboration\nRTL/LTR & Responsive Design\nDeveloper Handoff\nHTML/CSS/JS",
				),
			),
		),
		'cta'     => array(
			'title'    => __( 'Call to Action', 'moghadam' ),
			'priority' => 70,
			'fields'   => array(
				'enabled'      => array(
					'label'   => __( 'Show this section', 'moghadam' ),
					'type'    => 'checkbox',
					'default' => true,
				),
				'title'        => array(
					'label'   => __( 'Title', 'moghadam' ),
					'type'    => 'html',
					'default' => 'Have a product challenge worth solving? Let&rsquo;s talk',
				),
				'button_label' => array(
					'label'   => __( 'Button label', 'moghadam' ),
					'type'    => 'text',
					'default' => 'Let&rsquo;s talk 🔥',
				),
				'button_url'   => array(
					'label'   => __( 'Button link', 'moghadam' ),
					'type'    => 'url',
					'default' => '#',
				),
			),
		),
		'footer'  => array(
			'title'    => __( 'Footer', 'moghadam' ),
			'priority' => 80,
			'fields'   => array(
				'more_label'    => array(
					'label'       => __( '"More links" label', 'moghadam' ),
					'type'        => 'text',
					'description' => __( 'The dropdown is filled from the Footer More menu location.', 'moghadam' ),
					'default'     => 'More Links',
				),
				'legal'         => array(
					'label'   => __( 'Copyright', 'moghadam' ),
					'type'    => 'text',
					'default' => '&copy; 2014 &ndash; 2026',
				),
				'made_with'     => array(
					'label'   => __( 'Colophon', 'moghadam' ),
					'type'    => 'html',
					'default' => 'Made with <span class="heart">&#10084;</span> and WordPress',
				),
				'social_labels' => array(
					'label'       => __( 'Social links to show', 'moghadam' ),
					'type'        => 'text',
					'description' => __( 'Comma separated labels. Leave empty to show them all.', 'moghadam' ),
					'default'     => '',
				),
			),
		),
	);

	/**
	 * Filters the home page content schema.
	 *
	 * @since 1.3.0
	 *
	 * @param array $schema Section slug => section definition.
	 */
	return apply_filters( 'moghadam_home_schema', $schema );
}

/**
 * Look up a single field definition.
 *
 * @param string $section Section slug.
 * @param string $field   Field slug.
 * @return array|null
 */
function moghadam_home_field( $section, $field ) {
	$schema = moghadam_home_schema();

	return isset( $schema[ $section ]['fields'][ $field ] )
		? $schema[ $section ]['fields'][ $field ]
		: null;
}

/**
 * Theme mod name for a field.
 *
 * @param string $section Section slug.
 * @param string $field   Field slug.
 * @return string
 */
function moghadam_home_setting_id( $section, $field ) {
	return MOGHADAM_HOME_PREFIX . $section . '_' . $field;
}

/**
 * Read a home field, falling back to its schema default.
 *
 * @param string $section Section slug.
 * @param string $field   Field slug.
 * @return mixed
 */
function moghadam_home( $section, $field ) {
	$definition = moghadam_home_field( $section, $field );

	if ( null === $definition ) {
		return '';
	}

	$value = get_theme_mod( moghadam_home_setting_id( $section, $field ), $definition['default'] );

	if ( 'checkbox' === $definition['type'] ) {
		return (bool) $value;
	}

	if ( 'number' === $definition['type'] ) {
		return (int) $value;
	}

	return $value;
}

/**
 * Read a "lines" field as a trimmed array.
 *
 * @param string $section Section slug.
 * @param string $field   Field slug.
 * @return array
 */
function moghadam_home_lines( $section, $field ) {
	$raw   = (string) moghadam_home( $section, $field );
	$lines = preg_split( '/\R/', $raw );

	return array_values( array_filter( array_map( 'trim', (array) $lines ), 'strlen' ) );
}

/**
 * Whether a section should render.
 *
 * @param string $section Section slug.
 * @return bool
 */
function moghadam_home_enabled( $section ) {
	$definition = moghadam_home_field( $section, 'enabled' );

	return null === $definition ? true : (bool) moghadam_home( $section, 'enabled' );
}

/**
 * The markup allowed in "html" fields.
 *
 * @return array
 */
function moghadam_home_allowed_html() {
	return array(
		'a'      => array(
			'href'   => true,
			'title'  => true,
			'class'  => true,
			'target' => true,
			'rel'    => true,
		),
		'em'     => array( 'class' => true ),
		'strong' => array( 'class' => true ),
		'span'   => array( 'class' => true ),
		'br'     => array(),
		'p'      => array( 'class' => true ),
	);
}

/**
 * Print an "html" field through the allowed-markup filter.
 *
 * @param string $section Section slug.
 * @param string $field   Field slug.
 */
function moghadam_home_html( $section, $field ) {
	echo wp_kses( (string) moghadam_home( $section, $field ), moghadam_home_allowed_html() );
}
