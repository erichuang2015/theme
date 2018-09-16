<?php 

namespace Theme\Component\Layouts\Feature;

class ClassFeature extends \Theme\Core\Layouts\Feature\FeatureBase
{
	public function __construct()
	{
		parent::__construct( 'class' );
	}

	public function layout( $layout )
	{
		// Check Support
		if ( ! $layout->supports( $this->id ) ) 
		{
			return;
		}

		// Add field
		$layout->add_field( array
		(
			'key'           => "{$layout->name}_class",
			'label'         => __( 'Class', 'theme' ),
			'name'          => 'class',
			'type'          => 'text',
			'default_value' => '',
			'instructions'  => __( 'Extra CSS classes.' ),
			'required'      => false,
			'category'      => 'attributes',
			'order'         => 10,
		));
	}

	public function layout_html_attributes( $atts, $layout, $instance )
	{
		// Check Support
		if ( $layout->supports( $this->id ) ) 
		{
			if ( isset( $instance['class'] ) ) 
			{
				// Set Attribute
				$classes = array();

				foreach ( explode( ' ', $instance['class'] ) as $class ) 
				{
					$class = sanitize_html_class( $class );

					if ( $class ) 
					{
						$classes[ $class ] = $class;
					}
				}

				if ( $classes ) 
				{
					$atts['class'] .= ' ' . implode( ' ', $classes );
				}
			}
		}

		return $atts;
	}
}
