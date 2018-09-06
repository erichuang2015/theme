<?php
/**
 * Template part for displaying page content
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header>' ); ?>

	<div class="entry-content">
		<?php

			the_content();

			wp_link_pages( array
			(
				'before' => '<div class="page-links">' . __( 'Pages:', 'theme' ),
				'after'  => '</div>',
			));
		?>
	</div><!-- .entry-content -->
	
</article><!-- #post-## -->
