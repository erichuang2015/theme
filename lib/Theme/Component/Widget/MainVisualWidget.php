<?php

namespace Theme\Component\Widget;

class MainVisualWidget extends \WP_Widget
{
	public function __construct()
	{
		parent::__construct( 'theme_main_visual', __( 'Main Visual', 'theme' ), array
		( 
			'description' => esc_html__( 'Displays post/page main visual.', 'theme' ),
			'classname'   => 'main-visual-widget',
		));
	}

	public function widget( $args, $instance )
	{
		if ( ! theme_has_main_visual() ) 
		{
			return;
		}
		
		echo $args['before_widget'];

		theme_main_visual();

		echo $args['after_widget'];
	}
	
	public function form( $instance ) 
	{
		printf( '<p>%s</p>', __( 'Settings are available on post/page edit screens.', 'theme' ) );
	}
}
