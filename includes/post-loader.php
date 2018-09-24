<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Post Loader
 *
 * Renders posts via ajax.
 */

/**
 * Render
 */
function theme_post_loader( $loader_id, $include_content = true )
{
	$html_id = "$loader_id-post-loader";
	$target  = "$loader_id-post-loader-content";

	?>

	<div id="<?php echo esc_attr( $html_id ); ?>" class="post-loader" data-target="#<?php echo esc_attr( $target ); ?>">

		<form class="post-loader-form" method="post">

			<?php wp_nonce_field( 'post_loader', THEME_NONCE_NAME ); ?>

			<input type="hidden" name="action" value="theme_post_loader_process">
			<input type="hidden" name="loader" value="<?php echo esc_attr( $loader_id ); ?>">
			<input type="hidden" name="paged" value="1">

			<?php do_action( "theme_post_loader_form/loader=$loader_id", $loader_id ); ?>

		</form>

		<?php if ( $include_content ): ?>
		<?php theme_post_loader_content( $loader_id ); ?>
		<?php endif ?>

	</div><!-- .post-loader -->

	<?php
}

/**
 * Content
 */
function theme_post_loader_content( $loader_id )
{
	$html_id = "$loader_id-post-loader-content";

	?>

	<div id="<?php echo esc_attr( $html_id ); ?>" class="post-loader-content">
		<?php theme_post_loader_result( $loader_id ); ?>
	</div>

	<?php
}

/**
 * Result
 */
function theme_post_loader_result( $loader_id, &$query = null )
{
	/**
	 * WP Query
	 * ---------------------------------------------------------------
	 */

	$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;

	$query_args =array
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
 */
function theme_post_loader_process()
{
	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) 
	{
		return;
	}

	check_ajax_referer( 'post_loader', THEME_NONCE_NAME );

	ob_start();

	theme_post_loader_result( $_POST['loader'], $query );

	$content = ob_get_clean();

	wp_send_json( array
	(
		'content'       => $content,
		'found_posts'   => $query->found_posts,
		'post_count'    => $query->post_count,
		'max_num_pages' => $query->max_num_pages,
		'paged'         => $query->get( 'paged' ),
	));
}

add_action( 'wp_ajax_theme_post_loader_process'		  , 'theme_post_loader_process' );
add_action( 'wp_ajax_nopriv_theme_post_loader_process', 'theme_post_loader_process' );

/**
 * Post Loader Shortcode
 */
function theme_post_loader_shortcode( $args )
{
	$defaults = array
	(
		'id'      => '',
		'content' => true
	);

	$args = wp_parse_args( $args, $defaults );

	$include_content = ! $args['content'] || $args['content'] !== 'false';

	ob_start();

	theme_post_loader( $args['id'], $include_content );

	return ob_get_clean();
}

add_shortcode( 'post-loader', 'theme_post_loader_shortcode' );

/**
 * Post Loader Content Shortcode
 */
function theme_post_loader_content_shortcode( $args )
{
	$defaults = array
	(
		'loader' => '',
	);

	$args = wp_parse_args( $args, $defaults );

	ob_start();

	theme_post_loader_content( $args['loader'] );

	return ob_get_clean();
}

add_shortcode( 'post-loader-content', 'theme_post_loader_content_shortcode' );
