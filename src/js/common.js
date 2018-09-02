/**
 * Helpers
 */
(function( $ )
{
	var theme = window.theme || {};

	// https://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed
	theme.waitForFinalEvent = (function()
	{
		var timers = {};
		
		return function( callback, ms, uniqueId ) 
		{
			if ( ! uniqueId )
			{
				uniqueId =  "Don't call this twice without a uniqueId";
			};

			if ( timers[ uniqueId ] )
			{
				clearTimeout( timers[ uniqueId ] );
			};

			timers[ uniqueId ] = setTimeout( callback, ms );
		};
	})();

	// https://stackoverflow.com/questions/1248081/get-the-browser-viewport-dimensions-with-javascript
	theme.getViewPort = function()
	{
		return {
			width  : Math.max( document.documentElement.clientWidth, window.innerWidth || 0 ),
			height : Math.max( document.documentElement.clientHeight, window.innerHeight || 0 )
		};
	};

})( jQuery );
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

		$( '.hide-if-js' ).hide();
		$( '.show-if-js' ).show();
	});

})( jQuery );

