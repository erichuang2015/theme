<?php
/**
 * Template part for displaying page content in page.php
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php 

		if ( is_page() ) :
			the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header>' );
		else :

			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

	?>

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
