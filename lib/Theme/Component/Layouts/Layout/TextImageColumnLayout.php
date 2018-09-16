<?php

namespace Theme\Component\Layouts\Layout;

class TextImageColumnLayout extends \Theme\Core\Layouts\Layout\LayoutBase
{
	public function __construct()
	{
		parent::__construct( 'text_image_colum', __( 'Text - Image Column', 'theme' ), array
		(
			'class'    => 'text-image-column',
			'features' => array
			( 
				'id', 
				'class',
				'bg_color',
			),
		));

		// Image
		$this->add_field( array
		(
			'key'           => "{$this->name}_image",
			'label'         => 'Image',
			'name'          => 'image',
			'type'          => 'image',
			'instructions'  => '',
			'required'      => true,
			'return_format' => 'id',
		));

		// Image Position
		$this->add_field( array
		(
			'key'          => "{$this->name}_image_position",
			'label'        => __( 'Image Position', 'theme'),
			'name'         => 'image_position',
			'type'         => 'select',
			'instructions' => '',
			'required'     => true,
			'choices'      => array
			(
				'left'  => __( 'Left', 'theme'),
				'right' => __( 'Right', 'theme'),
			),
			'default_value' => array( 'right' ),
			'allow_null'    => false,
			'multiple'      => false,
			'return_format' => 'value',
			'category'      => 'layout',
		));

		// Content
		$this->add_field( array
		(
			'key'           => "{$this->name}_content",
			'label'         => __( 'Content', 'theme' ),
			'name'          => 'content',
			'type'          => 'wysiwyg',
			'default_value' => '<p>Penatibus anim inceptos doloribus scelerisque sodales at tempore, amet delectus similique alias, expedita vel in! Quae, vehicula, nam, fugit eius inceptos nisi, nibh quidem? Tincidunt? Nascetur minim veritatis, quisque do.</p>',
			'instructions'  => '',
			'required'      => true,
		));
	}

	public function render( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->get_defaults() );

		// Attributes

		$image_column = array
		( 
			'class' => 'col-md-6',
		);

		$text_column = array
		( 
			'class' => 'col-md-6',
		);

		if ( $instance['image_position'] == 'right' ) 
		{
			$image_column['class'] .= ' order-md-2';
			$text_column['class'] .= ' order-md-1';
		}
		
		echo $args['before'];

		?>

		<?php if ( ! theme_has_container() ) : ?>
		<div class="container">
		<?php endif; ?>

			<div class="row">

				<div<?php echo theme_esc_attr( $image_column ); ?>>
					<?php 

					echo wp_get_attachment_image( $instance['image'], 'large', false, array
					( 
						'class' => 'mb-3 mb-md-0' 
					)); 

					?>
				</div>

				<div<?php echo theme_esc_attr( $text_column ); ?>>
					<?php echo $instance['content']; ?>
				</div>

			</div><!-- .row -->
			

		<?php if ( ! theme_has_container() ) : ?>
		</div><!-- . container -->
		<?php endif; ?>

		<?php

		echo $args['after'];
	}
}
