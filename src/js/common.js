/**
 * Common
 */
(function( $ )
{
	"use strict";

	var theme = window.theme || {};

	/**
	 * Navbar
	 */
	function initNavbar()
	{
		jQuery( '.navbar' ).navbar(
		{
			sticky : true
		})

		// Check state change
		.on( 'change', function( event, navbar )
		{
			// Update theme

			var isTranparantOverlay = false;

			if ( navbar.isStuck ) 
			{
				navbar.setTheme( 'navbar-dark bg-dark' );
			} 

			else if ( isTranparantOverlay && navbar.isNavExpand && ! navbar.isExpand ) 
			{
				navbar.setTheme( 'navbar-dark bg-dark' );
			}

			else
			{
				navbar.restoreTheme();
			}
		});
	}

	/**
	 * SVG support
	 */
	if ( theme.supportsInlineSVG() ) 
	{
		$( 'html' ).removeClass( 'no-svg' ).addClass( 'svg' );
	}

	$( document ).on( 'ready', function( event )
	{
		// Toggle javascript related elements
		$( '.hide-if-js' ).hide();
		$( '.show-if-js' ).show();

		// Init navbar
		initNavbar();
	});

})( jQuery );

