<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Moghadam
 */

defined( 'ABSPATH' ) || exit;

if ( ! moghadam_has_sidebar() ) {
	return;
}
?>
<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
