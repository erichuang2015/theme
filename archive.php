<?php
/**
 * The template for displaying archive pages
 */

get_header(); 

?>
			
<div class="container">
	<div class="row">

		<main id="main" class="site-main col" role="main">
			
			<?php if ( have_posts() ) : ?>
			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
			<?php endif; ?>
			
			<?php

			if ( have_posts() ) :

				// The Loop
				while ( have_posts() )
				{
					the_post();

					// Include the Post-Type-specific template for the content.
					get_template_part( 'template-parts/content', get_post_type() );
				}

				theme_the_posts_pagination();

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
