(function( $ )
{
	"use strict";

	/**
	 * Construct
	 */
	function Plugin( elem, options )
	{
		this.$elem   = $( elem );
		this.options = $.extend( {}, Plugin.defaultOptions, options );

		var _this = this;

		// Checkbox and radio change.
		this.$elem.on( 'change', 'input[type="checkbox"], input[type="radio"]', function( event )
		{
			// Toggle label `active` class.

			var $label = $( this ).closest( 'label' );

			if ( $label.length ) 
			{
				if ( $( this ).is( ':checked' ) ) 
				{
					$label.addClass( 'active' );
				}

				else
				{
					$label.removeClass( 'active' );
				}
			}
		});

		// .autoload field change
		this.$elem.on( 'change', 'form :input.autoload', function( event )
		{
			// Load
			_this.load();
		});

		// Form submit
		this.$elem.on( 'submit', 'form', function( event )
		{
			event.preventDefault();

			// Load
			_this.load();
		});

		this.$elem.trigger( 'postLoader.init', this );
	}

	Plugin.defaultOptions = {};

	Plugin.prototype.$elem   = null;
	Plugin.prototype.options = {};

	/**
	 * Load
	 */
	Plugin.prototype.load = function()
	{
		// Get form data

		var data = this.$elem.find( 'form' ).serialize();

		// Set loading

		this.$elem.addClass( 'loading' );

		// Disable fields

		var $fields = this.$elem.find( 'form :input:not([disabled])' );

		$fields.prop( 'disabled', true );

		// Ajax

		$.ajax(
		{
			url : theme.ajaxurl,
			method : 'POST',
			data : data,
			context : this,

			beforeSend : function( jqXHR, settings )
			{
				this.$elem.trigger( 'postLoader.loadBeforeSend', [ jqXHR, settings ] );
			},

			success : function( data, textStatus, jqXHR )
			{
				console.log( 'success', data );

				// Set result html
				this.$elem.find( '.post-loader-result' )
					.html( data.content );

				this.$elem.trigger( 'postLoader.loadSuccess', [ data, textStatus, jqXHR ] );
			},

			error : function( jqXHR, textStatus, errorThrown )
			{
				console.warn( 'error', errorThrown );

				this.$elem.trigger( 'postLoader.loadError', [ jqXHR, textStatus, errorThrown ] );
			},

			complete : function( jqXHR, textStatus )
			{
				// Enable fields
				$fields.prop( 'disabled', false );

				// Unset loading
				this.$elem.removeClass( 'loading' );

				this.$elem.trigger( 'postLoader.loadComplete',[ jqXHR, textStatus ] );
			}
		})
	};

	/**
	 * jQuery Plugin
	 */
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

	// Assign to global scope
	window.postLoader = Plugin;

})( jQuery );