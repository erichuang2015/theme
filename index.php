<?php
/**
 * The main template file
 */

get_header(); 
?>

<div class="container">
	<div class="row">

		<main id="main" class="site-main col" role="main">

			<?php

			if ( have_posts() ) :
			
				while ( have_posts() ) : the_post();

					// Load Post-Type-specific template.
					get_template_part( 'template-parts/content', get_post_type() );

				endwhile;

				the_posts_pagination( array
				(
					'prev_text'          => sprintf( '%s<span class="sr-only">%s</span>', theme_get_icon( 'arrow-left' ), __( 'Previous page', 'theme' ) ),
					'next_text'          => sprintf( '<span class="sr-only">%s</span>%s', __( 'Next page', 'theme' ), theme_get_icon( 'arrow-right' ) ),
					'before_page_number' => sprintf( '<span class="meta-nav sr-only">%s</span>', __( 'Next page', 'theme' ) ),
				));
			
			else : 
			
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
