<?php 

namespace Theme\Core\Layouts\Feature;

class FeatureManager
{
	static private $instance = null;

	static public function get_instance()
	{
		if ( ! self::$instance ) 
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	protected $features = array();

	public function __construct()
	{
		
	}

	public function register_feature( $feature )
	{
		if ( ! $feature instanceof FeatureBase ) 
		{
			$feature = new $feature();
		}

		$this->features[ $feature->id ] = $feature;
	}

	public function unregister_feature( $id )
	{
		unset( $this->features[ $id ] );
	}

	public function get_features()
	{
		return $this->features;
	}

	public function get_feature( $id )
	{
		if ( isset( $this->features[ $id ] ) ) 
		{
			return $this->features[ $id ];
		}

		return null;
	}
}
