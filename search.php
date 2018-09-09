<?php
/**
 * The template for displaying search results pages
 */

get_header(); 

?>

<div class="container">
	<div class="row">

		<main id="main" class="site-main col" role="main">
			
			<?php

			if ( have_posts() ) :

				?>

				<header class="page-header">
					<?php if ( have_posts() ) : ?>
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'theme' ), '<small>' . get_search_query() . '</small>' ); ?></h1>
					<?php else : ?>
					<h1 class="page-title"><?php _e( 'Nothing Found', 'theme' ); ?></h1>
					<?php endif; ?>
				</header><!-- .page-header -->

				<?php

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
