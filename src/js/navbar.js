/**
 * Navbar
 */
(function( $ )
{
	"use strict";

	var theme = window.theme || {};

	theme.navbar = 
	{
		$elem : null,

		isStuck : false,
		isExpanded : false,
		isNavExpanded : false,

		init : function()
		{
			var _this = this;

			this.$elem = $( '.navbar' );

			if ( this.$elem.is( '.navbar-sticky' ) ) 
			{
				this.initSticky();
			}

			// Nav Expanded

			this.$elem.find( '#navbar-nav' )

				// This event fires immediately when the show instance method is called.
				.on( 'show.bs.collapse', function( event )
				{
					_this.isNavExpanded = true;

					_this.$elem.trigger( 'theme/change' );
				})

				// This event is fired when a collapse element has been hidden from the user (will wait for CSS transitions to complete).
				.on( 'hidden.bs.collapse', function( event )
				{
					_this.isNavExpanded = false;

					_this.$elem.trigger( 'theme/change' );
				})

			// Expanded

			$( window ).on( 'resize', function()
			{
				theme.waitForFinalEvent( function()
				{
					_this.updatedExpanded();

				}, 300, 'navbar');
			});

			this.updatedExpanded();
		},

		updatedExpanded : function()
		{
			var breakpoints = 
			{
				xs: 0,
				sm: 576,
				md: 768,
				lg: 992,
				xl: 1200
			};

			var breakpoint = this.getBreakpoint(), width;

			if ( breakpoint && typeof breakpoints[ breakpoint ] !== undefined ) 
			{
				width = breakpoints[ breakpoint ];
			}

			else
			{
				width = breakpoints.xs;
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

			this.$elem.trigger( 'theme/change' );
		},

		getBreakpoint : function()
		{
			var matches = this.$elem.attr( 'class' ).match( /(?:^| )navbar-expand-([a-z]+)(?: |$)/ );

			if ( matches ) 
			{
				return matches[ 1 ];
			}

			return null;
		},

		getTheme : function()
		{
			var classes = [];

			// Theme: navbar-{slug}

			var matches = this.$elem.attr( 'class' ).match( /(?:^| )(navbar-(?:light|dark))(?: |$)/ );

			if ( matches ) 
			{
				classes.push( matches[1] );
			}

			// Background: bg-{slug}

			var matches = this.$elem.attr( 'class' ).match( /(?:^| )(bg-[a-z]+)(?: |$)/ );

			if ( matches ) 
			{
				classes.push( matches[1] );
			}

			//

			return classes.join( ' ' );
		},

		setTheme : function( className )
		{
			var theme = this.getTheme();

			// Stores original theme

			if ( ! this.$elem.data( 'theme' ) ) 
			{
				this.$elem.data( 'theme', theme );	
			}

			this.$elem
				.removeClass( theme )
				.addClass( className );
		},

		restoreTheme : function()
		{
			this.$elem
				.removeClass( this.getTheme() )
				.addClass( this.$elem.data( 'theme' ) );
		},

		initSticky : function()
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

				_this.$elem.trigger( 'theme/change' );
			})

			// Called when unstuck
			.on( 'sticky_kit:unstick', function( event ) 
			{
				_this.isStuck = false;

				_this.$elem.trigger( 'theme/change' );
			});
		},
	};

})( jQuery );

(function( $ )
{
	$( document ).on( 'ready', function()
	{
		theme.navbar.init();

		theme.navbar.$elem.on( 'theme/change', function( event )
		{
			var navbarAltTheme = theme.navbar.$elem.data( 'themealt' );

			if ( theme.navbar.isStuck || ( theme.navbar.isNavExpanded && ! theme.navbar.isExpanded ) ) 
			{
				theme.navbar.setTheme( navbarAltTheme );
			}

			else
			{
				theme.navbar.restoreTheme();
			}
		});
	});

})( jQuery );
