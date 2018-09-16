<?php

namespace Theme\Component\Layouts\Layout;

class ContentLayout extends \Theme\Core\Layouts\Layout\LayoutBase
{
	public function __construct()
	{
		parent::__construct( 'content', __( 'Content', 'theme' ), array
		(
			'class'    => 'content',
			'features' => array
			( 
				'id', 
				'class',
				'bg_color',
			),
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

		echo $args['before'];

		?>

		<?php if ( ! theme_has_container() ) : ?>
		<div class="container">
		<?php endif; ?>

			<?php if ( theme_is_full_width() ) : ?>
			<div class="row">
				<div class="col-lg-9">
			<?php endif; ?>

			<?php echo $instance['content']; ?>

			<?php if ( theme_is_full_width() ) : ?>
				</div><!-- .col-lg-9 -->
			</div><!-- .row -->
			<?php endif; ?>

		<?php if ( ! theme_has_container() ) : ?>
		</div><!-- . container -->
		<?php endif; ?>

		<?php

		echo $args['after'];
	}
}
