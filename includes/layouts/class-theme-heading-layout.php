<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

class Theme_Heading_Layout extends Theme_Layout
{
	public function __construct()
	{
		parent::__construct( 'heading', __( 'Heading', 'theme' ) );

		$this->add_field( array
		(
			'key'           => "{$this->name}_text",
			'label'         => __( 'Text', 'theme' ),
			'name'          => 'text',
			'instructions'  => '',
			'type'          => 'text',
			'default_value' => '',
			'required'      => true,
		));

		$this->add_field( array
		(
			'key'           => "{$this->name}_text_2",
			'label'         => __( 'Secondary Text', 'theme' ),
			'name'          => 'text_2',
			'instructions'  => '',
			'type'          => 'text',
			'default_value' => '',
			'required'      => false,
		));
	}

	public function render( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->get_defaults() );

		echo $args['before'];

		?>

		<div class="heading my-5">
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
			</div><!-- .container -->
			<?php endif; ?>
		</div><!-- .heading -->

		<?php

		echo $args['after'];
	}

	public function enqueue_scripts()
	{
		
	}
}

theme_register_layout( 'Theme_Heading_Layout' );
