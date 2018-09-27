<?php 

namespace Theme\Core\PostLoader;

class PostLoader
{
	public $id = null;

	protected $data = array();

	public function __construct( $id, $args )
	{
		// Arguments

		$defaults = array
		(
			'before_posts'  => '',
			'before_post'   => '',
			'post_template' => 'template-parts/card.php',
			'after_post'    => '',
			'after_posts'   => '',
			'query_args'    => array(),
		);

		$args = wp_parse_args( $args, $defaults );
		$args = apply_filters( "theme_post_loader_args/loader={$this->id}", $args, $this );

		//

		$this->id   = $id;
		$this->data = $args;

		add_action( 'wp_ajax_theme_post_loader_process'		  , array( $this, 'process' ) );
		add_action( 'wp_ajax_nopriv_theme_post_loader_process', array( $this, 'process' ) );
	}

	public function __get( $key )
	{
		if ( array_key_exists( $key, $this->data ) ) 
		{
			return $this->data[ $key ];
		}

		return null;
	}

	/**
	 * Render
	 *
	 * Render the Post Loader.
	 */
	public function render()
	{
		$html_id = "{$this->id}-post-loader";

		?>

		<div id="<?php echo esc_attr( $html_id ); ?>" class="post-loader">
			<?php $this->inside(); ?>
		</div>

		<?php
	}

	/**
	 * Inside
	 *
	 * Render the main elements.
	 */
	public function inside()
	{
		if ( has_action( "theme_post_loader_inside/loader={$this->id}" ) ) 
		{
			do_action( "theme_post_loader_inside/loader={$this->id}", $this );
		}

		else
		{
			$this->form();
			$this->content();
		}
	}

	/**
	 * Settings Fields
	 *
	 * Render required fields for use inside the form.
	 */
	public function settings_fields()
	{
		wp_nonce_field( 'post_loader', THEME_NONCE_NAME );

		?>

		<input type="hidden" name="action" value="theme_post_loader_process">
		<input type="hidden" name="loader" value="<?php echo esc_attr( $this->id ); ?>">
		<input type="hidden" name="paged" value="1">

		<?php
	}

	/**
	 * Form
	 *
	 * Render the form.
	 */
	public function form()
	{
		?>

		<form class="post-loader-form" method="post">
			
			<?php $this->settings_fields(); ?>

			<?php do_action( "theme_post_loader_form/loader={$this->id}", $this ); ?>

		</form>

		<?php
	}

	/**
	 * Content
	 *
	 * Render the main content.
	 */
	public function content()
	{
		?>

		<div class="post-loader-content">
			<?php $this->result(); ?>
		</div>

		<?php
	}

	/**
	 * Result
	 *
	 * Create WP Query object and output the posts.
	 *
	 * Note: The $query argument needs to be set inside this method.
	 *
	 * @param WP_Query $query
	 */
	public function result( &$query = null )
	{
		/**
		 * Post data
		 */

		$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;

		/**
		 * WP Query
		 */

		$query_args = wp_parse_args( $this->query_args, array
		(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'paged'       => $paged,
		));

		$query_args = apply_filters( "theme_post_loader_query_args/loader={$this->id}", $query_args, $this );

		$query = new \WP_Query( $query_args );

		/**
		 * Output
		 */

		// Custom

		if ( has_action( "theme_post_loader_result/loader={$this->id}" ) ) 
		{
			do_action( "theme_post_loader_result/loader={$this->id}", $query, $this );

			return;
		}

		// Default

		if ( $query->have_posts() ) 
		{
			$this->list_posts( $query );
		}

		else
		{
			$this->no_posts_message( $query, '<div class="alert alert-warning">', '</div>' );
		}
	}

	/**
	 * List Posts
	 *
	 * Shorthand method for rendering posts dynamically.
	 *
	 * @param WP_Query $query
	 */
	public function list_posts( $query )
	{
		// Check posts
		if ( ! $query->have_posts() ) 
		{
			return;
		}

		echo $this->before_posts;

		// The Loop
		while ( $query->have_posts() ) 
		{
			$query->the_post();

			echo $this->before_post;

			// Include post template
			locate_template( $this->post_template, true, false );

			echo $this->after_post;
		}

		echo $this->after_posts;

		// Pagination
		$this->pagination( $query );

		// Reset
		wp_reset_postdata();
	}

	/**
	 * No Posts Message
	 *
	 * Outputs a not-found message based on the post type.
	 *
	 * @param WP_Query $query
	 * @param String   $before The content before the message.
	 * @param String   $after  The content after the message.
	 */
	public function no_posts_message( $query, $before = '', $after = '' )
	{
		// Check posts
		if ( $query->have_posts() ) 
		{
			return;
		}

		/**
		 * Get post type name
		 */

		$post_types = array();

		// Check post type
		if ( $query->get( 'post_type' ) ) 
		{
			// Loop post types
			foreach ( (array) $query->get( 'post_type' ) as $post_type ) 
			{
				// Get object
				$post_type = get_post_type_object( $post_type );

				// Store post type name
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

		// Check post types
		if ( $post_types ) 
		{
			// One post type
			if ( count( $post_types ) == 1 ) 
			{
				$message = sprintf( __( 'No %s found.', 'theme' ), $post_types[0] );
			}

			// Multiple post types
			else
			{
				$last = array_pop( $post_types );

				$message = sprintf( __( 'No %s or %s found.', 'theme' ), implode( ', ', $post_types ), $last );
			}
		}

		else
		{
			// No post types
			$message = __( 'No items found.', 'theme' );
		}

		// Output
		echo $before . $message . $after;
	}

	/**
	 * Pagination
	 *
	 * @param WP_Query $query
	 */
	public function pagination( $query )
	{
		theme_posts_ajax_pagination( $query );
	}

	/**
	 * Process
	 *
	 * Ajax handler
	 */
	public function process()
	{
		// Check if ajax
		if ( ! wp_doing_ajax() ) 
		{
			return;
		}

		// Check referer
		check_ajax_referer( 'post_loader', THEME_NONCE_NAME );

		// Check Loader
		if ( $this->id != $_POST['loader'] ) 
		{
			return;
		}

		// Get result and WP Query object

		ob_start();

		$this->result( $query );

		$result = ob_get_clean();

		// Response
		wp_send_json( array
		(
			'result'        => $result,
			'found_posts'   => intval( $query->found_posts ),
			'post_count'    => $query->post_count,
			'max_num_pages' => $query->max_num_pages,
			'paged'         => $query->get( 'paged' ),
		));
	}
}
