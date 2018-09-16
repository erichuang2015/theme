<?php

namespace Theme\Component\Layouts\Layout;

class PageHeaderLayout extends \Theme\Core\Layouts\Layout\LayoutBase
{
	public function __construct()
	{
		parent::__construct( 'page_header', __( 'Page Header', 'theme' ), array
		(
			'class'    => 'page-header',
			'features' => array
			( 
				'id', 
				'class',
				'bg_color',
			),
		));

		// Title
		$this->add_field( array
		(
			'key'           => "{$this->name}_title",
			'label'         => __( 'Title', 'theme' ),
			'name'          => 'title',
			'type'          => 'text',
			'default_value' => __( 'Page Header', 'theme' ),
			'instructions'  => '',
			'required'      => true,
		));

		// Subtitle
		$this->add_field( array
		(
			'key'           => "{$this->name}_subtitle",
			'label'         => __( 'Subtitle','theme' ),
			'name'          => 'subtitle',
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

			echo '<h1 class="page-title">' . esc_html( $instance['title'] );

			if ( $instance['subtitle'] ) 
			{
				printf( ' <small>%s</small>', esc_html( $instance['subtitle'] ) );
			}

			echo '</h1>';

		?>

		<?php if ( ! theme_has_container() ) : ?>
		</div><!-- . container -->
		<?php endif; ?>

		<?php

		echo $args['after'];
	}
}
