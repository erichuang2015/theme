/**
 * Utilities
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

	/*
	 * Test if inline SVGs are supported.
	 * @link https://github.com/Modernizr/Modernizr/
	 */
	theme.supportsInlineSVG = function()
	{
		var div = document.createElement( 'div' );
		div.innerHTML = '<svg/>';
		return 'http://www.w3.org/2000/svg' === ( typeof SVGRect !== 'undefined' && div.firstChild && div.firstChild.namespaceURI );
	};

	// @link https://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport
	theme.isInViewPort = function( elem )
	{
		var elem = $( elem ).get( 0 );

		var top    = elem.offsetTop;
		var left   = elem.offsetLeft;
		var width  = elem.offsetWidth;
		var height = elem.offsetHeight;

		while( elem.offsetParent ) 
		{
			elem = elem.offsetParent;
			top += elem.offsetTop;
			left += elem.offsetLeft;
		}

		return (
			top < (window.pageYOffset + window.innerHeight) 
			&& left < (window.pageXOffset + window.innerWidth) 
			&& (top + height) > window.pageYOffset 
			&& (left + width) > window.pageXOffset
		);
	}

})( jQuery );