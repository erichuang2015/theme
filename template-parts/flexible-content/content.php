<?php 
/**
 * Content
 *
 * Advanced Custom Fields flexible content layout
 */

$content    = get_sub_field( 'content' );
$full_width = get_sub_field( 'full_width' );

if ( ! theme_is_full_width() ) 
{
	$full_width = true;
}

?>

<div class="content">

	<?php if ( ! theme_has_container() ) : ?>
	<div class="container">
	<?php endif; ?>

		<?php if ( ! $full_width ) : ?>
		<div class="row">
			<div class="col-lg-9">
		<?php endif; ?>

		<?php echo $content; ?>

		<?php if ( ! $full_width ) : ?>
			</div><!-- .col-lg-9 -->
		</div><!-- .row -->
		<?php endif; ?>

	<?php if ( ! theme_has_container() ) : ?>
	</div><!-- .container -->
	<?php endif; ?>

</div><!-- .content -->
