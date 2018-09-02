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
			var $img = $( this ).find( 'img' );

			$( this ).css( 'background-image', 'url(' + $img.attr( 'src' ) + ')' );

			$img.hide();
		});
	});
})( jQuery );