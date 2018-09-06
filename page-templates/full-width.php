<?php
/**
 * Template Name: Full Width
 *
 * No left and right sidebar.
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
	
	<?php

		// The Loop
		while ( have_posts() )
		{
			the_post();

			// Include the Post-Type-specific template for the content.
			get_template_part( 'template-parts/content', get_post_type() );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() )
			{
				comments_template();
			}
		}

	?>

</main><!-- #main -->

<?php 
get_footer();
