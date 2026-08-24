/**
 * Live-preview bindings for the Customizer.
 *
 * @package Moghadam
 */

( function ( $ ) {
	'use strict';

	wp.customize( 'blogname', function ( value ) {
		value.bind( function ( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	wp.customize( 'blogdescription', function ( value ) {
		value.bind( function ( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
}( jQuery ) );
