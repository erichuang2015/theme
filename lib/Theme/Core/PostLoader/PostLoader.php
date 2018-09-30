<?php 

namespace Theme\Core\PostLoader;

class PostLoader
{
	public $id = null;

	/**
	 * Construct
	 */
	public function __construct( $id )
	{
		$this->id = $id;

		add_action( 'wp_ajax_theme_post_loader_process'       , array( $this, 'process' ) );
		add_action( 'wp_ajax_nopriv_theme_post_loader_process', array( $this, 'process' ) );
	}

	/**
	 * Render
	 */
	public function render()
	{
		echo '<div id="' . esc_attr( $this->id ) . '-post-loader" class="post-loader">';

		$this->inside();

		echo '</div>';
	}

	/**
	 * Inside
	 */
	public function inside()
	{
		$this->form();
		$this->content();
	}

	/**
	 * Form
	 */
	public function form()
	{
		echo '<form class="post-loader-form" method="post">';

		$this->form_inside();

		echo '</form>';
	}

	/**
	 * Form Inside
	 */
	public function form_inside()
	{
		$this->settings_fields();
	}

	/**
	 * Content
	 */
	public function content()
	{
		echo '<div class="post-loader-content">';

		$this->content_inside();

		echo '</div>';
	}

	/**
	 * Content Inside
	 *
	 * @param WP_Query $query
	 */
	public function content_inside( &$query = null )
	{
		/**
		 * Post data
		 */

		$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;

		/**
		 * WP Query
		 */

		$query_args = array
		(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'paged'       => $paged,
		);

		$this->query_args( $query_args );

		// Define $query.
		$query = new \WP_Query( $query_args );

		/**
		 * Output
		 */

		$this->result( $query );
	}

	/**
	 * Query Arguments
	 *
	 * @param array $query_args
	 */
	public function query_args( &$query_args = array() )
	{
		
	}

	/**
	 * Result
	 *
	 * @param WP_Query $query
	 */
	public function result( $query )
	{
		// Posts found
		if ( $query->have_posts() ) 
		{
			// List posts
			$this->list_posts( $query );

			// Pagination
			$this->pagination( $query );
		}

		// No posts
		else
		{
			// Output message
			$this->no_posts_message( $query, '<div class="alert alert-warning">', '</div>' );
		}
	}

	/**
	 * No Posts Message
	 *
	 * @param WP_Query $query
	 * @param string $before
	 * @param string $after
	 */
	public function no_posts_message( $query, $before = '', $after = '' )
	{
		/**
		 * Get post types name
		 */

		$post_types = array();

		if ( $query->get( 'post_type' ) ) 
		{
			foreach ( (array) $query->get( 'post_type' ) as $post_type ) 
			{
				$post_type = get_post_type_object( $post_type );

				if ( $post_type ) 
				{
					$post_types[ $post_type->name ] = strtolower( $post_type->labels->name );
				}
			}

			$post_types = array_values( $post_types );
		}

		/**
		 * Message
		 */

		// One post type
		if ( count( $post_types ) == 1 ) 
		{
			$message = sprintf( __( 'No %s found.', 'theme' ), $post_types[0] );
		}

		// Multiple post types
		elseif ( count( $post_types ) ) 
		{
			$last = array_pop( $post_types );

			$message = sprintf( __( 'No %s or %s found.', 'theme' ), implode( ', ', $post_types ), $last );
		}

		// No post types
		else
		{
			$message = __( 'No items found.', 'theme' );
		}

		// Output
		echo $before . $message . $after;
	}

	/**
	 * List Posts
	 *
	 * @param WP_Query $query
	 * @param array $args
	 */
	public function list_posts( $query, $args = array() )
	{
		/**
		 * Arguments
		 */

		$defaults = array
		(
			'before_posts'  => '<div class="row">',
			'before_post'   => '<div class="col-md-6 col-lg-4">',
			'post_template' => 'template-parts/card.php',
			'after_post'    => '</div>',
			'after_posts'   => '</div>',
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * Check posts
		 */

		if ( ! $query->have_posts() ) 
		{
			return;
		}

		/**
		 * Output
		 */

		echo $args['before_posts'];

		// The loop
		while ( $query->have_posts() ) 
		{
			$query->the_post();

			echo $args['before_post'];

			// Include post template
			locate_template( $args['post_template'], true, false );

			echo $args['after_post'];
		}

		echo $args['after_posts'];

		// Reset post data
		wp_reset_postdata();
	}

	/**
	 * Pagination
	 *
	 * @param WP_Query $query
	 */
	public function pagination( $query )
	{
		// Load more
		$this->load_more( $query );

		// pagination
		//theme_posts_ajax_pagination( $query );
	}

	/**
	 * Load More
	 *
	 * @param WP_Query $query
	 */
	public function load_more( $query )
	{
		// Check if more
		if ( ! $this->has_more( $query ) ) 
		{
			return;
		}

		echo '<div class="post-loader-more">';

		$this->load_more_button( $query );

		echo '</div>';
	}

	/**
	 * Load More Button
	 *
	 * @param WP_Query $query
	 */
	public function load_more_button( $query )
	{
		$page = (int) $query->get( 'paged' ) + 1;

		printf( '<button type="button" class="post-loader-more-button" data-page="%d">%s</button>', 
			$page, esc_html__( 'Load More', 'theme' ) );
	}

	/**
	 * Has More
	 *
	 * @param WP_Query $query
	 *
	 * @return bool
	 */
	public function has_more( $query )
	{
		$count = $this->get_more_count( $query );

		return $count['posts'] ? true : false; 
	}

	/**
	 * Get More count
	 *
	 * @param WP_Query $query
	 *
	 * @return array
	 */
	public function get_more_count( $query )
	{
		$paged          = intval( $query->get( 'paged' ) );
		$found_posts    = intval( $query->found_posts );
		$posts_per_page = intval( $query->get( 'posts_per_page' ) );

		$posts = $found_posts - ( $posts_per_page * $paged );
		$pages = $query->max_num_pages - $paged;

		$posts = max( $posts, 0 );
		$pages = max( $pages, 0 );

		return compact( 'posts', 'pages' );
	}

	/**
	 * Navigation
	 *
	 * @param string $taxonomy
	 * @param array $args
	 */
	public function nav( $taxonomy, $args = array() )
	{
		/**
		 * Arguments
		 */

		$defaults = array
		(
			'before'       => '<nav class="%1$s">',
			'title'        => null,
			'before_items' => '',
			'before_item'  => '',
			'after_item'   => '',
			'after_items'  => '',
			'after'        => '</nav>',
			
			'type'         => 'checkbox',
			'radio_all'    => __( 'Show all', 'theme' ),
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * Terms
		 */

		$taxonomy = get_taxonomy( $taxonomy );

		if ( ! $taxonomy ) 
		{
			return;
		}

		$terms = get_terms( array
		(
			'taxonomy' => $taxonomy->name,
		));

		if ( ! $terms || is_wp_error( $terms ) ) 
		{
			return;
		}

		/**
		 * Output
		 */

		if ( is_null( $args['title'] ) ) 
		{
			$title = $taxonomy->labels->name;
		}

		else
		{
			$title = $args['title'];
		}

		$field_name = "terms[{$taxonomy->name}][]";

		printf( $args['before'], 'post-loader-nav' );

		if ( $title ) 
		{
			printf( '<h3>%s</h3>', esc_html( $title ) );
		}

		echo $args['before_items'];

		// Radio All
		if ( $args['type'] == 'radio' && $args['radio_all'] ) 
		{
			echo $args['before_item'];

			$this->radio_all( $args['radio_all'], $field_name );

			echo $args['after_item'];
		}

		// Terms
		foreach ( $terms as $term ) 
		{
			echo $args['before_item'];

			$this->nav_item( $term, $field_name, $args['type'] );

			echo $args['after_item'];
		}

		echo $args['after_items'];

		echo $args['after'];
	}

	/**
	 * Navigation Item
	 *
	 * @param WP_Term $term
	 * @param string $field_name
	 * @param string $type
	 */
	public function nav_item( $term, $field_name, $type = 'checkbox' )
	{
		$type = $type == 'radio' ? 'radio' : 'checkbox';

		printf( '<label><input type="%s" class="autoload" name="%s" value="%d"> %s</label> ', 
			esc_attr( $type ), esc_attr( $field_name ), $term->term_id, esc_html( $term->name ) );
	}

	/**
	 * Radio All
	 *
	 * @param string $text
	 * @param string $field_name
	 */
	public function radio_all( $text, $field_name )
	{
		printf( '<label class="active"><input type="radio" class="autoload" name="%s" value="%d" checked> %s</label> ', 
			esc_attr( $field_name ), 0, esc_html( $text ) );
	}

	/**
	 * Apply Navigation
	 *
	 * @param string $taxonomy
	 * @param array $query_args
	 */
	public function apply_nav( $taxonomy, &$query_args )
	{
		// Check post data
		if ( ! isset( $_POST['terms'][ $taxonomy ] ) ) 
		{
			return;
		}

		// Get terms
		$terms = (array) $_POST['terms'][ $taxonomy ];

		// Remove empty 'show all' value
		$terms = array_filter( $terms );

		// Check terms
		if ( ! $terms ) 
		{
			return;
		}

		// Apply
		$query_args['tax_query'][] = array
		(
			'taxonomy' => $taxonomy,
			'field'    => 'term_id',
			'terms'    => array_map( 'intval', $terms ),
			'operator' => 'IN',
		);
	}

	/**
	 * Process
	 */
	public function process()
	{
		/**
		 * Check
		 */

		// Ajax
		if ( ! wp_doing_ajax() ) 
		{
			return;
		}

		// Referer and nonce
		check_ajax_referer( 'post_loader', THEME_NONCE_NAME );

		// loader
		if ( $this->id != $_POST['loader'] ) 
		{
			return;
		}

		/**
		 * Get content and WP Query object
		 */

		ob_start();

		$this->content_inside( $query );

		$content = ob_get_clean();

		/**
		 * Response
		 */

		wp_send_json( array
		(
			'content'       => $content,
			'found_posts'   => intval( $query->found_posts ),
			'post_count'    => $query->post_count,
			'max_num_pages' => $query->max_num_pages,
			'paged'         => $query->get( 'paged' ),
		));
	}

	/**
	 * Settings Fields
	 */
	public function settings_fields()
	{
		wp_nonce_field( 'post_loader', THEME_NONCE_NAME );

		echo '<input type="hidden" name="action" value="theme_post_loader_process">';
		echo '<input type="hidden" name="loader" value="' . esc_attr( $this->id ) . '">';
		echo '<input type="hidden" name="paged" value="1">';
	}
}
