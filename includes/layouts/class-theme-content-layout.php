<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

class Theme_Content_Layout extends Theme_Layout
{
	public function __construct()
	{
		parent::__construct( 'content', __( 'Content', 'theme' ) );

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

		echo $args['before'];

		?>

		<div class="content my-5">
			<?php if ( ! theme_has_container() ) : ?>
			<div class="container">
			<?php endif; ?>

				<?php if ( theme_is_full_width() ) : ?>
				<div class="row">
					<div class="col-lg-7">
				<?php endif; ?>

				<?php echo $instance['content']; ?>

				<?php if ( theme_is_full_width() ) : ?>
					</div><!-- .col-lg-7 -->
				</div><!-- .row -->
				<?php endif; ?>
			<?php if ( ! theme_has_container() ) : ?>
			</div><!-- .container -->
			<?php endif; ?>
		</div><!-- .content -->

		<?php

		echo $args['after'];
	}

	public function enqueue_scripts()
	{
		
	}
}

theme_register_layout( 'Theme_Content_Layout' );
