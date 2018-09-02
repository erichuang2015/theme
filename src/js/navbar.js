/**
 * Navbar
 */
(function( $ )
{
	"use strict";

	function Plugin( elem, options )
	{
		this.$elem   = $( elem );
		this.options = $.extend( {}, Plugin.defaultOptions, options );

		this.initExpand();

		if ( this.options.sticky ) 
		{
			this.initSticky();
		}
		this.initNavExpand();

		$( document ).trigger( 'navbar/init', [ this ] );
	}

	Plugin.defaultOptions =
	{
		sticky : false,
		breakpoints : 
		{
			xs: 0,
			sm: 576,
			md: 768,
			lg: 992,
			xl: 1200
		}
	};

	Plugin.waitForFinalEvent = (function()
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

	Plugin.prototype.$elem = null;
	Plugin.prototype.options = {};

	Plugin.prototype.updateExpand = function()
	{
		var breakpoint = this.getNavExpand(), width;

		if ( breakpoint && typeof this.options.breakpoints[ breakpoint ] !== undefined ) 
		{
			width = this.options.breakpoints[ breakpoint ];
		}

		else
		{
			width = this.options.breakpoints.xs;
		};

		var viewport = theme.getViewPort();

		if ( width <= viewport.width ) 
		{
			this.isExpanded = true;
		}

		else
		{
			this.isExpanded = false;
		}

		this.$elem.trigger( 'change', [ this ] );
	};

	Plugin.prototype.initExpand = function()
	{
		var _this = this;

		$( window ).on( 'resize', function()
		{
			Plugin.waitForFinalEvent( function()
			{
				_this.updateExpand();

			}, 300, 'navbar');
		});

		this.updateExpand();
	};

	Plugin.prototype.initNavExpand = function()
	{
		var _this = this;

		this.$elem.find( '#navbar-nav' )

		// This event fires immediately when the show instance method is called.
		.on( 'show.bs.collapse', function( event )
		{
			_this.isNavExpanded = true;

			_this.$elem.trigger( 'change', [ _this ] );
		})

		// This event is fired when a collapse element has been hidden from the user (will wait for CSS transitions to complete).
		.on( 'hidden.bs.collapse', function( event )
		{
			_this.isNavExpanded = false;

			_this.$elem.trigger( 'change', [ _this ] );
		})
	};

	Plugin.prototype.initSticky = function()
	{
		var _this = this;

		// Prevents sticky class to be set when navbar is at default position.

		this.$elem.parent().children().eq(0).css( 'margin-top', '-1px' )
			.parent().css( 'padding-top', '1px' );

		// Makes sure adminbar is absolute positioned. (prevents layout issues)

		$( '#wpadminbar' ).css( 'position', 'absolute' );

		// init Sticky Kit

		this.$elem.stick_in_parent(
		{
			parent : 'body',
			sticky_class : 'navbar-stuck'
		})

		// Called when stuck
		.on( 'sticky_kit:stick', function( event )
		{
			_this.isStuck = true;

			_this.$elem.trigger( 'change', [ _this ] );
		})

		// Called when unstuck
		.on( 'sticky_kit:unstick', function( event ) 
		{
			_this.isStuck = false;

			_this.$elem.trigger( 'change', [ _this ] );
		});
	};
	 
	Plugin.prototype.getTheme = function()
	{
		var classes = [];

		// Get Theme

		var matches = this.$elem.attr( 'class' ).match( /(?:^| )(navbar-(?:dark|light))(?: |$)/ );

		if ( matches ) 
		{
			classes.push( matches[1] );
		}

		// Get Background

		var matches = this.$elem.attr( 'class' ).match( /(?:^| )(bg-[a-z0-9_-]+)(?: |$)/ );

		if ( matches ) 
		{
			classes.push( matches[1] );
		}

		//

		return classes.join( ' ' );
	};

	Plugin.prototype.getNavExpand = function()
	{
		var matches = this.$elem.attr( 'class' ).match( /(?:^| )navbar-expand-([a-z]+)(?: |$)/ );

		return matches ? matches[ 1 ] : null;
	};

	Plugin.prototype.setTheme = function( className )
	{
		var current = this.getTheme();

		// Store current

		if ( typeof this.$elem.data( 'theme.original' ) === 'undefined' ) 
		{
			this.$elem.data( 'theme.original', current );
		}

		// Set new

		this.$elem.removeClass( current ).addClass( className );
	};

	Plugin.prototype.restoreTheme = function()
	{
		var current  = this.getTheme();
		var original = this.$elem.data( 'theme.original' ) || '';

		this.$elem.removeClass( current ).addClass( original );
	};

	/**
	 * jQuery Function
	 */
	$.fn.navbar = function( options )
	{
		return this.each( function()
		{
			if ( $( this ).data( 'navbar' ) ) 
			{
				return true;
			}
 
			var instance = new Plugin( this, options );
 
			$( this ).data( 'navbar', instance );
		});
	};

})( jQuery );
