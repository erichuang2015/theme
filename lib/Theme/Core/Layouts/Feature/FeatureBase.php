<?php 

namespace Theme\Core\Layouts\Feature;

class FeatureBase
{
	public $id = null;

	public function __construct( $id )
	{
		$this->id = $id;

		add_action( 'theme/layout', array( $this, 'layout' ) );
		add_filter( 'theme/layout_html_attributes', array( $this, 'layout_html_attributes' ), 10, 3 );
	}

	public function layout( $layout )
	{
		
	}

	public function layout_html_attributes( $atts, $layout, $instance )
	{
		return $atts;
	}
}
