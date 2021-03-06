<?php
/**
 * Template part for displaying post content
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<header class="entry-header">
		<?php

		if ( 'post' === get_post_type() ) 
		{
			echo '<div class="entry-meta">';

			if ( is_single() ) 
			{
				theme_posted_on();
			};

			echo '</div><!-- .entry-meta -->';
		};

		if ( is_single() ) 
		{
			the_title( '<h1 class="entry-title">', '</h1>' );
		} 

		elseif ( is_front_page() && is_home() ) 
		{
			the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
		} 

		else 
		{
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}

		?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() && ! is_single() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'theme-featured-image' ); ?>
			</a>
		</div><!-- .post-thumbnail -->
	<?php endif; ?>

	<?php if ( is_single() ) : ?>

	<div class="entry-content">
		<?php

		/* translators: %s: Name of current post */
		the_content( sprintf( '%s<span class="sr-only"> "%s"</span>', __( 'Continue reading', 'theme' ), get_the_title() ) );

		wp_link_pages( array
		(
			'before'      => '<div class="page-links">' . __( 'Pages:', 'theme' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		));

		?>
	</div><!-- .entry-content -->

	<?php else : ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<?php endif ?>

</article><!-- #post-## -->
