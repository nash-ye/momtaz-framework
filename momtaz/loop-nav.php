<?php
/**
 * Posts Loop Navigation Links Template
 *
 * This template is used to show your your next/previous post links on singular pages and
 * the next/previous posts links on the home/posts page and archive pages.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */

if ( is_attachment() ) : ?>

        <nav class="navigation">
            <?php previous_post_link( '%link', '<span class="previous">' . __( '&laquo; Return to entry', 'momtaz' ) . '</span>' ); ?>
        </nav> <!-- .navigation -->

<?php elseif ( is_singular( 'post' ) ) : ?>

        <nav class="navigation post-navigation" role="navigation">

            <?php previous_post_link( '%link', '<span class="previous">' . __( '&laquo; Previous', 'momtaz' ) . '</span>' ); ?>
            <?php next_post_link( '%link', '<span class="next">' . __( 'Next &raquo;', 'momtaz' ) . '</span>' ); ?>

        </nav> <!-- .post-navigation -->

<?php elseif ( ! is_singular() ) :

        global $wp_query;

        if ( function_exists( 'loop_pagination' ) ) {

            loop_pagination(); // Display the loop pagination.

        } elseif ( $wp_query->max_num_pages > 1 ) { ?>

            <nav class="navigation loop-navigation">
                <span class="previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older', 'momtaz' ) ); ?></span>
                <span class="next"><?php previous_posts_link( __( 'Newer <span class="meta-nav">&rarr;</span>', 'momtaz' ) ); ?></span>
            </nav> <!-- .loop-navigation --> <?php

        } // end if

endif;