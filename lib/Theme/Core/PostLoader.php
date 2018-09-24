<?php 

namespace Theme\Core;

class PostLoader
{
	public $id = null;

	public function __construct( $id )
	{
		$this->id = $id;

		add_action( 'wp_ajax_theme_post_loader_process'		  , array( $this, 'process' ) );
		add_action( 'wp_ajax_nopriv_theme_post_loader_process', array( $this, 'process' ) );
	}

	public function render( $include_content = true )
	{
		$html_id = "{$this->id}-post-loader";
		$target  = "{$this->id}-post-loader-content";

		?>

		<div id="<?php echo esc_attr( $html_id ); ?>" class="post-loader" data-target="#<?php echo esc_attr( $target ); ?>">

			<form class="post-loader-form" method="post">
				
				<?php wp_nonce_field( 'post_loader', THEME_NONCE_NAME ); ?>

				<input type="hidden" name="action" value="theme_post_loader_process">
				<input type="hidden" name="loader" value="<?php echo esc_attr( $this->id ); ?>">
				<input type="hidden" name="paged" value="1">

				<?php $this->form(); ?>

			</form>

			<?php  

				if ( $include_content ) :

					$this->content();

				endif;

			?>

		</div><!-- .post-loader -->

		<?php
	}

	public function form()
	{
		do_action( "theme_post_loader_form/loader={$this->id}", $this );
	}

	public function content()
	{
		$html_id = "{$this->id}-post-loader-content";

		?>

		<div id="<?php echo esc_attr( $html_id ); ?>" class="post-loader-content">
			<?php $this->result(); ?>
		</div>

		<?php
	}

	public function result( &$query = null )
	{
		$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;

		$query_args = array
		(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'paged'       => $paged,
		);

		$query_args = apply_filters( "theme_post_loader_query_args/loader={$this->id}", $query_args, $this );

		$query = new \WP_Query( $query_args );

		do_action( "theme_post_loader_result/loader={$this->id}", $query, $this );
	}

	public function process()
	{
		// Check ajax
		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) 
		{
			return;
		}

		// Check referer
		check_ajax_referer( 'post_loader', THEME_NONCE_NAME );

		// Check loader
		if ( $this->id != $_POST['loader'] ) 
		{
			return;
		}

		// Get content and WP Query object

		ob_start();

		$this->result( $query );

		$content = ob_get_clean();

		// Response

		wp_send_json( array
		(
			'content'       => $content,
			'found_posts'   => $query->found_posts,
			'post_count'    => $query->post_count,
			'max_num_pages' => $query->max_num_pages,
			'paged'         => $query->get( 'paged' ),
		));
	}
}
