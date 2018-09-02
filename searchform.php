<?php
/**
 * Template for displaying search forms
 */

?>

<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">

	<div class="input-group">
		<label for="<?php echo $unique_id; ?>">
			<span class="sr-only"><?php echo _x( 'Search for:', 'label', 'theme' ); ?></span>
		</label>
		<input type="search" id="<?php echo $unique_id; ?>" class="form-control" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'theme' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
		<div class="input-group-append">
			<button type="submit" class="btn btn-outline-secondary">
				<?php echo theme_get_svg( 'icon-search' ); ?><span class="sr-only"><?php esc_html_e( 'Search', 'theme' ); ?></span>
			</button>
		</div>
	</div>
	
</form>
