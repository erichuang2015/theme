<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Post Loader
 *
 * Renders posts via ajax.
 *
 * Dependency: `/src/js/post-loader.js` 
 */

/**
 * Render
 *
 * Output the post loader form and optionaly the content.
 * The content can be used separately. Use `theme_post_loader_content()`.
 *
 * @param string  $loader_id 	   The loader id.
 * @param boolean $include_content Whether to output the content. (optional, default: true)
 */
function theme_post_loader( $loader_id, $include_content = true )
{
	$html_id = "$loader_id-post-loader";
	$target  = "$loader_id-post-loader-content";

	?>

	<form id="<?php echo esc_attr( $html_id ); ?>" class="post-loader" method="post" data-target="#<?php echo esc_attr( $target ); ?>">
		
		<?php wp_nonce_field( 'post_loader', THEME_NONCE_NAME ); ?>
		
		<input type="hidden" name="action" value="theme_post_loader_process">
		<input type="hidden" name="loader" value="<?php echo esc_attr( $loader_id ); ?>">
		<input type="hidden" name="paged" value="1">

		<?php do_action( "theme_post_loader_form/loader=$loader_id", $loader_id ); ?>

	</form><!-- .post-loader -->

	<?php

	if ( $include_content ) theme_post_loader_content( $loader_id );
}

/**
 * Content
 *
 * Output the content.
 *
 * @param string $loader_id The loader id.
 */
function theme_post_loader_content( $loader_id )
{
	$html_id = "$loader_id-post-loader-content";

	?>

	<div id="<?php echo esc_attr( $html_id ); ?>" class="post-loader-content">
		<?php theme_post_loader_result( $loader_id ); ?>
	</div><!-- .post-loader-content -->

	<?php
}

/**
 * Result
 *
 * Setup the WP_Query object and output the result.
 *
 * @param string $loader_id The loader id.
 * @param WP_Query $query
 */
function theme_post_loader_result( $loader_id, &$query = null )
{
	/**
	 * Post data
	 * ---------------------------------------------------------------
	 */

	// TODO : check if our form is submitted.

	$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;

	/**
	 * WP Query
	 * ---------------------------------------------------------------
	 */

	$query_args = array
	(
		'post_type'   => 'post',
		'post_status' => 'publish',
		'paged'       => $paged,
	);
	
	$query_args = apply_filters( "theme_post_loader_query_args/loader=$loader_id", $query_args, $loader_id );

	$query = new WP_Query( $query_args );

	/**
	 * Output
	 * ---------------------------------------------------------------
	 */

	do_action( "theme_post_loader_result/loader=$loader_id", $query, $loader_id );
}

/**
 * Process
 *
 * Handle ajax request and sends the result.
 */
function theme_post_loader_process()
{
	/**
	 * Check
	 * ---------------------------------------------------------------
	 */

	// Ajax
	if ( ! wp_doing_ajax() ) 
	{
		return;
	}

	// Referer
	check_ajax_referer( 'post_loader', THEME_NONCE_NAME );

	/**
	 * Result
	 * ---------------------------------------------------------------
	 * Get result and WP_Query object.
	 */

	ob_start();

	theme_post_loader_result( $_POST['loader'], $query );

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

add_action( 'wp_ajax_theme_post_loader_process'		  , 'theme_post_loader_process' );
add_action( 'wp_ajax_nopriv_theme_post_loader_process', 'theme_post_loader_process' );

/**
 * Shortcode
 */
function theme_post_loader_shortcode( $atts, $content = null, $tag )
{
	$defaults = array
	(
		'id'              => '',
		'include_content' => true,
	);

	$atts = shortcode_atts( $defaults, $atts, $tag );

	$include_content = $atts['include_content'] && $atts['include_content'] !== 'false';

	ob_start();

	theme_post_loader( $atts['id'], $include_content );

	return ob_get_clean();
}

add_shortcode( 'post-loader', 'theme_post_loader_shortcode' );

/**
 * Content Shortcode
 */
function theme_post_loader_content_shortcode( $atts, $content = null, $tag )
{
	$defaults = array
	(
		'loader' => '',
	);

	$atts = shortcode_atts( $defaults, $atts, $tag );

	ob_start();

	theme_post_loader_content( $atts['loader'] );

	return ob_get_clean();
}

add_shortcode( 'post-loader-content', 'theme_post_loader_content_shortcode' );
