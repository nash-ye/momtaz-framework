<?php

/**
 * Uses the $comment_type to determine which comment template should be used. Once the
 * template is located, it is loaded for use.
 *
 * @since 1.0
 * @param $comment The comment variable
 * @param $args Array of arguments passed from wp_list_comments()
 * @param $depth What level the particular comment is
 */
function momtaz_comments_callback( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $depth;
	$GLOBALS['max_depth'] = $args['max_depth'];

	// Locate the template based on the $comment_type. Default to 'comment.php'.
	momtaz_template_part( 'comment', get_comment_type( $comment->comment_ID ) );

}

add_filter( 'comment_form_defaults', 'momtaz_comment_form_args' );

/**
 * Filters the WordPress comment_form() function that was added in WordPress 3.0.  This allows
 * the theme to preserve some backwards compatibility with its old comment form.  It also allows
 * users to build custom comment forms by filtering 'comment_form_defaults' in their child theme.
 *
 * @since 1.0
 * @param array $defaults The default comment form arguments.
 * @return array $args The filtered comment form arguments.
 */
function momtaz_comment_form_args( $defaults ) {
	return $defaults;
}
