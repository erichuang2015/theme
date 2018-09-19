<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Post Loader
 */

/**
 * Render Post Loader
 */
function theme_post_loader( $loader_id )
{
	?>

	<div id="<?php echo esc_attr( $loader_id ); ?>-post-loader" class="post-loader" data-id="<?php echo esc_attr( $loader_id ); ?>">

		<form class="post-loader-form" method="post">
			
			<?php wp_nonce_field( 'post_loader', THEME_NONCE_NAME ); ?>

			<input type="hidden" name="action" value="theme_post_loader_process">
			<input type="hidden" name="loader" value="<?php echo esc_attr( $loader_id ); ?>">

			<?php do_action( "theme/post_loader_form/loader=$loader_id" ); ?>

		</form><!-- .post-loader-form -->

		<div class="post-loader-result">
			<?php theme_post_loader_result( $loader_id ); ?>
		</div><!-- .post-loader-result -->

		<div class="post-loader-progress">
			<?php echo theme_get_icon( 'spinner' ); ?>
		</div><!-- .post-loader-progress -->

	</div><!-- .post-loader -->

	<?php
}

/**
 * Render Result
 */
function theme_post_loader_result( $loader_id, &$wp_query = null )
{
	do_action_ref_array( "theme/post_loader_result/loader=$loader_id", array( &$wp_query, $loader_id ) );
}

/**
 * Process
 */
function theme_post_loader_process()
{
	/**
	 * Check
	 * ---------------------------------------------------------------
	 */

	// Check ajax
	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) 
	{
		return;
	}

	// Check referer
	check_ajax_referer( 'post_loader', THEME_NONCE_NAME );

	/**
	 * Post data
	 * ---------------------------------------------------------------
	 */

	$loader_id = isset( $_POST['loader'] ) ? $_POST['loader'] : null;

	/**
	 * Content
	 * ---------------------------------------------------------------
	 */

	// Start output buffer
	ob_start();

	// Print result
	theme_post_loader_result( $loader_id, $wp_query );

	// Fetch output
	$content = ob_get_clean();

	/**
	 * Response
	 * ---------------------------------------------------------------
	 */

	if ( ! $wp_query instanceof WP_Query ) 
	{
		wp_send_json_error( __( 'WP query is required.', 'theme' ) );
	}

	wp_send_json_success( array
	(
		'content'       => $content,
		'found_posts'   => intval( $wp_query->found_posts ),
		'post_count'    => $wp_query->post_count,
		'max_num_pages' => $wp_query->max_num_pages,
		'paged'         => $wp_query->get( 'paged' ),
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
		'id' => '',
	);

	$atts = shortcode_atts( $defaults, $atts, $tag );

	ob_start();

	theme_post_loader( $atts['id'] );

	return ob_get_clean();
}

add_shortcode( 'post-loader', 'theme_post_loader_shortcode' );

/*
----------------------------------------------------------------------
 Example
----------------------------------------------------------------------
*/

add_action( 'theme/post_loader_form/loader=example', function()
{
	// Render category filter

	$terms = get_terms( array
	(
		'taxonomy' => 'category',
	));

	echo '<div class="terms">';

	foreach ( $terms as $term ) 
	{
		printf( '<label><input type="checkbox" class="autoload" name="terms[]" value="%s"> %s</label>', esc_attr( $term->term_id ), esc_html__( $term->name ) );
	}

	echo '</div>';
});

add_action( 'theme/post_loader_result/loader=example', function( &$wp_query )
{
	$terms = isset( $_POST['terms'] ) && is_array( $_POST['terms'] ) ? $_POST['terms'] : array();

	/**
	 * WP Query
	 * ---------------------------------------------------------------
	 */

	$query_args = array
	(
		'post_type' => 'post',
	);

	if ( $terms ) 
	{
		$query_args['tax_query'] = array
		(
			'relation' => 'AND',
		);

		foreach ( $terms as $term_id ) 
		{
			$query_args['tax_query'][] = array
			( 
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => (array) intval( $term_id ),
				'operator' => 'IN',
			);
		}
	}

	$wp_query = new WP_Query( $query_args );

	/**
	 * Output
	 * ---------------------------------------------------------------
	 */

	if ( $wp_query->have_posts() ) 
	{
		echo '<div class="row">';

		while ( $wp_query->have_posts() ) 
		{
			$wp_query->the_post();

			echo '<div class="col-md-4">';

			get_template_part( 'template-parts/card', get_post_type() );

			echo '</div>';
		}

		echo '</div>'; // .row
	}

	else
	{
		printf( '<div class="alert alert-info">%s</div>', __( 'No posts found.', 'theme' ) );
	}
});
