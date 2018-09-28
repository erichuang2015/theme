<?php 

namespace Theme\Component\PostLoader;

/**
 * Post Loader
 */
class SamplePostLoader extends \Theme\Core\PostLoader\PostLoader
{
	public function __construct()
	{
		parent::__construct( 'sample' );
	}

	/**
	 * Form
	 */
	public function form()
	{
		$terms = get_terms( array
		(
			'taxonomy'   => 'category',
			'hide_empty' => false, // Testing not found message
		));

		?>

		<form class="post-loader-form" method="post">

			<?php $this->settings_fields(); ?>

			<?php  

				// Term filter

				if ( $terms ) 
				{
					echo '<nav class="filter-nav mb-4">';

					printf( '<label class="btn btn-outline-secondary btn-sm active"><input type="radio" class="autoload d-none" name="terms[]" value="" checked> %s</label> ', 
						esc_html__( 'Show All', 'theme' ) );

					foreach ( $terms as $term ) 
					{
						printf( '<label class="btn btn-outline-primary btn-sm"><input type="radio" class="autoload d-none" name="terms[]" value="%s"> %s</label> ', 
							esc_attr( $term->term_id ), esc_html( $term->name ) );
					}

					echo '</nav>';
				}

			?>

		</form>

		<?php
	}

	/**
	 * Result
	 */
	public function result( &$query = null )
	{
		/**
		 * Post data
		 */

		$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;
		$terms = isset( $_POST['terms'] ) && is_array( $_POST['terms'] ) ? $_POST['terms'] : array();

		/**
		 * WP Query
		 */

		$is_filter_active = false;

		$query_args = array
		(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'paged'       => $paged,
		);

		$pre_filter_query = new \WP_Query( $query_args );

		// Apply term filter

		// Remove 'Show All' empty value
		$terms = array_filter( $terms );

		if ( $terms ) 
		{
			$query_args['tax_query'][] = array
			(
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => array_map( 'intval', $terms ),
				'operator' => 'IN',
			);

			$is_filter_active = true;
		}

		// Set $query argument (required).

		$query = new \WP_Query( $query_args );

		/**
		 * Output
		 */

		// Posts found
		if ( $query->have_posts() ) 
		{
			// Meta data

			if ( $is_filter_active ) 
			{
				$term = get_term( $terms[0] );

				$message = sprintf( '<strong>%s</strong> - ', esc_html( $term->name ) );
				$message .= sprintf( __( '%d of %d posts', 'theme' ), $query->found_posts, $pre_filter_query->found_posts );
			}

			else
			{
				$message = sprintf( __( 'Showing all %d posts', 'theme' ), $query->found_posts );
			}

			printf( '<div class="alert alert-info"><small>%s</small></div>', $message );

			// List posts
			theme_list_posts( $query, array
			(
				'before_posts'  => '<div class="row">',
				'before_post'   => '<div class="col-md-4">',
				'post_template' => 'template-parts/card.php',
				'after_post'    => '</div>',
				'after_posts'   => '</div>',
			));

			// Pagination
			theme_posts_ajax_pagination( $query );
		}

		// No posts
		else
		{
			// Output message
			theme_no_posts_message( $query, '<div class="alert alert-warning">', '</div>' );
		}
	}
}
