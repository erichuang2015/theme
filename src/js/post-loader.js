/**
 * Post Loader
 */
(function( $ )
{
	"use strict";

	function Plugin( elem, options )
	{
		this.$elem    = $( elem );
		this.options  = $.extend( {}, Plugin.defaultOptions, options );

		this.$elem.addClass( 'post-loader' );

		var _this = this;

		// Form submit
		this.$elem.on( 'submit', '.post-loader-form', function( event )
		{
			event.preventDefault();

			// Load
			_this.load();
		});

		// Form reset
		this.$elem.on( 'reset', '.post-loader-form', function( event )
		{
			setTimeout( function()
			{
				_this.updateLabelActive();

				if ( _this.options.loadOnReset ) 
				{
					_this.load();
				}
			}, 50);
		});

		// Pagination Click
		this.$elem.on( 'click', '.pagination .page-link', function( event )
		{
			event.preventDefault();

			// Load
			_this.load( 
			{
				page : $( this ).data( 'page' ),
				animate : false,
			});
		});

		// 'Load More' Click
		this.$elem.on( 'click', '.post-loader-more-button', function( event )
		{
			event.preventDefault();

			// Load
			_this.load( 
			{
				page    : $( this ).data( 'page' ),
				animate : true,
				append  : true,
			});
		});

		// input.autload change
		this.$elem.on( 'change', ':input.autoload', function( event )
		{
			// Load
			_this.load();
		});

		// Checkbox and radio change
		this.$elem.on( 'change', 'input[type="checkbox"], input[type="radio"]', function( event )
		{
			_this.updateLabelActive( this );
		});

		$( document ).trigger( 'postLoader.init', [ this ] );
	}

	Plugin.defaultOptions = 
	{
		scrollSpeed  : 500, // Milliseconds
		scrollOffset : -120,
		loadOnReset  : true,
	};

	Plugin.prototype.$elem = null;
	Plugin.prototype.$content = null;
	Plugin.prototype.options = {};

	Plugin.prototype.updateLabelActive = function( elem )
	{
		if ( typeof elem === 'undefined' ) 
		{
			elem = this.$elem.find( 'input[type="checkbox"], input[type="radio"]' );
		}

		var _this = this;

		$( elem ).filter( 'input[type="checkbox"], input[type="radio"]' ).each( function()
		{
			// Radio
			if ( $( elem ).prop( 'type' ) == 'radio' ) 
			{
				// Select related radio buttons. And removes label 'active' class.
				_this.$elem.find( 'input[type="radio"]' ).not( elem, ':checked' ).filter( function()
				{
					return $( this ).prop( 'name' ) == $( elem ).prop( 'name' );

				}).closest( 'label' ).removeClass( 'active' );
			}

			var $label = $( this ).closest( 'label' );

			if ( $( this ).is( ':checked' ) ) 
			{
				$label.addClass( 'active' );
			}

			else
			{
				$label.removeClass( 'active' );
			}
		});
	}

	Plugin.prototype.load = function( options ) 
	{
		var defaults = 
		{
			page    : 1,
			animate : false,
			append  : false,
		};

		options = $.extend( {}, defaults, options );

		this.$elem.find( ':input[name="paged"]' ).val( options.page );

		var $fields = this.$elem.find( ':input:not([disabled])' );

		$.ajax(
		{
			url : theme.ajaxurl,
			method : 'POST',
			data : this.$elem.find( '.post-loader-form' ).serialize(),
			context : this,

			beforeSend : function( jqXHR, settings )
			{
				this.$elem.addClass( 'loading' );

				$fields.prop( 'disabled', true );

				this.$elem.trigger( 'postLoader.loadBeforeSend', [ jqXHR, settings ] );
			},

			success : function( response, textStatus, jqXHR )
			{
				console.log( 'response', response );

				// Get content
				var $content = $( response.content );

				// Check append
				if ( ! options.append ) 
				{
					// Empty content
					this.$elem.find( '.post-loader-content' ).html( '' );
				}

				else
				{
					// Remove 'load more' from current content
					this.$elem.find( '.post-loader-content .post-loader-more' ).remove();
				}
				
				// Add content
				this.$elem.find( '.post-loader-content' ).append( $content );

				// Animation
				if ( options.animate ) 
				{
					// Get target

					var $target = this.$elem.find( '.post-loader-content' );

					if ( options.append ) 
					{
						$target = $content.children().first();
					}

					// Scroll to target
					$( [ document.documentElement, document.body ] ).stop().animate(
					{
						scrollTop: $target.offset().top + this.options.scrollOffset

					}, this.options.scrollSpeed );
				}

				this.$elem.trigger( 'postLoader.loadSuccess', [ response, textStatus, jqXHR ] );
			},

			error : function( jqXHR, textStatus, errorThrown )
			{
				this.$elem.trigger( 'postLoader.loadError', [ jqXHR, textStatus, errorThrown ] );
			},

			complete : function( jqXHR, textStatus )
			{
				this.$elem.removeClass( 'loading' );

				$fields.prop( 'disabled', false );

				this.$elem.trigger( 'postLoader.loadComplete', [ jqXHR, textStatus ] );
			},
		})
	};

	$.fn.postLoader = function( options )
	{
		return this.each( function()
		{
			if ( typeof $( this ).data( 'postLoader' ) === 'undefined' ) 
			{
				var instance = new Plugin( this, options );

				$( this ).data( 'postLoader', instance );
			}
		});
	}

})( jQuery );

(function( $ )
{
	$( document ).on( 'ready', function()
	{
		$( '.post-loader' ).postLoader();
	});

})( jQuery );
