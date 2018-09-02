<?php
/**
 * The template for displaying all single posts
 */

get_header(); 
?>

<div class="container">
	<div class="row">

		<main id="main" class="site-main col" role="main">

			<?php

			while ( have_posts() ) : the_post();

				// Load Post-Type-specific template.
				get_template_part( 'template-parts/content', get_post_type() );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

				the_post_navigation( array
				(
					'prev_text' => __( 'Previous Post', 'theme' ) . '<span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'theme' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => __( 'Next Post', 'theme' ) . '<span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'theme' ) . '</span> <span class="nav-title">%title</span>',
				));

			endwhile; // End of the loop.

			?>

		</main><!-- #main -->

		<?php get_sidebar( 'content' ); ?>
		<?php get_sidebar(); ?>

	</div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();
