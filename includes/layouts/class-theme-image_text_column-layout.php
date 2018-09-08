<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

class Theme_Image_Text_Column_Layout extends Theme_Layout
{
	public function __construct()
	{
		parent::__construct( 'image_text_column', __( 'Image - Text Column', 'theme' ) );

		$this->add_field( array
		(
			'key'           => "{$this->name}_image",
			'label'         => __( 'Image', 'theme' ),
			'name'          => 'image',
			'instructions'  => '',
			'type'          => 'image',
			'default_value' => '',
			'required'      => true,
			'return_format' => 'id',
			'wrapper'       => array( 'width' => 50 ),
		));

		$this->add_field( array
		(
			'key'           => "{$this->name}_image_position",
			'label'         => __( 'Image Position', 'theme' ),
			'name'          => 'image_position',
			'instructions'  => '',
			'type'          => 'select',
			'choices'       => array
			(
				'left'  => __( 'Left', 'theme' ),
				'right' => __( 'Right', 'theme' ),
			),
			'default_value' => 'left',
			'required'      => true,
			'wrapper'       => array( 'width' => 50 ),
		));

		$this->add_field( array
		(
			'key'           => "{$this->name}_content",
			'label'         => __( 'Content', 'theme' ),
			'name'          => 'content',
			'instructions'  => '',
			'type'          => 'wysiwyg',
			'default_value' => '',
			'required'      => true,
			'tabs'          => 'visual',
			'toolbar'       => 'full',
			'media_upload'  => true,
			'delay'         => false,
		));
	}			

	public function render( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->get_defaults() );

		//

		$image_column = array
		(
			'class' => 'col-md'
		);

		$text_column = array
		(
			'class' => 'col-md'
		);

		// Position
		if ( $instance['image_position'] == 'right' ) 
		{
			$text_column['class']  .= ' order-md-1';
			$image_column['class'] .= ' order-md-2';
		}

		echo $args['before'];

		?>

		<div class="text-image-column my-5">
			<?php if ( ! theme_has_container() ) : ?>
			<div class="container">
			<?php endif; ?>
				<div class="row">

					<!-- Image -->
					<div<?php echo theme_esc_attr( $image_column ); ?>>
						<?php 

							echo wp_get_attachment_image( $instance['image'], 'large', false, array
							(
								'class' => 'mb-4 mb-md-0'
							)); 

						?>
					</div>

					<!-- Text -->
					<div<?php echo theme_esc_attr( $text_column ); ?>>
						<?php echo $instance['content']; ?>
					</div>

				</div><!-- .row -->
			<?php if ( ! theme_has_container() ) : ?>
			</div><!-- .container -->
			<?php endif; ?>
		</div><!-- .image-text-column -->

		<?php

		echo $args['after'];
	}

	public function enqueue_scripts()
	{
		
	}
}

theme_register_layout( 'Theme_Image_Text_Column_Layout' );
