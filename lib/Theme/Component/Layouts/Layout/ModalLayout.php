<?php

namespace Theme\Component\Layouts\Layout;

class ModalLayout extends \Theme\Core\Layouts\Layout\LayoutBase
{
	public function __construct()
	{
		parent::__construct( 'modal', __( 'Modal', 'theme' ), array
		(
			'features' => array(),
		));

		// Modal ID
		$this->add_field( array
		(
			'key'           => "{$this->name}_id",
			'label'         => __( 'ID', 'theme' ),
			'name'          => 'id',
			'type'          => 'text',
			'default_value' => '',
			'instructions'  => '',
			'required'      => true,
		));

		// Title
		$this->add_field( array
		(
			'key'           => "{$this->name}_title",
			'label'         => __( 'Title', 'theme' ),
			'name'          => 'title',
			'type'          => 'text',
			'default_value' => '',
			'instructions'  => '',
			'required'      => false,
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

		// Size
		$this->add_field( array
		(
			'key'           => "{$this->name}_size",
			'label'         => __( 'Size', 'theme' ),
			'name'          => 'size',
			'type'          => 'select',
			'choices'       => array
			(
				'sm' => __( 'Small', 'theme' ),
				'md' => __( 'Medium', 'theme' ),
				'lg' => __( 'Large', 'theme' ),
			),
			'default_value' => 'md',
			'instructions'  => '',
			'required'      => true,
			'category'      => 'layout',
			'order'         => 10,
		));
	}

	public function render( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->get_defaults() );

		echo $args['before'];

		?>

		<div id="<?php echo esc_attr( $instance['id'] ); ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="<?php echo esc_attr( $instance['id'] ); ?>Title" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-<?php echo esc_attr( $instance['size'] ); ?>" role="document">
				<div class="modal-content">

					<div class="modal-header">
						<?php if ( $instance['title'] ) : ?>
						<h5 class="modal-title" id="<?php echo esc_attr( $instance['id'] ); ?>Title"><?php echo esc_html( $instance['title'] ); ?></h5>
						<?php endif; ?>
						<button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'theme' ); ?>">
							<span aria-hidden="true">&times;</span>
						</button>
					</div><!-- .modal-header -->

					<div class="modal-body">
						<?php echo $instance['content']; ?>
					</div><!-- .modal-body -->

				</div><!-- .modal-content -->
			</div><!-- .modal-dialog -->
		</div><!-- .modal -->

		<?php

		echo $args['after'];
	}
}
