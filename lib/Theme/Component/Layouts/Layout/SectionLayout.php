<?php

namespace Theme\Component\Layouts\Layout;

class SectionLayout extends \Theme\Core\Layouts\Layout\LayoutBase
{
	public function __construct()
	{
		parent::__construct( 'section', __( 'Section', 'theme' ), array
		(
			'features' => array
			( 
				'id',
				'class',
			),
		));

		// Section
		$this->add_field( array
		(
			'key'           => "{$this->name}_post_id",
			'label'         => __( 'Section', 'theme' ),
			'name'          => 'post_id',
			'type'          => 'post_object',
			'post_type'     => array( THEME_SECTIONS_POST_TYPE ),
			'default_value' => '',
			'instructions'  => '',
			'required'      => true,
			'return_format' => 'id',
			'multiple'      => false,
			'ui'            => true,
		));
	}

	public function render( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->get_defaults() );

		// Check if section has layouts
		if ( theme_has_layouts( $instance['post_id'] ) ) 
		{
			// Render layouts
			theme_render_layouts( $instance['post_id'] );

			return;
		}

		$the_query = new \WP_Query( array
		(
			'p'         => $instance['post_id'],
			'post_type' => THEME_SECTIONS_POST_TYPE
		));

		if ( ! $the_query->have_posts() ) 
		{
			return;
		}

		echo $args['before'];

		while ( $the_query->have_posts() ) 
		{
			$the_query->the_post();

			if ( ! theme_has_container() ) 
			{
				echo '<div class="container">' . "\n";
			}

			get_template_part( 'template-parts/content', get_post_type() );
		
			if ( ! theme_has_container() ) 
			{
				echo '</div>' . "\n";
			}
		}

		wp_reset_postdata();

		echo $args['after'];
	}
}
