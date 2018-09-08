<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

class Theme_Layout
{
	public $name   = null;
	public $title  = null;
	public $fields = array();

	public function __construct( $name, $title )
	{
		$this->name  = $name;
		$this->title = $title;

		do_action( "theme/layout/name=$this->name", $this );
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
		static $order = THEME_LAYOUTS_ORDER_DEFAULT;

		$defaults = array
		(
			'key'           => '',
			'label'         => '',
			'name'          => '',
			'type'          => '',
			'default_value' => '',
			'order'         => $order
		);

		$field = wp_parse_args( $args, $defaults );

		$field = apply_filters( "theme/layout_field/key={$field['key']}", $field, $this );
		$field = apply_filters( "theme/layout_field/layout={$this->name}", $field, $this );
		$field = apply_filters( 'theme/layout_field', $field, $this );

		$this->fields[ $field['key'] ] = $field;

		$order += 10;
	}

	public function render( $args, $instance )
	{
		
	}

	public function enqueue_scripts()
	{
		
	}
}