<?php
/**
 * Posts Loop
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */

// If the query found some posts.
if ( have_posts() ) {

	momtaz_template_part( 'loop-meta' );

	Momtaz_Zones::call( 'loop:before' );

	while ( have_posts() ) {
		the_post(); // Setup the current post.
		momtaz_post_context_template( 'entry' );
	}

	Momtaz_Zones::call( 'loop:after' );

	momtaz_template_part( 'loop-nav' );

// The query failed or there is not any post.
} else {

	momtaz_template_part( 'loop-error' );

}