<?php 

namespace Theme\Component\Layouts\Feature;

class IDFeature extends \Theme\Core\Layouts\Feature\FeatureBase
{
	public function __construct()
	{
		parent::__construct( 'id' );
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
			'key'           => "{$layout->name}_id",
			'label'         => __( 'ID', 'theme' ),
			'name'          => 'id',
			'type'          => 'text',
			'default_value' => '',
			'instructions'  => '',
			'required'      => false,
			'category'      => 'attributes',
			'order'         => 0,
		));
	}

	public function layout_html_attributes( $atts, $layout, $instance )
	{
		// Check Support
		if ( $layout->supports( $this->id ) ) 
		{
			// Set attribute
			$value = isset( $instance['id'] ) ? sanitize_title( $instance['id'] ) : '';

			if ( $value ) 
			{
				$atts['id'] = $value;
			}
		}

		return $atts;
	}
}
