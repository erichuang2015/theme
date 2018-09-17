<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Post Loader
 */

/**
 * Render
 */
function theme_post_loader( $context )
{
	$id = "$context-post-loader";

	$options = array();

	?>

	<div id="<?php echo esc_attr( $id ); ?>" class="post-loader">
		
		<form class="post-loader-form" method="post">
			
			<?php wp_nonce_field( 'post_loader', THEME_NONCE_NAME ); ?>
			<input type="hidden" name="action" value="theme_post_loader_process">
			<input type="hidden" name="context" value="<?php echo esc_attr( $context ); ?>">

			<?php do_action( "theme/post_loader_form/context=$context", $context ); ?>
			<?php do_action( 'theme/post_loader_form/', $context ); ?>

		</form>

		<div class="post-loader-result">

			<div class="post-loader-content"></div>

			<div class="post-loader-loader">
				<?php echo theme_get_icon( 'spinner' ); ?>
			</div>

		</div>

	</div><!-- .post-loader -->

	<script type="text/javascript">
		
		jQuery( document ).ready( function( $ )
		{
			$( '#<?php echo esc_js( $id ) ?>.post-loader' ).postLoader( <?php echo json_encode( $options ); ?> );
		});

	</script>

	<?php
}

/**
 * Shortcode
 */
function theme_post_loader_shortcode( $atts )
{
	$defaults = array
	(
		'context' => ''
	);

	$atts = shortcode_atts( $defaults, $atts );

	ob_start();

	theme_post_loader( $atts['context'] );

	return ob_get_clean();
}

add_shortcode( 'post-loader', 'theme_post_loader_shortcode' );

/**
 * Process
 */
function theme_post_loader_process()
{
	// Check ajax
	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) 
	{
		return;
	}

	// Check referer
	check_ajax_referer( 'post_loader', THEME_NONCE_NAME );

	//

	$context = isset( $_POST['context'] ) ? $_POST['context'] : '';

	$the_query = new WP_Query( array
	(
		'post_type' => 'post',
	));

	// Render result

	ob_start();

	do_action_ref_array( "theme/post_loader_result/context=$context", array( &$the_query, $context ) );
	do_action_ref_array( 'theme/post_loader_result'		            , array( &$the_query, $context ) );

	$content = ob_get_clean();

	// Response
	wp_send_json( array
	(
		'content'       => $content,
		'found_posts'   => $the_query->found_posts,
		'post_count'    => $the_query->post_count,
		'max_num_pages' => $the_query->max_num_pages,
		'paged'         => $the_query->get( 'paged' ),
	));
}

add_action( 'wp_ajax_theme_post_loader_process'		  , 'theme_post_loader_process' );
add_action( 'wp_ajax_nopriv_theme_post_loader_process', 'theme_post_loader_process' );

/**
 * Test
 */
add_action( 'theme/post_loader_result/context=test', function( $the_query )
{
	// Check posts
	if ( ! $the_query->have_posts() ) 
	{
		printf( '<div class="alert alert-info">%s</div>', __( 'No posts found.', 'theme' ) );

		return;
	}

	// Render posts

	echo '<div class="row">';

	// The loop
	while ( $the_query->have_posts() ) 
	{
		$the_query->the_post();

		echo '<div class="col-md-4">';

		// Include the Post-Type-specific template
		get_template_part( 'template-parts/card', get_post_type() );

		echo '</div>';
	}

	echo '</div>'; // .row

	wp_reset_postdata();
});
