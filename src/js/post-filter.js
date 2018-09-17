(function( $ )
{
	"use strict";

	function Plugin( elem, options )
	{
		this.$elem   = $( elem );
		this.options = $.extend( {}, Plugin.defaultOptions, options );

		$( document ).trigger( 'postFilter.init', this );

		this.load();
	}

	Plugin.defaultOptions = {};

	Plugin.prototype.$elem   = null;
	Plugin.prototype.options = {};

	Plugin.prototype.load = function( page )
	{
		// Check page
		if ( typeof page !== 'undefined' ) 
		{
			// Set page
			this.$elem.find( 'form :input[name="paged"]' ).val( page );
		};

		// Get form data

		var data = this.$elem.find( 'form' ).serialize();

		// Disable fields

		var $fields = this.$elem.find( 'form :input:not([disabled])' );

		$fields.prop( 'disabled', true );

		// Set Loading

		this.$elem.addClass( 'loading' );

		// Ajax

		$.ajax(
		{
			method : 'POST',
			url : pandafish.ajaxurl,
			data : data,
			context : this,

			beforeSend : function()
			{
				this.$elem.trigger( 'postFilter.loadBeforeSend' );
			},

			success : function( response )
			{
				console.log( response );

				this.$elem.find( '.post-filter-result' )
					.html( response.content );

				this.$elem.trigger( 'postFilter.loadSuccess' );
			},

			error : function()
			{
				this.$elem.trigger( 'postFilter.loadError' );
			},

			complete: function()
			{
				// Enable fields
				$fields.prop( 'disabled', false );

				// Unset loading
				this.$elem.removeClass( 'loading' );

				this.$elem.trigger( 'postFilter.loadComplete' );
			}    
		})
	};

	$.fn.postFilter = function( options )
	{
		return this.each( function()
		{
			if ( typeof $( this ).data( 'postFilter' ) === 'undefined' ) 
			{
				var instance = new Plugin( this, options );

				$( this ).data( 'postFilter', instance );
			}
		});
	};

	window.postFilter = Plugin;

})( jQuery );

(function( $ )
{
	$( document ).on( 'ready', function()
	{
		$( '.post-filter' ).postFilter();
	});

})( jQuery );
