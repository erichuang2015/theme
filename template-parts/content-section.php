<?php
/**
 * Template part for displaying section content
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php the_title( '<header class="entry-header"><h2 class="entry-title">', '</h2></header>' ); ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
	
</article><!-- #post-## -->
