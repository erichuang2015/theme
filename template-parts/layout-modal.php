<?php 
/**
 * Modal
 *
 * Advanced Custom Fields flexible content layout
 */

$id      = get_sub_field( 'id' );
$title   = get_sub_field( 'title' );
$content = get_sub_field( 'content' );
$size    = get_sub_field( 'size' );

?>

<div id="<?php echo esc_attr( $id ); ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="<?php echo esc_attr( $id ); ?>Title" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-<?php echo esc_attr( $size ); ?>" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<?php if ( $title ) : ?>
				<h5 class="modal-title" id="<?php echo esc_attr( $id ); ?>Title"><?php echo esc_html( $title ); ?></h5>
				<?php endif; ?>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'theme' ); ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div><!-- .modal-header -->

			<div class="modal-body">
				<?php echo $content; ?>
			</div><!-- .modal-body -->

		</div><!-- .modal-content -->
	</div><!-- .modal-dialog -->
</div><!-- .modal -->
