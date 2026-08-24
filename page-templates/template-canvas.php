<?php
/**
 * Template Name: Canvas
 * Template Post Type: page
 *
 * A bare HTML document for pages whose markup is authored by hand.
 *
 * The theme contributes no styles, no scripts and no chrome here: there is no
 * site header, no footer, no sidebar and no theme CSS. What does still run is
 * wp_head() and wp_footer(), so title tags, canonical URLs, Open Graph data,
 * schema and any other SEO or plugin output stay exactly as they are on every
 * other page.
 *
 * Paragraph auto-formatting is disabled on this template so hand-written HTML
 * reaches the browser untouched.
 *
 * @package Moghadam
 * @since   1.1.0
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
while ( have_posts() ) :
	the_post();
	the_content();
endwhile;
?>
<?php wp_footer(); ?>
</body>
</html>
