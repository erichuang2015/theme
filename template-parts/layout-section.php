<?php 
/**
 * Section
 *
 * Advanced Custom Fields flexible content layout
 */

$post_id = get_sub_field( 'post_id' );

// Check if section has layouts
if ( theme_has_layouts( $post_id ) ) 
{
	// Render layouts
	theme_render_layouts( $post_id );

	return;
}

$the_query = new \WP_Query( array
(
	'p'         => $post_id,
	'post_type' => THEME_SECTIONS_POST_TYPE
));

if ( ! $the_query->have_posts() ) 
{
	return;
}

while ( $the_query->have_posts() ) 
{
	$the_query->the_post();

	if ( ! theme_has_container() ) 
	{
		echo '<div class="container">' . "\n";
	}

	get_template_part( 'template-parts/content', get_post_type() );

	if ( ! theme_has_container() ) 
	{
		echo '</div>' . "\n";
	}
}

wp_reset_postdata();
