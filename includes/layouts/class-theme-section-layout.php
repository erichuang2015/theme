<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

class Theme_Section_Layout extends Theme_Layout
{
	public function __construct()
	{
		parent::__construct( 'section', __( 'Section', 'theme' ) );

		$this->add_field( array
		(
			'key'           => "{$this->name}_post_id",
			'label'         => __( 'Section', 'theme' ),
			'name'          => 'post_id',
			'instructions'  => '',
			'type'          => 'post_object',
			'default_value' => '',
			'required'      => true,
			'post_type'     => array( 'section' ),
			'allow_null'    => false,
			'multiple'      => false,
			'return_format' => 'id',
		));
	}

	public function render( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->get_defaults() );

		echo $args['before'];

		$the_query = new WP_Query( array
		(
			'p'         => $instance['post_id'],
			'post_type' => THEME_SECTIONS_POST_TYPE
		));

		if ( ! $the_query->have_posts() ) 
		{
			return;
		}

		?>

		<div class="section my-5">

			<?php if ( ! theme_has_container() ) : ?>
			<div class="container">
			<?php endif; ?>

				<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<?php the_content(); ?>
				<?php endwhile; wp_reset_postdata(); ?>

			<?php if ( ! theme_has_container() ) : ?>
			</div><!-- .container -->
			<?php endif; ?>

		</div><!-- .section -->

		<?php

		echo $args['after'];
	}

	public function enqueue_scripts()
	{
		
	}
}

theme_register_layout( 'Theme_Section_Layout' );
