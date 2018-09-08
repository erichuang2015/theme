<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

class Theme_Section_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct( 'theme_section', __( 'Section', 'theme' ), array
		( 
			'description' => esc_html__( 'Displays a section.', 'theme' ),
			'classname'   => 'section-widget',
		));
	}

	public function widget( $args, $instance )
	{
		if ( empty( $instance['section'] ) )
		{
			return;
		}

		$the_query = new WP_Query( array
		(
			'p'         => $instance['section'],
			'post_type' => THEME_SECTIONS_POST_TYPE
		));

		if ( ! $the_query->have_posts() ) 
		{
			return;
		}

		echo $args['before_widget'];

		while ( $the_query->have_posts() ) 
		{
			$the_query->the_post();

			get_template_part( 'template-parts/content', get_post_type() );
		}

		wp_reset_postdata();

		echo $args['after_widget'];
	}

	public function form( $instance ) 
	{
		$section = ! empty( $instance['section'] ) ? absint( $instance['section'] ) : 0;
		
		$widget_title = $section ? get_post_field( 'post_title', $section ) : '';

		$posts = get_posts( array
		(
			'post_type'   => THEME_SECTIONS_POST_TYPE,
			'numberposts' => 999
		));

		// No sections found.

		if ( empty( $posts ) )
		{
			printf( '<p>%s <a href="%s" target="_blank">%s</a></p>', 
				esc_html__( 'No sections found.', 'theme' ),
				admin_url( 'post-new.php?post_type=' . urlencode( THEME_SECTIONS_POST_TYPE ) ),
				esc_html__( 'Add Section' ) );

			return;
		}

		// Input field with id is required for WordPress to display the title in the widget header.
		?>
		<input type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" value="<?php echo esc_attr( $widget_title ); ?>">
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'section' ) ); ?>"><?php esc_attr_e( 'Section:', 'theme' ); ?></label> 
			<select id="<?php echo esc_attr( $this->get_field_id( 'section' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'section' ) ); ?>">
				<option value=""><?php esc_html_e( '- Select -', 'theme' ); ?></option>
				<?php foreach ( $posts as $post ) : ?>
				<option value="<?php echo esc_attr( $post->ID ); ?>"<?php selected( $post->ID, $section ); ?>><?php echo esc_html( $post->post_title ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<?php 
	}

	public function update( $new_instance, $old_instance ) 
	{
		$instance = array();
		$instance['section'] = ! empty( $new_instance['section'] ) ? $new_instance['section'] : 0;
		
		return $instance;
	}
}

register_widget( 'Theme_Section_Widget' );
