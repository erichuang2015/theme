<?php
/**
 * Template Name: Full Width Fixed
 */

get_header(); ?>

<div class="container">

		<main id="main" class="site-main" role="main">

			<?php

			while ( have_posts() ) : the_post();

				// Load Post-Type-specific template.
				get_template_part( 'template-parts/content', get_post_type() );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.

			?>

		</main><!-- #main -->
		
	</div><!-- .container -->
</div><!-- #content-body -->

<?php
get_footer();
