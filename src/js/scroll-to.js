/**
 * ScrollTo
 *
 * Dependencies: jquery-scrollto
 */
(function( $ )
{
	"use strict";

	$( document ).on( 'ready', function( event )
	{
		var $elem = $( '.scroll-to' );

		if ( typeof $.fn.scrollTo === 'undefined' ) 
		{
			if ( $elem.length ) 
			{
				console.warn( ".scroll-to needs 'jquery-scrollto' plugin." );
			}

			return;
		}

		$elem.on( 'click', function( event )
		{
			// Gets target

			var target;

			if ( $( this ).data( 'target' ) ) 
			{
				target = $( this ).data( 'target' );
			}

			else if ( $( this ).is( 'a' ) )
			{
				var href = $( this ).attr( 'href' );

				// Finds hash part

				if ( href ) 
				{
					target = href.substring( href.lastIndexOf( '#' ) );
				}
			}

			// Checks if target is in DOM

			if ( ! target || ! $( target ).length )
			{
				return;
			}

			// Prevents default event actions

			event.preventDefault();

			// init scrolling

			$.scrollTo( target, 
			{
				duration: $( this ).data( 'duration' ) || 400,
				offset : - $( '#header' ).height()
			});
		});
		
	});
})( jQuery );