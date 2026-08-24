<?php
/**
 * The template for displaying the search form.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;

$moghadam_search_id = 'search-field-' . wp_unique_id();
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $moghadam_search_id ); ?>" class="screen-reader-text">
		<?php esc_html_e( 'Search for:', 'moghadam' ); ?>
	</label>
	<input
		type="search"
		id="<?php echo esc_attr( $moghadam_search_id ); ?>"
		class="search-field"
		placeholder="<?php esc_attr_e( 'Search &hellip;', 'moghadam' ); ?>"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		name="s"
	/>
	<button type="submit" class="search-submit"><?php esc_html_e( 'Search', 'moghadam' ); ?></button>
</form>
