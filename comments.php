<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */

// Load's directly.
if ( ! defined( 'ABSPATH' ) ) die();

/*
 * If the visitor has not yet entered the post password if needed
 * will return early without loading the comments.
 */
if ( post_password_required() )
    return;

/* If no comments are given and comments/pings are closed, return. */
if ( ! have_comments() && ! comments_open() && ! pings_open() )
   return;

do_action( momtaz_format_hook( 'before_comments' ) ); ?>

    <section id="comments" class="comments-area">

        <?php

            // Load the comments-loop template.
            momtaz_template_part( 'comments-loop' );

            // Load the comment form.
            comment_form();

        ?>

    </section> <!-- #comments -->
    
<?php do_action( momtaz_format_hook( 'after_comments' ) );