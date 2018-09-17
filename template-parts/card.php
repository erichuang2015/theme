<?php
/**
 * Template part for displaying post contentinside a Bootstrap Card.
 *
 * @link http://getbootstrap.com/docs/4.1/components/card/
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>

	<?php the_post_thumbnail( 'large', array( 'class' => 'card-img-top' ) ); ?>

	<div class="card-body">
		
		<?php the_title( '<h5 class="entry-title card-title">', '</h5>' ); ?>

		<div class="entry-summary card-text">
			<?php the_excerpt(); ?>

			<p>
				<a href="<?php echo get_permalink() ?>" class="btn btn-primary"><?php esc_html_e( 'Read More', 'theme' ); ?></a>
			</p>

		</div><!-- .entry-summary -->

	</div><!-- .card-body -->
	
</article><!-- #post-## -->
