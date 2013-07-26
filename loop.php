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

// If the query found some posts .
if ( have_posts() ) {

    // Load the loop-meta template.
    momtaz_template_part( 'loop-meta' );

    // @hook: Before the posts loop.
    do_action( momtaz_format_hook( 'before_loop' ) );

    // Loop through the query posts.
    while ( have_posts() ) { the_post();

        // Load the entry template.
        momtaz_post_context_template( 'entry' );

    } // end while

    // @hook: After the posts loop.
    do_action( momtaz_format_hook( 'after_loop' ) );

    // Load the loop-nav template.
    momtaz_template_part( 'loop-nav' );

// The query failed or there is not any post
} else {

    // Loop Error Template
    momtaz_template_part( 'loop-error' );

} // end if