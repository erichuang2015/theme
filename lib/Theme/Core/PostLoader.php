<?php 

namespace Theme\Core;

/**
 * Post Loader
 */
class PostLoader
{
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

		<div id="<?php echo esc_attr( $html_id ); ?>" class="post-loader">
			<?php $this->inner(); ?>
		</div>

		<script type="text/javascript">
			
			jQuery( document ).on( 'ready', function()
			{
				jQuery( '#<?php echo esc_js( $html_id ); ?>' ).postLoader();
			});

		</script>

		<?php
	}

	/**
	 * Inner
	 */
	public function inner()
	{
		$this->form();
		$this->result();
		$this->progress();
	}

	/**
	 * Form
	 */
	public function form()
	{
		?>

		<form class="post-loader-form" method="post">

			<?php wp_nonce_field( 'post_loader', THEME_NONCE_NAME ); ?>

			<input type="hidden" name="action" value="theme_post_loader_process">
			<input type="hidden" name="loader" value="<?php echo esc_attr( $this->id ); ?>">
			<input type="hidden" name="paged" value="1">

			<?php $this->form_inner(); ?>

		</form>

		<?php
	}

	/**
	 * Form Inner
	 */
	public function form_inner()
	{

	}

	/**
	 * Result
	 */
	public function result()
	{
		?>

		<div class="post-loader-result">
			<?php $this->result_inner(); ?>
		</div>

		<?php
	}

	/**
	 * Result Inner
	 */
	public function result_inner( &$query = null )
	{
		$query = new \WP_Query();
	}

	/**
	 * Progress
	 */
	public function progress()
	{
		?>

		<div class="post-loader-progress">
			<?php $this->progress_inner(); ?>
		</div>

		<?php
	}

	/**
	 * Progress Inner
	 */
	public function progress_inner()
	{
		echo theme_get_icon( 'spinner' );
	}

	/**
	 * Process
	 */
	public function process()
	{
		/**
		 * Check
		 * ---------------------------------------------------------------
		 */

		// Ajax

		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) 
		{
			return;
		}

		// Nonce and referer

		check_ajax_referer( 'post_loader', THEME_NONCE_NAME );

		// Loader

		if ( ! isset( $_POST['loader'] ) || $this->id != $_POST['loader'] ) 
		{
			return; // TODO: return response
		}

		/**
		 * Result
		 * ---------------------------------------------------------------
		 * Get result and WP Query object
		 */

		ob_start();

		$this->result_inner( $query );

		$result = ob_get_clean();

		/**
		 * Response
		 * ---------------------------------------------------------------
		 */

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