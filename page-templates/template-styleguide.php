<?php
/**
 * Template Name: Theme
 * Template Post Type: page
 *
 * The theme's style guide. Renders every design token and every styled element
 * on one page, using the live values, so the result of a settings change can be
 * checked in one place.
 *
 * Content written in the editor is rendered first, then the generated sections.
 *
 * @package Moghadam
 * @since   1.1.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="primary" <?php moghadam_main_class( 'styleguide' ); ?>>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<header class="page-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header>

			<?php if ( '' !== trim( get_the_content() ) ) : ?>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
			<?php
		endwhile;
		?>

		<section class="style-guide-section">
			<h2><?php esc_html_e( 'Colors', 'moghadam' ); ?></h2>
			<div class="style-guide-swatches">
				<?php foreach ( moghadam_style_guide_colors() as $token ) : ?>
					<div class="style-guide-swatch">
						<span class="style-guide-swatch-chip" style="background-color: var(<?php echo esc_attr( $token['var'] ); ?>);"></span>
						<strong><?php echo esc_html( $token['label'] ); ?></strong>
						<code><?php echo esc_html( $token['var'] ); ?></code>
						<?php if ( ! empty( $token['usage'] ) ) : ?>
							<span class="style-guide-usage"><?php echo esc_html( $token['usage'] ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="style-guide-section">
			<h2><?php esc_html_e( 'Typography', 'moghadam' ); ?></h2>
			<table class="style-guide-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Token', 'moghadam' ); ?></th>
						<th><?php esc_html_e( 'Variable', 'moghadam' ); ?></th>
						<th><?php esc_html_e( 'Used for', 'moghadam' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( moghadam_style_guide_typography() as $token ) : ?>
						<tr>
							<td><?php echo esc_html( $token['label'] ); ?></td>
							<td><code><?php echo esc_html( $token['var'] ); ?></code></td>
							<td><?php echo esc_html( isset( $token['usage'] ) ? $token['usage'] : '' ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<div class="style-guide-scale">
				<h1><?php esc_html_e( 'Heading level 1', 'moghadam' ); ?></h1>
				<h2><?php esc_html_e( 'Heading level 2', 'moghadam' ); ?></h2>
				<h3><?php esc_html_e( 'Heading level 3', 'moghadam' ); ?></h3>
				<h4><?php esc_html_e( 'Heading level 4', 'moghadam' ); ?></h4>
				<h5><?php esc_html_e( 'Heading level 5', 'moghadam' ); ?></h5>
				<h6><?php esc_html_e( 'Heading level 6', 'moghadam' ); ?></h6>
				<p><?php esc_html_e( 'Body copy sits at the base size with the theme line height. A sentence long enough to wrap shows the measure the content width produces, which is what the reading experience actually depends on.', 'moghadam' ); ?></p>
				<p>
					<a href="#"><?php esc_html_e( 'A link', 'moghadam' ); ?></a> &middot;
					<strong><?php esc_html_e( 'Bold', 'moghadam' ); ?></strong> &middot;
					<em><?php esc_html_e( 'Italic', 'moghadam' ); ?></em> &middot;
					<code><?php esc_html_e( 'inline_code()', 'moghadam' ); ?></code>
				</p>
			</div>
		</section>

		<section class="style-guide-section">
			<h2><?php esc_html_e( 'Spacing and sizing', 'moghadam' ); ?></h2>
			<div class="style-guide-spacing">
				<?php foreach ( moghadam_style_guide_spacing() as $token ) : ?>
					<div class="style-guide-spacing-row">
						<strong><?php echo esc_html( $token['label'] ); ?></strong>
						<code><?php echo esc_html( $token['var'] ); ?></code>
						<span class="style-guide-bar" style="width: var(<?php echo esc_attr( $token['var'] ); ?>);"></span>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="style-guide-section">
			<h2><?php esc_html_e( 'Elements', 'moghadam' ); ?></h2>

			<h3><?php esc_html_e( 'Buttons and forms', 'moghadam' ); ?></h3>
			<p>
				<button type="button"><?php esc_html_e( 'Button', 'moghadam' ); ?></button>
			</p>
			<?php get_search_form(); ?>

			<h3><?php esc_html_e( 'Blockquote', 'moghadam' ); ?></h3>
			<blockquote>
				<p><?php esc_html_e( 'A quotation carries the accent border and the muted text color, so it reads as a distinct voice without shouting.', 'moghadam' ); ?></p>
			</blockquote>

			<h3><?php esc_html_e( 'Lists', 'moghadam' ); ?></h3>
			<ul>
				<li><?php esc_html_e( 'Unordered item', 'moghadam' ); ?></li>
				<li><?php esc_html_e( 'Another item', 'moghadam' ); ?></li>
			</ul>
			<ol>
				<li><?php esc_html_e( 'Ordered item', 'moghadam' ); ?></li>
				<li><?php esc_html_e( 'Another item', 'moghadam' ); ?></li>
			</ol>

			<h3><?php esc_html_e( 'Code block', 'moghadam' ); ?></h3>
			<pre><code>.site {
	display: flex;
	flex-direction: column;
}</code></pre>

			<h3><?php esc_html_e( 'Table', 'moghadam' ); ?></h3>
			<table>
				<thead>
					<tr>
						<th><?php esc_html_e( 'Column', 'moghadam' ); ?></th>
						<th><?php esc_html_e( 'Column', 'moghadam' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php esc_html_e( 'Cell', 'moghadam' ); ?></td>
						<td><?php esc_html_e( 'Cell', 'moghadam' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Cell', 'moghadam' ); ?></td>
						<td><?php esc_html_e( 'Cell', 'moghadam' ); ?></td>
					</tr>
				</tbody>
			</table>
		</section>
	</main><!-- #primary -->
<?php
get_footer();
