<?php
/**
 * Custom template tags for this theme
 */

if ( ! function_exists( 'theme_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function theme_posted_on()
{

	// Get the author name; wrap it in a link.
	$byline = sprintf(
		/* translators: %s: post author */
		__( 'by %s', 'theme' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></span>'
	);

	// Finally, let's write all of this to the page.
	echo '<span class="posted-on">' . theme_time_link() . '</span><span class="byline"> ' . $byline . '</span>';
}
endif; // theme_posted_on


if ( ! function_exists( 'theme_time_link' ) ) :
/**
 * Gets a nicely formatted string for the published date.
 */
function theme_time_link()
{
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		get_the_date( DATE_W3C ),
		get_the_date(),
		get_the_modified_date( DATE_W3C ),
		get_the_modified_date()
	);

	// Wrap the time string in a link, and preface it with 'Posted on'.
	return sprintf(
		/* translators: %s: post date */
		__( '<span class="sr-only">Posted on</span> %s', 'theme' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);
}
endif; // theme_time_link

if ( ! function_exists( 'theme_edit_link' ) ) :
/**
 * Returns an accessibility-friendly link to edit a post or page.
 *
 * This also gives us a little context about what exactly we're editing
 * (post or page?) so that users understand a bit more where they are in terms
 * of the template hierarchy and their content. Helpful when/if the single-page
 * layout with multiple posts/pages shown gets confusing.
 */
function theme_edit_link()
{
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			__( 'Edit<span class="sr-only"> "%s"</span>', 'theme' ),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif; // theme_edit_link


if ( ! function_exists( 'theme_favicon' ) ) :
/**
 * Favicon
 *
 * @link https://en.wikipedia.org/wiki/Favicon
 * @link http://realfavicongenerator.net
 */
function theme_favicon()
{
	/**
     * size: 16×16, 32×32, 48×48 or 64×64
     * format: ico (supported for all browsers, also older browser versions)
     * ---------------------------------------------------------------
     */

    $attachment_id = theme_get_option( 'favicon' );

    if ( $attachment_id && get_post_type( $attachment_id ) )
    {
    	list( $image_url ) = wp_get_attachment_image_src( $attachment_id, 'favicon' );

    	printf( '<link rel="shortcut icon" href="%s">', esc_url( $image_url ) );
    }

    /**
     * Touch Icons
     *
     * Size: 180x180. Format: png.
     * ---------------------------------------------------------------
     */

    $attachment_id = theme_get_option( 'favicon_touch' );

    if ( $attachment_id && get_post_type( $attachment_id ) )
    {
    	list( $image_url ) = wp_get_attachment_image_src( $attachment_id, 'favicon_touch' );

    	printf( '<link rel="apple-touch-icon" href="%s">', esc_url( $image_url ) );
    }
}

add_action( 'wp_head', 'theme_favicon' );

endif; // theme_favicon

