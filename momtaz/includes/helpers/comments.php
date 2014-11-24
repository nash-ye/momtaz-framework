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

	$args = array();

	// Remove the notes after the form.
	$args['comment_notes_after'] = '';

	// Get current commenter's name, email, and URL.
	$commenter = wp_get_current_commenter();

	// Is the "Name" and "Email" fields are required?
	$required = (bool) get_option( 'require_name_email' );


	/*** Comment Form Fields **************************************************/

	$args['fields'] = Nmwdhj\create_elements( array(

		'author' => array(
			'atts'  => array( 'required' => (bool) $required ),
			'value' => $commenter['comment_author'],
			'label' => __( 'Name', 'momtaz' ),
			'type'  => 'input_text',
			'nid'   => 'author',
		),

		'email'	=> array(
			'atts'  => array( 'required' => (bool) $required ),
			'value' => $commenter['comment_author_email'],
			'label' => __( 'Email', 'momtaz' ),
			'type'  => 'input_email',
			'nid'   => 'email',
		),

		'url'	=> array(
			'value' => $commenter['comment_author_url'],
			'label' => __( 'Website', 'momtaz' ),
			'type'  => 'input_url',
			'nid'   => 'url',
		),

	) );

	// Shared
	foreach ( $args['fields'] as $k => $e ) {

		if ( $e->get_attr( 'required' ) ) {

			$label = $e->get_option( 'label' );

			if ( ! empty( $label ) ) {
				$label .= '<span class="required">*</span>';
				$e->set_option( 'label', $label );
			}

		}

		$e->set_options( array(
			'wrapper_atts'  => array( 'class' => 'form-section layout-columned' ),
			'label_atts'    => array( 'class' => 'form-label' ),
			'wrapper'       => 'div',
		), true );

		$e->set_atts( array(
			'class' => 'form-field regular-textbox',
			'size'  => 40,
		) );

		$args['fields'][ $k ] = $e->get_output();

	}

	// Comment Text
	$args['comment_field'] = Nmwdhj\create_element( array(
		'type'          => 'textarea',
		'nid'           => 'comment',
		'atts'          => array(
			'class'        => 'form-field large-textbox',
			'required'     => true,
			'rows'         => 10,
			'cols'         => 60,
		),
		'wrapper'       => 'div',
		'wrapper_atts'  => array(
			'class'        => 'form-section layout-full',
		),
	) )->get_output();


	$args = wp_parse_args( $args, $defaults );
	return $args;

}