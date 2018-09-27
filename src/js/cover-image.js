/**
 * Cover Image
 */
(function( $ )
{
	"use strict";

	$( document ).on( 'ready', function()
	{
		// IE fallback
		$( '.browser-ie .cover-image' ).each( function()
		{
			var $img = $( this ).find( 'img' );

			$( this ).css( 'background-image', 'url(' + $img.attr( 'src' ) + ')' );
		});
	});

})( jQuery );
