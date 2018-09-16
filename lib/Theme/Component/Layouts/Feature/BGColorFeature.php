<?php 

namespace Theme\Component\Layouts\Feature;

class BGColorFeature extends \Theme\Core\Layouts\Feature\FeatureBase
{
	public function __construct()
	{
		parent::__construct( 'bg_color' );
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
			'key'           => "{$layout->name}_bg_color",
			'label'         => __( 'Background Color', 'theme' ),
			'name'          => 'bg_color',
			'type'          => 'select',
			'choices'       => array
			(
				''            => __( '- Select -', 'theme' ),
				'primary'     => __( 'Primary', 'theme' ),
				'secondary'   => __( 'Secondary', 'theme' ),
				'dark'        => __( 'Dark', 'theme' ),
				'light'       => __( 'Light', 'theme' ),
				'white'       => __( 'White', 'theme' ),
				'transparent' => __( 'Transparent', 'theme' ),
			),
			'default_value' => '',
			'instructions'  => '',
			'required'      => false,
			'category'      => 'layout',
			'order'         => 10,
		));
	}

	public function layout_html_attributes( $atts, $layout, $instance )
	{
		// Check Support
		if ( $layout->supports( $this->id ) ) 
		{
			// Set attribute
			$value = isset( $instance['bg_color'] ) ? sanitize_html_class( $instance['bg_color'] ) : '';

			if ( $value ) 
			{
				$atts['class'] .= " bg-$value";
			}
		}

		return $atts;
	}
}
