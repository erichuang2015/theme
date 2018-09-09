/**
 * Cover Image
 */
(function( $ )
{
	jQuery( window ).on( 'load', function()
	{
		// Set image as background image

		$( '.browser-ie .cover-image' ).each( function()
		{
			$( this ).find( 'img' )
				.css( 'background-image', 'url(' + $img.attr( 'src' ) + ')' )
				.hide();
		});
	});
})( jQuery );