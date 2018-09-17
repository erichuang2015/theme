(function( $ )
{
	"use strict";

	function Plugin( elem, options )
	{
		this.$elem   = $( elem );
		this.options = $.extend( {}, Plugin.defaultOptions, options );

		this.$elem.trigger( 'postLoader.init', this );

		this.load();
	}

	Plugin.defaultOptions = {};

	Plugin.prototype.$elem   = null;
	Plugin.prototype.options = {};

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

			beforeSend : function()
			{
				this.$elem.trigger( 'postLoader.beforeSend' );
			},

			success : function( response )
			{
				console.log( response );

				this.$elem.find( '.post-loader-result .post-loader-content' )
					.html( response.content );

				this.$elem.trigger( 'postLoader.success' );
			},

			error : function()
			{
				this.$elem.trigger( 'postLoader.error' );
			},

			complete : function()
			{
				// Enable fields
				$fields.prop( 'disabled', false );

				// Unset loading
				this.$elem.removeClass( 'loading' );

				this.$elem.trigger( 'postLoader.complete' );
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

	window.postLoader = Plugin;

})( jQuery );