<?php
/**
 * The template for displaying search results pages
 */

get_header(); 
get_sidebar( 'content-top' );
?>

<div class="container">
	<div class="row">

		<main id="main" class="site-main col" role="main">

			<header class="page-header">
				<?php if ( have_posts() ) : ?>
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'theme' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<?php else : ?>
					<h1 class="page-title"><?php _e( 'Nothing Found', 'theme' ); ?></h1>
				<?php endif; ?>
			</header><!-- .page-header -->
			
			<?php

			if ( have_posts() ) :
				
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/content', get_post_type() );

				endwhile;

				the_posts_pagination( array
				(
					'prev_text'          => sprintf( '%s<span class="sr-only">%s</span>', theme_get_icon( 'arrow-left' ), __( 'Previous page', 'theme' ) ),
					'next_text'          => sprintf( '<span class="sr-only">%s</span>%s', __( 'Next page', 'theme' ), theme_get_icon( 'arrow-right' ) ),
					'before_page_number' => sprintf( '<span class="meta-nav sr-only">%s</span>', __( 'Next page', 'theme' ) ),
				));

			else :
			?>

				<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'theme' ); ?></p>
				<?php get_search_form(); ?>

			<?php endif; ?>

		</main><!-- #main -->

	<?php get_sidebar( 'content' ); ?>
	<?php get_sidebar(); ?>

	</div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();
