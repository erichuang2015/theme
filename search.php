<?php
/**
 * The template for displaying search results pages
 */

get_header(); ?>

<div class="container">
	<div class="row">

		<main id="main" class="site-main col" role="main">
			
			<header class="page-header">
				<?php if ( have_posts() ) : ?>
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'theme' ), '<small>' . get_search_query() . '</small>' ); ?></h1>
				<?php else : ?>
				<h1 class="page-title"><?php _e( 'Nothing Found', 'theme' ); ?></h1>
				<?php endif; ?>
			</header><!-- .page-header -->
			
			<?php

			if ( have_posts() ) :


				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );

				// The Loop
				while ( have_posts() )
				{
					the_post();

					// Include the Post-Type-specific template for the content.
					get_template_part( 'template-parts/content', get_post_type() );
				}

				the_posts_pagination( array
				(
					'prev_text'          => theme_get_icon( array( 'icon' => 'arrow-left' ) ) . '<span class="sr-only">' . __( 'Previous page', 'theme' ) . '</span>',
					'next_text'          => '<span class="sr-only">' . __( 'Next page', 'theme' ) . '</span>' . theme_get_icon( array( 'icon' => 'arrow-right' ) ),
					'before_page_number' => '<span class="meta-nav sr-only">' . __( 'Page', 'theme' ) . ' </span>',
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
