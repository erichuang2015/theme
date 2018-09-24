<?php 

namespace Theme\Core\Manager;

use \Theme\Core\PostLoader as PostLoader;

class PostLoaderManager
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
	
	protected $loaders = array();
	
	/**
	 * Create
	 */
	function create_loader( $loader_id, $register = true )
	{
		$loader = new PostLoader( $loader_id );

		if ( $register ) 
		{
			$this->register_loader( $loader );
		}

		return $loader;
	}

	/**
	 * Register
	 */
	function register_loader( $loader )
	{
		if ( ! $loader instanceof PostLoader ) 
		{
			$loader = new $loader();
		}

		$this->loaders[ $loader->id ] = $loader;
	}

	/**
	 * Unregister
	 */
	function unregister_loader( $loader_id )
	{
		unset( $this->loaders[ $loader_id ] );
	}

	/**
	 * Get
	 */
	function get_loader( $loader_id )
	{
		if ( isset( $this->loaders[ $loader_id ] ) ) 
		{
			return $this->loaders[ $loader_id ];
		}

		return null;
	}

	/**
	 * Render
	 */
	function render_loader( $loader_id, $include_content = true )
	{
		$loader = $this->get_loader( $loader_id );

		if ( $loader ) 
		{
			$loader->render( $include_content );
		}
	}

	/**
	 * Render Content
	 */
	function render_loader_content( $loader_id )
	{
		$loader = $this->get_loader( $loader_id );

		if ( $loader ) 
		{
			$loader->content();
		}
	}
}
