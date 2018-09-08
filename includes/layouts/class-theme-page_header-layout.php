<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

class Theme_Page_Header_Layout extends Theme_Layout
{
	public function __construct()
	{
		parent::__construct( 'page_header', __( 'Page Header', 'theme' ) );

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
			'key'           => "{$this->name}_subtitle",
			'label'         => __( 'Subtitle', 'theme' ),
			'name'          => 'subtitle',
			'instructions'  => '',
			'type'          => 'text',
			'default_value' => '',
			'required'      => false,
		));

		add_filter( 'the_title', array( $this, 'remove_the_title' ), 99, 2 );
	}

	public function render( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->get_defaults() );

		echo $args['before'];

		?>

		<header class="page-header my-5">
			<?php if ( ! theme_has_container() ) : ?>
			<div class="container">
			<?php endif; ?>
			<?php 

				echo '<h1>' . esc_html( $instance['title'] );

				if ( $instance['subtitle'] )
				{
					printf( ' <small>%s</small>', esc_html( $instance['subtitle'] ) );
				}

				echo '</h1>';
			?>
			<?php if ( ! theme_has_container() ) : ?>
			</div><!-- .container -->
			<?php endif; ?>
		</header><!-- .page-header -->

		<?php

		echo $args['after'];
	}

	public function enqueue_scripts()
	{
		
	}

	function remove_the_title( $title, $post_id )
	{
		if ( theme_has_layout( $this->name ) ) 
		{
			if ( is_main_query() 
			  && in_the_loop() 
			  && ( is_single( $post_id ) || is_page( $post_id ) ) ) 
			{
				return '';
			}
		}

		return $title;
	}
}

theme_register_layout( 'Theme_Page_Header_Layout' );
