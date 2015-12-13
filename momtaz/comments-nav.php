<?php
/**
 * Comments Navigation Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

if ( get_option( 'page_comments' ) && get_comment_pages_count() > 1 ) : ?>

	<nav<?php momtaz_atts( 'nav-comments', array( 'class' => 'navigation comment-navigation' ) ) ?>>

		<div class="previous"><?php previous_comments_link( __( '&larr; Older Comments', 'momtaz' ) ) ?></div>
		<div class="next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'momtaz' ) ) ?></div>

	</nav> <!-- .comment-navigation -->

<?php endif;
