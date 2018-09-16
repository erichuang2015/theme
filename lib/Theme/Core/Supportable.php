<?php 

namespace Theme\core;

class Supportable
{
	protected $features = array();

	public function __construct()
	{
		
	}

	public function add_support( $feature )
	{
		if ( func_num_args() > 1 ) 
		{
			$features = func_get_args();
		}

		else
		{
			$features = (array) $feature;
		}

		foreach ( $features as $feature ) 
		{
			$this->features[ $feature ] = true;
		}
	}

	public function remove_support( $feature )
	{
		unset( $this->features[ $feature ] );
	}

	public function supports( $feature )
	{
		return isset( $this->features[ $feature ] );
	}
}
