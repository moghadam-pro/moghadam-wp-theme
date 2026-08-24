/**
 * Primary navigation: mobile menu toggle and Escape/outside-click handling.
 *
 * @package Moghadam
 */

( function () {
	'use strict';

	var nav = document.getElementById( 'site-navigation' );

	if ( ! nav ) {
		return;
	}

	var button = nav.querySelector( '.menu-toggle' );
	var menu = nav.querySelector( 'ul' );

	if ( ! button || ! menu ) {
		return;
	}

	function setExpanded( expanded ) {
		nav.classList.toggle( 'toggled', expanded );
		button.setAttribute( 'aria-expanded', expanded ? 'true' : 'false' );
	}

	button.addEventListener( 'click', function () {
		setExpanded( ! nav.classList.contains( 'toggled' ) );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && nav.classList.contains( 'toggled' ) ) {
			setExpanded( false );
			button.focus();
		}
	} );

	document.addEventListener( 'click', function ( event ) {
		if ( nav.classList.contains( 'toggled' ) && ! nav.contains( event.target ) ) {
			setExpanded( false );
		}
	} );
}() );
