/**
 * Navbar
 */
(function( $ )
{
	"use strict";

	var theme = window.theme || {};

	function Plugin( elem, options )
	{
		var _this = this;

		this.$elem = $( elem );
		this.options = $.extend( {}, Plugin.defaultOptions, options );

		if ( this.options.sticky ) 
		{
			this.initSticky();
		};

		this.initExpand();
		this.initNavExpand();

		$( document ).trigger( 'theme.navbar', [ this ] );
	}

	Plugin.THEME_BACKUP_KEY = 'theme.backup';
	Plugin.defaultOptions = 
	{
		sticky 		: true,
		stickyClass : 'navbar-stuck'
	};

	Plugin.prototype.$elem       = null;
	Plugin.prototype.options     = false;
	Plugin.prototype.isStuck     = false;
	Plugin.prototype.isExpand    = false;
	Plugin.prototype.isNavExpand = false;

	Plugin.prototype.set = function( prop, value )
	{
		if ( typeof this[ prop ] === 'undefined' ) 
		{
			return;
		}

		if ( this[ prop ] === value ) 
		{
			return;
		}

		this[ prop ] = value;
	
		this.$elem.trigger( 'change', this );
	}

	/**
	 * Init Sticky
	 */
	Plugin.prototype.initSticky = function()
	{
		var _this = this;

		// Prevents sticky class from being set when navbar's is on top of screen.
		//this.$elem.parent().children().eq(0).css( 'margin-top', '-1px' )
			//.parent().css( 'padding-top', '1px' );

		// Makes sure adminbar is absolute positioned. (prevents layout issues)
		$( '#wpadminbar' ).css( 'position', 'absolute' );

		// Init Sticky Kit
		var initStickyKit = function()
		{
			_this.$elem.stick_in_parent(
			{
				parent : 'body',
				sticky_class : _this.options.stickyClass,
			})

			// Called when stuck
			.on( 'sticky_kit:stick', function( event )
			{
				_this.set( 'isStuck', true );
			})

			// Called when unstuck
			.on( 'sticky_kit:unstick', function( event ) 
			{
				_this.set( 'isStuck', false );
			})

			// Called when detached
			.on( 'sticky_kit:detach', function( event ) 
			{
				_this.set( 'isStuck', false );
			});
		}

		/**
		 * Maybe init Sticky Kit
		 *
		 * Stick elem when its outside viewport.
		 * This Prevents a gap from showing when elem 
		 * gets smaller when stuck
		 */
		var maybeInitStickyKit = function()
		{
			// In viewport
			if ( theme.isInViewPort( _this.$elem ) ) 
			{
				// Detach elem and Destroy Sticky Kit
				_this.$elem.trigger( 'sticky_kit:detach' );
			}

			// Not in viewport
			else if ( typeof _this.$elem.data( 'sticky_kit' ) === 'undefined' )
			{
				// init Sticky Kit
				initStickyKit();
			}
		}

		$( window ).on( 'scroll', function()
		{
			maybeInitStickyKit();
		});

		maybeInitStickyKit();
	};

	/**
	 * Init Expand
	 */
	Plugin.prototype.initExpand = function()
	{
		var _this = this;

		// Check when navbar is expanded.
		var update = function()
		{
			var expand      = _this.getExpand();
			var viewport    = theme.getViewPort();
			var breakpoints = theme.gridBreakpoints;
			var isExpand    = false;

			if ( expand && typeof breakpoints[ expand ] !== 'undefined' ) 
			{
				var expandWidth = breakpoints[ expand ];

				if ( expandWidth < viewport.width ) 
				{
					isExpand = true;
				}
			}

			_this.set( 'isExpand', isExpand );
		}

		$( window ).on( 'resize', function( event )
		{
			theme.waitForFinalEvent( function()
			{
				update();
			}, 300, 'navbar/initExpand' );
		});

		update();
	};

	/**
	 * Init Nav Expand
	 */
	Plugin.prototype.initNavExpand = function() 
	{
		var _this = this;

		this.$elem.find( '#navbar-nav' )

			// This event fires immediately when the show instance method is called.
			.on( 'show.bs.collapse', function( event )
			{
				_this.set( 'isNavExpand', true );
			})

			// This event is fired when a collapse element has been hidden from the user (will wait for CSS transitions to complete).
			.on( 'hidden.bs.collapse', function( event )
			{
				_this.set( 'isNavExpand', false );
			})
	};

	/**
	 * Get Theme
	 */
	Plugin.prototype.getTheme = function()
	{
		var classes = [];

		// Get Theme class

		var matches = this.$elem.attr( 'class' ).match( /(?:^| )(navbar-(?:dark|light))(?: |$)/ );

		if ( matches ) 
		{
			classes.push( matches[1] );
		}

		// Get Background class

		var matches = this.$elem.attr( 'class' ).match( /(?:^| )(bg-[a-z0-9_-]+)(?: |$)/ );

		if ( matches ) 
		{
			classes.push( matches[1] );
		}

		// return

		return classes.join( ' ' );
	}

	/**
	 * Get Original Theme
	 */
	Plugin.prototype.getOriginalTheme = function()
	{
		return this.$elem.data( Plugin.THEME_BACKUP_KEY );
	}

	/**
	 * Set Theme
	 */
	Plugin.prototype.setTheme = function( className )
	{
		var current = this.getTheme();

		// Backup original theme

		if ( typeof this.$elem.data( Plugin.THEME_BACKUP_KEY ) === 'undefined' ) 
		{
			this.$elem.data( Plugin.THEME_BACKUP_KEY, current );
		};

		// Switch theme

		this.$elem.removeClass( current ).addClass( className );
	}

	/**
	 * Restore Theme
	 */
	Plugin.prototype.restoreTheme = function()
	{
		// Check backup

		if ( typeof this.$elem.data( Plugin.THEME_BACKUP_KEY ) === 'undefined' ) 
		{
			return;
		}

		// Replace current theme with original theme

		var current  = this.getTheme();
		var original = this.$elem.data( Plugin.THEME_BACKUP_KEY );

		this.$elem.removeClass( current ).addClass( original );
	}

	/**
	 * Get Expand
	 */
	Plugin.prototype.getExpand = function()
	{
		var breakpoint;

		if ( this.$elem.hasClass( 'navbar-expand' ) ) 
		{
			return 'xs';
		}

		var matches = this.$elem.attr( 'class' ).match( /(?:^| )navbar-expand-([a-z]+)(?: |$)/ );

		return matches ? matches[ 1 ] : null;
	}

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
