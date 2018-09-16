<?php

namespace Theme\Component\Layouts\Layout;

class HeadingLayout extends \Theme\Core\Layouts\Layout\LayoutBase
{
	public function __construct()
	{
		parent::__construct( 'heading', __( 'Heading', 'theme' ), array
		(
			'class'    => 'heading',
			'features' => array
			( 
				'id', 
				'class',
				'bg_color',
			),
		));

		// Text
		$this->add_field( array
		(
			'key'           => "{$this->name}_title",
			'label'         => __( 'Text', 'theme' ),
			'name'          => 'text',
			'type'          => 'text',
			'default_value' => __( 'Heading', 'theme' ),
			'instructions'  => '',
			'required'      => true,
		));

		// Secondary Text
		$this->add_field( array
		(
			'key'           => "{$this->name}_subtitle",
			'label'         => __( 'Secondary Text','theme' ),
			'name'          => 'text_2',
			'type'          => 'text',
			'default_value' => '',
			'instructions'  => '',
			'required'      => false,
		));
	}

	public function render( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->get_defaults() );

		echo $args['before'];

		?>

		<?php if ( ! theme_has_container() ) : ?>
		<div class="container">
		<?php endif; ?>

		<?php  

			echo '<h2>' . esc_html( $instance['text'] );

			if ( $instance['text_2'] ) 
			{
				printf( ' <small>%s</small>', esc_html( $instance['text_2'] ) );
			}

			echo '</h2>';

		?>

		<?php if ( ! theme_has_container() ) : ?>
		</div><!-- . container -->
		<?php endif; ?>

		<?php

		echo $args['after'];
	}
}
