/**
 * Common
 */
(function( $ )
{
	"use strict";

	var theme = window.theme || {};

	$( document ).on( 'ready', function( event )
	{
		$( 'body' ).removeClass( 'no-js' ).addClass( 'js' );

		if ( theme.supportsInlineSVG() ) 
		{
			$( 'body' ).removeClass( 'no-svg' ).addClass( 'svg' );
		}
		
		$( '.hide-if-js' ).hide();
		$( '.show-if-js' ).show();
	});

})( jQuery );

