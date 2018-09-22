<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Postloader
 *
 * Renders posts via ajax.
 */

/**
 * Render
 */
function theme_postloader( $loader_id )
{
	$html_id = "{$loader_id}-postloader";

	?>

	<div id="<?php echo esc_attr( $html_id ); ?>" class="postloader">
		<?php theme_postloader_inner( $loader_id ); ?>
	</div>

	<script type="text/javascript">
		
		jQuery( document ).on( 'ready', function( $ )
		{
			jQuery( '#<?php echo esc_js( $html_id ); ?>' ).postloader();
		});

	</script>

	<?php
}

/**
 * Inner
 */
function theme_postloader_inner( $loader_id )
{
	if ( has_action( "theme/postloader_inner/loader=$loader_id" ) )
	{
		do_action( "theme/postloader_inner/loader=$loader_id", $loader_id );

		return;
	}

	?>

	<form class="postloader-form" method="post">
		<?php theme_postloader_form( $loader_id ); ?>
	</form>

	<div class="postloader-result">
		<?php theme_postloader_result( $loader_id ); ?>
	</div>

	<div class="postloader-progress">
		<?php theme_postloader_progress( $loader_id ); ?>
	</div>

	<?php
}

/**
 * Form
 */
function theme_postloader_form( $loader_id )
{
	wp_nonce_field( 'postloader', THEME_NONCE_NAME );

	echo '<input type="hidden" name="action" value="theme/postloader_process">';
	echo '<input type="hidden" name="loader" value="' . esc_attr( $loader_id ) . '">';
	echo '<input type="hidden" name="paged" value="1">';

	do_action( "theme/postloader_form/loader=$loader_id" );
}

/**
 * Result
 */
function theme_postloader_result( $loader_id, &$wp_query = null )
{
	$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;

	$query_args = array
	(
		'post_type'   => 'post',
		'post_status' => 'publish',
		'paged'       => $paged,
	);

	do_action_ref_array( "theme/postloader_query_args/loader=$loader_id", array( &$query_args, $loader_id ) );

	$wp_query = new WP_Query( $query_args );

	do_action( "theme/postloader_result/loader=$loader_id", $wp_query, $loader_id );
}

/**
 * Progress
 */
function theme_postloader_progress( $loader_id )
{
	if ( has_action( "theme/postloader_progress/loader=$loader_id" ) ) 
	{
		do_action( "theme/postloader_progress/loader=$loader_id", $loader_id );
	}

	else
	{
		echo theme_get_icon( 'spinner' );
	}
}

/**
 * Process
 */
function theme_postloader_process()
{
	/**
	 * Check
	 * -----------------------------------------------------------
	 */

	// Ajax

	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) 
	{
		return;
	}

	// Referer

	check_ajax_referer( 'postloader', THEME_NONCE_NAME );

	/**
	 * Get post data
	 * -----------------------------------------------------------
	 */

	$loader_id = isset( $_POST['loader'] ) ? $_POST['loader'] : null;

	/**
	 * Result
	 * -----------------------------------------------------------
	 * Get result and WP_Query object.
	 */

	ob_start();

	theme_postloader_result( $loader_id, $wp_query );

	$result = ob_get_clean();

	/**
	 * Response
	 * -----------------------------------------------------------
	 */

	wp_send_json( array
	(
		'result'        => $result,
		'found_posts'   => intval( $wp_query->found_posts ),
		'post_count'    => $wp_query->post_count,
		'max_num_pages' => $wp_query->max_num_pages,
		'paged'         => $wp_query->get( 'paged' ),
	));
}

add_action( 'wp_ajax_theme_postloader_process'		 , 'theme_postloader_process' );
add_action( 'wp_ajax_nopriv_theme_postloader_process', 'theme_postloader_process' );

/**
 * Shortcode
 */
function theme_postloader_shortcode( $atts, $content = null, $tag )
{
	$defaults = array
	(
		'id' => ''
	);

	$atts = shortcode_atts( $defaults, $atts, $tag );

	ob_start();

	theme_postloader( $atts['id'] );

	return ob_get_clean();
}

add_shortcode( 'postloader', 'theme_postloader_shortcode' );

/*
----------------------------------------------------------------------
 Example
----------------------------------------------------------------------
*/

/**
 * Create Grid
 */
add_action( 'theme/postloader_inner/loader=test', function( $loader_id )
{
	?>

	<div class="row">
		<div class="col-md-4">
			<form class="postloader-form" method="post">
				<?php theme_postloader_form( $loader_id ); ?>
			</form>
		</div>
		<div class="col">
			<div class="postloader-result">
				<?php theme_postloader_result( $loader_id ); ?>
			</div>
		</div>
	</div>

	<div class="postloader-progress">
		<?php theme_postloader_progress( $loader_id ); ?>
	</div>

	<?php
});

/**
 * Render term filter
 */
add_action( 'theme/postloader_form/loader=test', function()
{
	$terms = get_terms( array
	(
		'taxonomy'   => 'category',
		'hide_empty' => false,
	));

	if ( $terms ) 
	{
		echo '<div class="term-filter d-md-flex flex-md-column">';

		foreach ( $terms as $term ) 
		{
			printf( '<label class="btn btn-outline-dark btn-sm"><input type="checkbox" class="autoload d-none" name="terms[]" value="%s">%s</label> ', 
				esc_attr( $term->term_id ), esc_html( $term->name ) );
		}

		echo '</div>'; // .term-filter
	}

});

/**
 * Apply term filter
 */
add_action( 'theme/postloader_query_args/loader=test', function( &$query_args )
{
	$terms = isset( $_POST['terms'] ) && is_array( $_POST['terms'] ) ? $_POST['terms'] : array();

	if ( $terms ) 
	{
		$query_args['tax_query'] = array
		(
			array
			(
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => array_map( 'intval', $terms ),
				'operator' => 'IN',
			),
		);
	}
});

/**
 * Result
 */
add_action( 'theme/postloader_result/loader=test', function( $wp_query )
{
	if ( $wp_query->have_posts() ) 
	{
		echo '<div class="row">';

		// The Loop
		while ( $wp_query->have_posts() ) 
		{
			$wp_query->the_post();

			echo '<div class="col-md-4">';

			// Include item template
			get_template_part( 'template-parts/card', get_post_type() );

			echo '</div>';  // .col…
		}

		echo '</div>'; // .row

		// Pagination
		theme_posts_ajax_pagination( $wp_query );

		// Reset
		wp_reset_postdata();
	}

	else
	{
		printf( '<div class="alert alert-warning">%s</div>', __( 'No posts found.', 'theme' ) );
	}
});
