<?php 

namespace Theme\Core\Layouts\Layout;

class LayoutBase extends \Theme\Core\Supportable
{
	public $name  = null;
	public $title = null;
	public $class = null;

	public function __construct( $name, $title, $args = array() )
	{
		parent::__construct();

		$defaults = array
		(
			'features' => array(),
			'class'    => ''
		);

		$args = wp_parse_args( $args, $defaults );

		$this->name  = $name;
		$this->title = $title;
		$this->class = $args['class'];

		$this->add_support( $args['features'] );

		do_action( 'theme/layout', $this );
	}

	public function get_defaults()
	{
		$defaults = array();

		foreach ( $this->fields as $field ) 
		{
			$defaults[ $field['name'] ] = $field['default_value'];
		}

		return $defaults;
	}

	public function add_field( $args )
	{
		$defaults = array
		(
			'key'           => '',
			'label'         => '',
			'name'          => '',
			'type'          => 'text',
			'default_value' => '',
			'category'      => 'default',
			'order'         => 0,
		);

		$field = wp_parse_args( $args, $defaults );

		$this->fields[ $field['key'] ] = $field;
	}

	public function render( $args, $instance )
	{
		
	}

	public function enqueue_scripts()
	{
		
	}
}
