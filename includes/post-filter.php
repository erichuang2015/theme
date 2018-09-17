<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly
/**
 * Post Filter
 */

function theme_post_filter( $id )
{
	?>

	<div id="<?php echo esc_attr( $id ); ?>-post-filter" class="post-filter">

		<form method="post">
			
			<?php wp_nonce_field( 'post_filter', 'theme_nonce' ); ?>

			<input type="hidden" name="action" value="theme_post_filter_process">
			<input type="hidden" name="paged" value="1">
			<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>">

			<?php do_action( "theme/post_filter_form/id=$id" ); ?>
			<?php do_action( 'theme/post_filter_form', $id ); ?>

		</form>

		<div class="post-filter-result"></div>

	</div><!-- .post-filter -->

	<?php
}

function theme_post_filter_process()
{
	// Check if ajax
	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) 
	{
		return;
	}

	// Check referer
	check_ajax_referer( 'post_filter', 'theme_nonce' );

	//
	$id    = isset( $_POST['id'] ) ? $_POST['id'] : null;
	$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;

	$the_query = new WP_Query( array
	(
		'post_type' => 'post',
		'paged'     => $paged,
	));

	ob_start();

	do_action_ref_array( "theme/post_filter_result/id=$id", array( &$the_query, $id ) );
	do_action_ref_array( 'theme/post_filter_result', array( &$the_query, $id ) );

	$content = ob_get_clean();

	wp_send_json( array
	(
		'content'       => $content,
		'post_count'    => $the_query->post_count,
		'found_posts'   => (int) $the_query->found_posts,
		'max_num_pages' => $the_query->max_num_pages,
		'paged'         => $the_query->get( 'paged' )
	));
}

add_action( 'wp_ajax_theme_post_filter_process'		  , 'theme_post_filter_process' );
add_action( 'wp_ajax_nopriv_theme_post_filter_process', 'theme_post_filter_process' );

function theme_post_filter_shortcode( $atts )
{
	$defaults = array
	(
		'id' => ''
	);

	$atts = shortcode_atts( $defaults, $atts );

	ob_start();

	theme_post_filter( $atts['id'] );

	return ob_get_clean();
}

add_shortcode( 'post-filter', 'theme_post_filter_shortcode' );
