<?php if ( ! function_exists( 'get_field' ) ) return; // Dependency check
/**
 * Main Visual
 */

// Check support
if ( ! current_theme_supports( 'theme-main-visuals' ) ) 
{
	return;
}

// Check active
if ( ! theme_has_main_visual() ) 
{
	return;
}

$title          = get_field( 'main_visual_title' );
$content        = get_field( 'main_visual_content' );
$image          = get_field( 'main_visual_image' );
$image_position = get_field( 'main_visual_image_position' );

list( $image_url ) = wp_get_attachment_image_src( $image, 'theme-full-width' );

// Attributes

$atts = array
(
	'id'    => 'main-visual',
	'class' => 'py-6 bg-light vh-65 d-flex align-items-center'
);

if ( $image_url )
{
	$atts['style'] = sprintf( 'background-image: url(%s);', esc_url( $image_url ) );
	$atts['class'] .= sprintf( ' text-white bg-size-cover bg-position-%s', sanitize_html_class( $image_position ) );
}

?>

<div<?php echo theme_esc_attr( $atts ); ?>>
	<div class="container">
		<div class="row">
			<div class="col-lg-7">

				<?php if ( $title ) : ?>
				<h1 class="display-4 m-0"><?php echo esc_html( $title ); ?></h1>
				<?php endif; ?>

				<?php if ( $content ) : ?>
				<div class="lead mt-3">
					<?php echo $content; ?>
				</div>
				<?php endif; ?>

			</div><!-- .col-lg-7 -->
		</div><!-- .row -->
	</div><!-- .container -->
</div><!-- .main-visual -->
