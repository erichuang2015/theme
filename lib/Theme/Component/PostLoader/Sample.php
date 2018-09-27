<?php 

namespace Theme\Component\PostLoader;

class Sample extends \Theme\Core\PostLoader
{
	public function __construct()
	{
		parent::__construct( 'sample', array
		(
			'before_posts'  => '<div class="row">',
			'before_post'   => '<div class="col-md-4">',
			'post_template' => 'template-parts/card.php',
			'after_post'    => '</div>',
			'after_posts'   => '</div>',
			'query_args' => 'post_type=page',
		));
	}

	public function inside()
	{
		?>

		<div class="row">

			<div class="col-lg-4">
				<?php $this->form(); ?>
			</div>

			<div class="col">
				<?php $this->content(); ?>
			</div>

		</div>

		<?php
	}

	public function form()
	{
		$terms = get_terms( array
		(
			'taxonomy'   => 'category',
			'hide_empty' => false, // test not found message
		));

		?>

		<form class="post-loader-form" method="post">

			<?php $this->settings_fields() ?>

			<?php if ( $terms ) : ?>
			<div class="term-filter d-lg-flex flex-lg-column">
				<?php foreach ( $terms as $term ) : ?>
				<label class="btn btn-outline-dark btn-sm text-lg-left"><input type="checkbox" class="autoload d-none" name="terms[]" value="<?php echo esc_attr( $term->term_id ); ?>"> <?php echo esc_html( $term->name ); ?></label>
				<?php endforeach; ?>
			</div><!-- .term-filter -->
			<?php endif ?>

		</form><!-- .post-loader-form -->

		<?php
	}

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

		$query_args = array
		(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'paged'       => $paged,
		);

		if ( $terms ) 
		{
			$query_args['tax_query'][] = array
			(
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => array_map( 'intval', $terms ),
				'operator' => 'IN',
			);
		}

		$query = new \WP_Query( $query_args );

		/**
		 * Output
		 */

		if ( $query->have_posts() ) 
		{
			$this->list_posts( $query );
		}

		else
		{
			$this->no_posts_message( $query, '<div class="alert alert-warning">', '</div>' );
		}
	}
}
