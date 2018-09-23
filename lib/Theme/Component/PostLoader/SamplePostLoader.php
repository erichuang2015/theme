<?php 

namespace Theme\Component\PostLoader;

/**
 * Sample Post Loader
 */
class SamplePostLoader extends \Theme\Core\PostLoader
{
	public function __construct()
	{
		parent::__construct( 'sample' );
	}

	public function inner()
	{
		?>

		<div class="row">
			<div class="col-lg-4">
				<form class="post-loader-form" method="post">
					<?php $this->form(); ?>
				</form>
			</div>
			<div class="col">
				<div class="post-loader-result">
					<?php $this->result(); ?>
				</div>
			</div>
		</div>

		<div class="post-loader-progress">
			<?php $this->progress(); ?>
		</div>

		<?php
	}

	public function form()
	{
		parent::form();

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
	}

	public function result( &$query = null )
	{
		$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;
		$terms = isset( $_POST['terms'] ) && is_array( $_POST['terms'] ) ? $_POST['terms'] : array();

		/**
		 * WP Query
		 * -----------------------------------------------------------
		 */

		$query_args = array
		(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'paged'       => $paged,
		);

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

		$query = new \WP_Query( $query_args );

		/**
		 * Output
		 * -----------------------------------------------------------
		 */

		// Posts

		if ( $query->have_posts() ) 
		{
			echo '<div class="row">';

			// The Loop
			while ( $query->have_posts() ) 
			{
				$query->the_post();

				echo '<div class="col-md-6 col-lg-4">';

				// Include item template
				get_template_part( 'template-parts/card', get_post_type() );

				echo '</div>';
			}

			echo '</div>';

			// Pagination
			theme_posts_ajax_pagination( $query );

			// Reset
			wp_reset_postdata();
		}

		// No Posts found

		else
		{
			printf( '<div class="alert alert-warning">%s</div>', __( 'No posts found.', 'theme' ) );
		}
	}
}
