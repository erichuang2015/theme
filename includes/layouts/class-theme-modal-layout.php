<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

class Theme_Modal_Layout extends Theme_Layout
{
	public function __construct()
	{
		parent::__construct( 'modal', __( 'Modal', 'theme' ) );

		$this->add_field( array
		(
			'key'           => "{$this->name}_modal_id",
			'label'         => __( 'Modal ID', 'theme' ),
			'name'          => 'modal_id',
			'instructions'  => '',
			'type'          => 'text',
			'default_value' => '',
			'required'      => true,
		));

		$this->add_field( array
		(
			'key'           => "{$this->name}_title",
			'label'         => __( 'Title', 'theme' ),
			'name'          => 'title',
			'instructions'  => '',
			'type'          => 'text',
			'default_value' => '',
			'required'      => true,
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

		$this->add_field( array
		(
			'key'           => "{$this->name}_size",
			'label'         => __( 'Size', 'theme' ),
			'name'          => 'size',
			'instructions'  => '',
			'type'          => 'select',
			'choices'       => array
			(
				'sm' => __( 'Small', 'theme' ),
				'md' => __( 'Medium', 'theme' ),
				'lg' => __( 'Large', 'theme' ),
			),
			'default_value' => 'md',
			'required'      => true,
			'order'         => THEME_LAYOUTS_ORDER_LAYOUT + 10
		));
	}

	public function render( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->get_defaults() );

		echo $args['before'];

		?>

		<div id="<?php echo esc_attr( $instance['modal_id'] ); ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="<?php echo esc_attr( $instance['modal_id'] ); ?>-label" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-<?php echo sanitize_html_class( $instance['size'] ); ?>" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<?php if ( $instance['title'] ) : ?>
						<h5 class="modal-title" id="<?php echo esc_attr( $instance['modal_id'] ); ?>-label"><?php echo esc_html( $instance['title'] ); ?></h5>
						<?php endif; ?>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
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

	public function enqueue_scripts()
	{
		
	}
}

theme_register_layout( 'Theme_Modal_Layout' );
