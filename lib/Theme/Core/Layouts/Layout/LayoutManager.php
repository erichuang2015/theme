<?php 

namespace Theme\Core\Layouts\Layout;

class LayoutManager
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

	protected $layouts = array();

	public function __construct()
	{
		
	}

	public function init()
	{
		add_action( 'init'				, array( $this, 'layout_settings_init' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'auto_enqueue_layouts_scripts' ) );
		add_filter( 'the_content'		, array( $this, 'auto_render_layouts' ) );
	}

	public function register_layout( $layout )
	{
		if ( ! $layout instanceof LayoutBase ) 
		{
			$layout = new $layout();
		}

		$this->layouts[ $layout->name ] = $layout;
	}

	public function unregister_layout( $name )
	{
		unset( $this->layouts[ $name ] );
	}

	public function get_layouts()
	{
		return $this->layouts;
	}

	public function get_layout( $name )
	{
		if ( isset( $this->layouts[ $name ] ) ) 
		{
			return $this->layouts[ $name ];
		}

		return null;
	}

	public function render_layouts( $post_id = 0 )
	{
		// Check dependency
		if ( ! function_exists( 'have_rows' )
		  || ! function_exists( 'the_row' ) 
		  || ! function_exists( 'get_row_layout' )
		  || ! function_exists( 'get_row' ) ) 
		{
			return;
		}

		// Loop layouts
		while ( have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD, $post_id ) ) 
		{
			the_row();

			$this->render_layout( get_row_layout(), get_row( true ) );
		}
	}

	public function auto_render_layouts( $content )
	{
		// Check dependency
		if ( ! function_exists( 'have_rows' ) ) 
		{
			return $content;
		}

		if ( is_main_query() 
		  && in_the_loop() 
		  && ( is_page() || is_single() ) 
		  && have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD ) ) 
		{
			ob_start();

			$this->render_layouts();

			$content = ob_get_clean();
		}

		return $content;
	}

	public function render_layout( $name, $instance )
	{
		// Layout

		$layout = $this->get_layout( $name );

		if ( ! $layout ) 
		{
			return;
		}

		// Wrapper

		$wrapper = array
		(
			'class' => 'layout',
		);

		if ( $layout->class ) 
		{
			$wrapper['class'] .= " {$layout->class}";
		}

		else
		{
			$wrapper['class'] .= " {$layout->name}-layout";
		}

		$wrapper = apply_filters( 'theme/layout_html_attributes', $wrapper, $layout, (array) $instance );

		$wrapper = array_filter( $wrapper );

		// Args

		$args = array
		(
			'before' => sprintf( '<div%s>', theme_esc_attr( $wrapper ) ),
			'after'  => '</div>',
		);

		$args = apply_filters( 'theme/layout_args', $args, $layout, (array) $instance, $wrapper );

		// Render

		$layout->render( $args, $instance );
	}

	public function has_layouts( $post_id = 0 )
	{
		// Check dependency
		if ( ! function_exists( 'have_rows' ) ) 
		{
			return false;
		}

		return have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD, $post_id );
	}

	public function has_layout( $name, $post_id = 0 )
	{
		// Check dependency
		if ( ! function_exists( 'have_rows' )
		  || ! function_exists( 'the_row' ) 
		  || ! function_exists( 'get_row_layout' ) ) 
		{
			return false;
		}

		$return = false;

		// Loop layouts
		while ( have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD, $post_id ) ) 
		{
			the_row();

			if ( get_row_layout() == $name ) 
			{
				$return = true;

				// Don't break loop
			}
		}

		return $return;
	}

	public function enqueue_layout_scripts( $name )
	{
		static $enqueued = array();

		$layout = $this->get_layout( $name );

		if ( ! $layout ) 
		{
			return;
		}

		if ( isset( $enqueued[ $layout->name ] ) ) 
		{
			return;
		}

		$layout->enqueue_scripts();

		$enqueued[ $layout->name ] = true;
	}

	public function auto_enqueue_layouts_scripts()
	{
		// Check dependency
		if ( ! function_exists( 'have_rows' )
		  || ! function_exists( 'the_row' ) 
		  || ! function_exists( 'get_row_layout' ) ) 
		{
			return;
		}

		// Loop layouts
		while ( have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD, get_queried_object() ) ) 
		{
			the_row();

			// Enqueue layout scripts
			$this->enqueue_layout_scripts( get_row_layout() );
		}
	}

	public function layout_settings_init()
	{
		// Check dependency
		if ( ! function_exists( 'acf_add_local_field_group' ) ) 
		{
			return;
		}

		/**
		 * Field Categories
		 */

		$categories = apply_filters( 'theme/layouts_field_categories', array
		(
			'default'    => array( 'title' => __( 'General' )   , 'order' => 0 ),
			'layout'     => array( 'title' => __( 'Layout' )    , 'order' => 1000 ),
			'attributes' => array( 'title' => __( 'Attributes' ), 'order' => 2000 ),
		));

		/**
		 * Location
		 */

		$location = array();

		foreach ( get_post_types() as $post_type ) 
		{
			if ( post_type_supports( $post_type, THEME_LAYOUTS_POST_TYPE_FEATURE ) ) 
			{
				$location[] = array
				(
					array( 'param' => 'post_type', 'operator' => '==', 'value' => $post_type ),
				);
			}
		}

		/**
		 * Layouts
		 */

		$layouts = array();
		
		foreach ( $this->get_layouts() as $instance ) 
		{
			$fields = array();

			if ( $categories && $instance->fields ) 
			{
				// Loop categories
				foreach ( $categories as $cat_id => $category ) 
				{
					// Get category fields
					$cat_fields = wp_filter_object_list( $instance->fields, array( 'category' => $cat_id ) );

					// Check category fields
					if ( ! $cat_fields ) 
					{
						continue;
					}

					// Loop category fields
					foreach ( $cat_fields as &$cat_field ) 
					{
						// Update order
						$cat_field['order'] = $category['order'] + $cat_field['order'] + 1;
					}

					// Add category tab field
					$tab = array
					(
						'key'   => "{$instance->name}_category_{$cat_id}",
						'label' => $category['title'],
						'type'  => 'tab',
						'order' => $category['order'],
					);

					$cat_fields[ $tab['key'] ] = $tab;

					// Add category fields to layout fields
					$fields = array_merge( $fields, $cat_fields );
				}
			}

			$fields = array_values( $fields );

			usort( $fields, 'theme_sort_order' );

			// Add layout

			$layout = array
			(
				'key'        => "layout_{$instance->name}",
				'name'       => $instance->name,
				'label'      => $instance->title,
				'display'    => 'block',
				'sub_fields' => $fields,
			);

			$layouts[ $layout['key'] ] = $layout;
		}

		/**
		 * Field Group
		 */

		$field_group = array
		(
			'key'    => 'theme_flexible_content_field_group',
			'title'  => 'Layouts',
			'fields' => array
			(
				array
				(
					'key'          => 'theme_flexible_content',
					'label'        => '',
					'name'         => THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD,
					'type'         => 'flexible_content',
					'layouts'      => $layouts,
					'button_label' => __( 'Add Layout', 'theme' ),
				),
			),
			'location'              => $location,
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'seamless',
			'label_placement'       => 'top',
			'instruction_placement' => 'field',
			'hide_on_screen'        => array( 'the_content' ),
		);

		$field_group = apply_filters( 'theme/layouts_field_group', $field_group );

		// Register Field Group
		acf_add_local_field_group( $field_group );
	}
}
