<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Post Loader
 *
 * Render posts via ajax.
 */

/**
 * Render
 */
function theme_post_loader( $loader_id )
{
	$html_id = "{$loader_id}-post-loader";

	?>

	<div id="<?php echo esc_attr( $html_id ); ?>" class="post-loader">
		<?php theme_post_loader_inner( $loader_id ); ?>
	</div><!-- .post-loader -->

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
function theme_post_loader_inner( $loader_id )
{
	if ( has_action( "theme/post_loader_inner/loader=$loader_id" ) ) 
	{
		do_action( "theme/post_loader_inner/loader=$loader_id", $loader_id );

		return;
	}

	?>

	<form class="post-loader-form" method="post">
		<?php theme_post_loader_form( $loader_id ); ?>
	</form>

	<div class="post-loader-result">
		<?php theme_post_loader_result( $loader_id ); ?>
	</div>

	<div class="post-loader-progress">
		<?php echo theme_get_icon( 'spinner' ); ?>
	</div>

	<?php
}

/**
 * Form
 */
function theme_post_loader_form( $loader_id )
{
	wp_nonce_field( 'post_loader', THEME_NONCE_NAME );

	echo '<input type="hidden" name="action" value="theme_post_loader_process">';
	echo '<input type="hidden" name="loader" value="' . esc_attr( $loader_id ) . '">';
	echo '<input type="hidden" name="paged" value="1">';

	do_action( "theme/post_loader_form/loader=$loader_id", $loader_id );
}

/**
 * Result
 */
function theme_post_loader_result( $loader_id, &$wp_query = null )
{
	$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;

	$query_args = array
	(
		'post_type'   => 'post',
		'post_status' => 'publish',
		'paged'       => $paged,
	);

	do_action_ref_array( "theme/post_loader_query_args/loader=$loader_id", array( &$query_args, $loader_id ) );

	$wp_query = new WP_Query( $query_args );

	do_action( "theme/post_loader_result/loader=$loader_id", $wp_query, $loader_id );
}

/**
 * Process
 */
function theme_post_loader_process()
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

	check_ajax_referer( 'post_loader', THEME_NONCE_NAME );

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

	theme_post_loader_result( $loader_id, $wp_query );

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

add_action( 'wp_ajax_theme_post_loader_process'		 , 'theme_post_loader_process' );
add_action( 'wp_ajax_nopriv_theme_post_loader_process', 'theme_post_loader_process' );

/**
 * Shortcode
 */
function theme_post_loader_shortcode( $atts, $content = null, $tag )
{
	$defaults = array
	(
		'id' => ''
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

/**
 * Custom layout
 */
add_action( 'theme/post_loader_inner/loader=sample', function( $loader_id )
{
	?>

	<div class="row">
		<div class="col-lg-4">
			<form class="post-loader-form" method="post">
				<?php theme_post_loader_form( $loader_id ); ?>
			</form>
		</div>
		<div class="col">
			<div class="post-loader-result">
				<?php theme_post_loader_result( $loader_id ); ?>
			</div>
		</div>
	</div>

	<div class="post-loader-progress">
		<?php echo theme_get_icon( 'spinner' ); ?>
	</div>

	<?php
});

/**
 * Form
 */
add_action( 'theme/post_loader_form/loader=sample', function()
{
	// Render term filter

	$terms = get_terms( array
	(
		'taxonomy'   => 'category',
		'hide_empty' => false,
	));

	if ( $terms ) 
	{
		echo '<div class="term-filter d-lg-flex flex-lg-column">';

		foreach ( $terms as $term ) 
		{
			printf( '<label class="btn btn-outline-dark btn-sm text-left"><input type="checkbox" class="autoload" name="terms[]" value="%s"> %s</label> ', 
				esc_attr( $term->term_id ), esc_html( $term->name ) );
		}

		echo '</div>'; // .term-filter
	}

});

/**
 * Query Args
 */
add_action( 'theme/post_loader_query_args/loader=sample', function( &$query_args )
{
	// Apply term filter

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
add_action( 'theme/post_loader_result/loader=sample', function( $wp_query )
{
	if ( $wp_query->have_posts() ) 
	{
		echo '<div class="row">';

		// The Loop
		while ( $wp_query->have_posts() ) 
		{
			$wp_query->the_post();

			echo '<div class="col-sm-6 col-md-4">';

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

