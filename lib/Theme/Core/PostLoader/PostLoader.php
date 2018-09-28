<?php 

namespace Theme\Core\PostLoader;

/**
 * Post Loader
 */
class PostLoader
{
	const ACTION_RESULT = 'post_loader';

	public $id = null;

	public function __construct( $id )
	{
		$this->id = $id;

		add_action( 'wp_ajax_theme_post_loader_process'		  , array( $this, 'process' ) );
		add_action( 'wp_ajax_nopriv_theme_post_loader_process', array( $this, 'process' ) );
	}

	/**
	 * Render
	 */
	public function render()
	{
		$html_id = "{$this->id}-post-loader";

		?>

		<div id="<?php echo esc_attr( $html_id ) ?>" class="post-loader">
			<?php $this->inside(); ?>
		</div>

		<?php
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
		?>

		<form class="post-loader-form" method="post">
			<?php $this->settings_fields(); ?>
		</form>

		<?php
	}

	/**
	 * Content
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

		$query_args = array
		(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'paged'       => $paged,
		);

		// Set $query argument (required).
		$query = new \WP_Query( $query_args );

		/**
		 * Output
		 */

		// Posts found
		if ( $query->have_posts() ) 
		{
			// List posts
			theme_list_posts( $query, array
			(
				'before_posts'  => '',
				'before_post'   => '',
				'post_template' => 'template-parts/card.php',
				'after_post'    => '',
				'after_posts'   => '',
			));

			// Pagination
			theme_posts_ajax_pagination( $query );
		}

		// No posts
		else
		{
			// Output message
			theme_no_posts_message( $query, '<div class="alert alert-warning">', '</div>' );
		}
	}

	/**
	 * Process
	 */
	public function process()
	{
		// Check if ajax
		if ( ! wp_doing_ajax() ) 
		{
			return;
		}

		// Check referer
		check_ajax_referer( self::ACTION_RESULT, THEME_NONCE_NAME );

		// Check loader
		if ( $this->id != $_POST['loader'] ) 
		{
			return;
		}

		// Get result and WP Query object.
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

	/**
	 * Settings Fields
	 */
	public function settings_fields()
	{
		wp_nonce_field( self::ACTION_RESULT, THEME_NONCE_NAME );

		?>

		<input type="hidden" name="action" value="theme_post_loader_process">
		<input type="hidden" name="loader" value="<?php echo esc_attr( $this->id ); ?>">
		<input type="hidden" name="paged" value="1">

		<?php
	}
}
