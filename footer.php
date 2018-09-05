<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 */

?>
	</div><!-- #content -->

	<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
	<footer id="colophon" class="site-footer widget-area" role="contentinfo">
		<div class="container">
			<div class="row">
				<?php dynamic_sidebar( 'sidebar-3' ); ?>
			</div>
		</div><!-- .container -->
	</footer><!-- #colophon -->
	<?php endif; ?>

</div><!-- #page -->
<?php wp_footer(); ?>

</body>
</html>
