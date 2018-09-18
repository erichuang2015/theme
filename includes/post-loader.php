<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Post Loader
 */

/**
 * Render
 */
function theme_post_loader( $id )
{
	?>

	<div id="<?php echo esc_attr( $id ); ?>" class="post-loader">
		
		<form class="post-loader-form" method="post">
			
			<?php wp_nonce_field( 'post_loader', THEME_NONCE_NAME ); ?>
			<input type="hidden" name="action" value="theme_post_loader_process">
			<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>">

			<?php do_action( "theme/post_loader_form/id=$id", $id ); ?>
			<?php do_action( 'theme/post_loader_form'		, $id ); ?>

		</form>

		<div class="post-loader-result">
			<?php theme_post_loader_result( $id ); ?>
		</div>

		<div class="post-loader-loader">
			<?php echo theme_get_icon( 'spinner' ); ?>
		</div><!-- .post-loader-loader -->

	</div><!-- .post-loader -->

	<script type="text/javascript">
		
		jQuery( document ).ready( function( $ )
		{
			$( '#<?php echo esc_js( $id ) ?>.post-loader' ).postLoader();
		});

	</script>

	<?php
}

/**
 * Result
 */
function theme_post_loader_result( $id, &$the_query = null )
{
	/**
	 * Arguments
	 * ---------------------------------------------------------------
	 */

	// Defaults
	$args = array
	(
		'before'        => '<div class="row">',
		'before_item'   => '<div class="col-12">',
		'item_template' => 'card',
		'after_item'    => '</div>',
		'after'         => '</div>',
	);

	// Filter
	$args = (array) apply_filters( "theme/post_loader_result_args/id=$id", $args, $id );
	$args = (array) apply_filters( 'theme/post_loader_result_args'		 , $args, $id );

	/**
	 * WP Query
	 * ---------------------------------------------------------------
	 */

	// Defaults
	$query_args = array
	(
		'post_type' => 'post',
	);

	// Filter
	$query_args = apply_filters( "theme/post_loader_query_args/id=$id", $query_args, $id );
	$query_args = apply_filters( 'theme/post_loader_query_args'		  , $query_args, $id );
 	
 	//
	$the_query = new WP_Query( $query_args );

	/**
	 * Output
	 * ---------------------------------------------------------------
	 */

	// Posts found

	if ( $the_query->have_posts() ) 
	{
		echo $args['before'];

		while ( $the_query->have_posts() ) 
		{
			$the_query->the_post();

			echo $args['before_item'];

			locate_template( "template-parts/{$args['item_template']}.php", true, false );

			echo $args['after_item'];
		}

		echo $args['after'];

		return;
	}

	// No posts found

	// post-type-specific message

	$post_type = $the_query->get( 'post_type' );

	if ( $post_type && ! is_array( $post_type ) ) 
	{
		// Get object
		$post_type = get_post_type_object( $post_type );

		$message = sprintf( __( 'No %s found.', 'theme' ), strtolower( $post_type->labels->name ) );
	}

	// general message

	else
	{
		$message = __( 'No items found.', 'theme' );
	}

	// Filter
	$wrap = sprintf( '<div class="alert alert-info">%s</div>', $message );
	$wrap = apply_filters( "theme/post_loader_not_found_message/id=$id", $wrap, $id );
	$wrap = apply_filters( 'theme/post_loader_not_found_message'	   , $wrap, $id );

	// Output
	echo $wrap;
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
	 * Form data
	 * ---------------------------------------------------------------
	 */

	$id = isset( $_POST['id'] ) ? $_POST['id'] : '';

	/**
	 * Result
	 * ---------------------------------------------------------------
	 */

	// Start output buffer
	ob_start();

	// Render result
	theme_post_loader_result( $id, $the_query );
	
	// Fetch output
	$content = ob_get_clean();

	/**
	 * Response
	 * ---------------------------------------------------------------
	 */

	wp_send_json( array
	(
		'content'       => $content,
		'found_posts'   => intval( $the_query->found_posts ),
		'post_count'    => $the_query->post_count,
		'max_num_pages' => $the_query->max_num_pages,
		'paged'         => $the_query->get( 'paged' ),
	));
}

add_action( 'wp_ajax_theme_post_loader_process'		  , 'theme_post_loader_process' );
add_action( 'wp_ajax_nopriv_theme_post_loader_process', 'theme_post_loader_process' );

/**
 * Shortcode
 */
function theme_post_loader_shortcode( $atts )
{
	$defaults = array
	(
		'id' => ''
	);

	$atts = shortcode_atts( $defaults, $atts );

	ob_start();

	theme_post_loader( $atts['id'] );

	return ob_get_clean();
}

add_shortcode( 'post-loader', 'theme_post_loader_shortcode' );

/*
----------------------------------------------------------------------
 Test
----------------------------------------------------------------------
*/

function theme_post_loader_test_form()
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
}

add_action( 'theme/post_loader_form/id=my-post-loader', 'theme_post_loader_test_form' );

function theme_post_loader_test_query_args()
{
	$terms = isset( $_POST['terms'] ) && is_array( $_POST['terms'] ) ? $_POST['terms'] : array();

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

	return $query_args;
}

add_filter( 'theme/post_loader_query_args/id=my-post-loader', 'theme_post_loader_test_query_args' );

function theme_post_loader_test_result_args( $args )
{
	return array_merge( $args, array
	(
		'before'        => '<div class="row">',
		'before_item'   => '<div class="col-md-4">',
		'item_template' => 'card',
		'after_item'    => '</div>',
		'after'         => '</div>',
	));
}

add_filter( 'theme/post_loader_result_args/id=my-post-loader', 'theme_post_loader_test_result_args' );
