/**
 * SVG
 */
(function( $ )
{
	"use strict";

	var theme = window.theme || {};

	/*
	 * Test if inline SVGs are supported.
	 * @link https://github.com/Modernizr/Modernizr/
	 */
	theme.supportsInlineSVG = function()
	{
		var div = document.createElement( 'div' );
		div.innerHTML = '<svg/>';
		return 'http://www.w3.org/2000/svg' === ( typeof SVGRect !== 'undefined' && div.firstChild && div.firstChild.namespaceURI );
	}

	$( document ).ready( function()
	{
		if ( theme.supportsInlineSVG() ) 
		{
			$( 'body' ).removeClass( 'no-svg' ).addClass( 'svg' );
		}
	});

})( jQuery );