/**
 * Breakpoint
 */
(function( $ )
{
	"use strict";

	var theme = window.theme || {};

	var breakpoint  = null;
	var breakpoints = theme.gridBreakpoints || {};
	var listeners   = [];

	function init()
	{
		$( window ).on( 'resize', function()
		{
			theme.waitForFinalEvent( update, 300, 'breakpoint' );
		});

		update();
	}

	function change( callback )
	{
		listeners.push( callback );

		if ( listeners.length == 1 ) 
		{
			init();
		}
	}

	function update()
	{
		var viewport = theme.getViewPort();

		// Get breakpoint

		var current = null;

		if ( viewport.width < breakpoints.sm ) 
		{
			current = 'xs';
		}

		else if ( viewport.width >= breakpoints.sm && viewport.width < breakpoints.md )
		{
			current = 'sm';
		}

		else if ( viewport.width >= breakpoints.md && viewport.width < breakpoints.lg )
		{
			current = 'md';
		}

		else if ( viewport.width >= breakpoints.lg && viewport.width < breakpoints.xl )
		{
			current = 'lg';
		}

		else if ( viewport.width >= breakpoints.xl )
		{
			current = 'xl';
		}

		// Check breakpoint change

		if ( breakpoint == current ) 
		{
			return;
		}

		breakpoint = current;

		// Notify Observers

		$.each( listeners, function( i, callback )
		{
			callback( breakpoint, breakpoints );
		});
	}

	theme.breakpoint = 
	{
		change : change
	};

})( jQuery );
