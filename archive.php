<?php
/**
 * The template for displaying archive pages.
 */

get_header(); 
?>

<div class="container">
	<div class="row">

		<main id="main" class="site-main col" role="main">

			<?php

			if ( have_posts() ) :
				
				the_archive_title( '<header class="page-header"><h1 class="page-title">', '</h1></div>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );

				while ( have_posts() ) : the_post();

					// Load Post-Type-specific template.
					get_template_part( 'template-parts/content', get_post_type() );

				endwhile;

				the_posts_pagination();
			
			elseif : 
			
				get_template_part( 'template-parts/content', 'none' );
			
			endif;

			?>

		</main><!-- #main -->

		<?php get_sidebar( 'content' ); ?>
		<?php get_sidebar(); ?>

	</div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();
