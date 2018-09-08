(function( $ ) 
{
    "use strict";

    tinymce.create( 'tinymce.plugins.theme', 
    {
        init : function( editor, url ) 
        {
            /**
             * Add 'Button' button
             */
            editor.addButton( 'button', 
            {
                text: 'Button',
                icon: false,
                selectedButton : null,
                onclick: function() 
                {
                    var _this = this;

                    var data = 
                    {
                        text : '',
                        link : '',
                        link_tab : false,
                        type : 'primary',
                        outline : false,
                        size : 'md',
                        toggle : ''
                    };

                    var node = editor.selection.getNode();

                    if ( node.nodeName == 'A' ) 
                    {
                        this.selectedButton = node;

                        var typeMatches = $( node ).attr( 'class' ).match( /(?:^| )btn-(outline-)?(primary|secondary|success|warning|danger|info|light|dark|link)(?: |$)/ );
                        var sizeMatches = $( node ).attr( 'class' ).match( /(?:^| )btn-(sm|md|lg)(?: |$)/ );

                        data.text     = $( node ).text() || '';
                        data.link     = $( node ).attr( 'href' ) || '';
                        data.link_tab = $( node ).attr( 'target' ) == '_blank' ? true : false;

                        if ( typeMatches ) 
                        {
                            data.outline = typeof typeMatches[1] !== 'undefined' ? true : false;
                            data.type    = typeMatches[2];
                        }

                        if ( sizeMatches ) 
                        {
                            data.size = sizeMatches[1];
                        }

                        data.toggle = $( node ).attr( 'data-toggle' ) || '';
                    }   

                    else
                    {
                        this.selectedButton = null;

                        data.text = editor.selection.getContent( { format : 'text' } );
                    }

                    // Open window.
                    editor.windowManager.open(
                    {
                        title: 'Insert/edit Button',
                        body: 
                        [
                            { 
                                name: 'text' , 
                                label: 'Text', 
                                type: 'textbox',
                                value : data.text,
                            },

                            { 
                                name: 'link' , 
                                label: 'Link', 
                                type: 'textbox',
                                value : data.link,
                            },

                            { 
                                name: 'link_tab' , 
                                label: 'Open link in a new tab', 
                                type: 'checkbox',
                                checked : data.link_tab,
                            },

                            { 
                                name: 'type' , 
                                label: 'Type', 
                                type: 'listbox',
                                values: 
                                [
                                    { text: 'Primary', value: 'primary' },
                                    { text: 'Secondary', value: 'secondary' },
                                    { text: 'Success', value: 'success' },
                                    { text: 'Danger', value: 'danger' },
                                    { text: 'Warning', value: 'warning' },
                                    { text: 'Info', value: 'info' },
                                    { text: 'Light', value: 'light' },
                                    { text: 'Dark', value: 'dark' },
                                    { text: 'Link', value: 'link' },
                                ],
                                value : data.type,
                            },

                            { 
                                name: 'outline' , 
                                label: 'Outline', 
                                type: 'checkbox',
                                checked : data.outline,
                            },

                            { 
                                name: 'size' , 
                                label: 'Size', 
                                type: 'listbox',
                                values: 
                                [
                                    { text: 'Small', value: 'sm' },
                                    { text: 'Medium', value: 'md' },
                                    { text: 'Large', value: 'lg' },
                                ],
                                value : data.size,
                            },
                            { 
                                name: 'toggle' , 
                                label: 'Toggle', 
                                type: 'listbox',
                                values: 
                                [
                                    { text: '- None -', value: '' },
                                    { text: 'Modal', value: 'modal' },
                                    { text: 'Collapse', value: 'collapse' },
                                ],
                                value : data.toggle,
                            },
                        ],

                        // Form submit.
                        onsubmit: function( event ) 
                        {
                            var data = $.extend( {}, event.data );

                            var $button, update = false;

                            if ( _this.selectedButton ) 
                            {
                                $button = $( _this.selectedButton ).attr( 'class', '' );
                            }

                            else
                            {
                                $button = $( '<a></a>' )
                            }

                            $button.addClass( 'btn' );

                            /**
                             * Attributes
                             */

                            // Text
                            if ( data.text ) 
                            {
                                $button.text( data.text );
                            }

                            // Link
                            if ( data.link ) 
                            {
                                $button.attr( 'href', data.link );
                            }

                            // Link Tab
                            if ( data.link_tab ) 
                            {
                                $button.attr( 'target', '_blank' );
                            }

                            // Type
                            if ( data.type ) 
                            {
                                if ( data.outline ) 
                                {
                                    $button.addClass( 'btn-outline-' + data.type );
                                }

                                else
                                {
                                    $button.addClass( 'btn-' + data.type );
                                }
                            }

                            // Size
                            if ( data.size ) 
                            {
                                $button.addClass( 'btn-' + data.size );
                            }

                            // Toggle
                            if ( data.toggle ) 
                            {
                                $button.attr( 'data-toggle', data.toggle );
                            }

                            /**
                             * Insert into editor
                             */

                             if ( ! _this.selectedButton ) 
                             {
                                editor.insertContent( $( '<div></div>' ).append( $button ).html() );
                             }
                        }
                    });
                }
            });
        },
    });

    // Register plugin
    tinymce.PluginManager.add( 'theme', tinymce.plugins.theme );

})( jQuery );