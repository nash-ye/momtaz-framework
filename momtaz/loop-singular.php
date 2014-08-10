<?php
/**
 * Singular Post Loop.
 *
 * The loop displays the post and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

// If the query found some posts.
if ( have_posts() ) {

	// Before Loop Zone.
	Momtaz_Zones::call( 'loop:before' );

	// Loop through the query posts.
	while ( have_posts() ) {

		the_post(); // Setup the current post.
		momtaz_post_context_template( 'entry', 'singular' );

	} // end while

	// After Loop Zone.
	Momtaz_Zones::call( 'loop:after' );

// The query failed or there is not any post
} else {

	// Loop Error Template
	momtaz_template_part( 'loop-error' );

} // end if