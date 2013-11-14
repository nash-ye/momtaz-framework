<?php
/**
 * Functions for handling how comments are displayed and used on the site. This allows more
 * precise control over their display and makes more filter and action hooks available to developers
 * to use in their customizations.
 *
 * @package Momtaz
 * @subpackage Functions
 */

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

/**
 * Filters the WordPress comment_form() function that was added in WordPress 3.0.  This allows
 * the theme to preserve some backwards compatibility with its old comment form.  It also allows
 * users to build custom comment forms by filtering 'comment_form_defaults' in their child theme.
 *
 * @since 1.0
 * @param array $args The default comment form arguments.
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

	// Load the Momtaz Nmwdhj module.
	Momtaz_Modules::load_module( 'momtaz-nmwdhj' );

	// Commenter Name
	$args['fields']['author'] = Nmwdhj\create_element( 'input_text' )
			->set_value( $commenter['comment_author'] )
			->set_attr( 'required', (bool) $required )
			->set_NID( 'author' );

	Nmwdhj\decorate_element( 'label', $args['fields']['author'] )
			->set_label( __( 'Name', 'momtaz' ) );

	// Commenter Email
	$args['fields']['email'] = Nmwdhj\create_element( 'input_email' )
			->set_value( $commenter['comment_author_email'] )
			->set_attr( 'required', (bool) $required )
			->set_NID( 'email' );

	Nmwdhj\decorate_element( 'label', $args['fields']['email'] )
			->set_label( __( 'Email', 'momtaz' ) );

	// Commenter URL
	$args['fields']['url'] = Nmwdhj\create_element( 'input_url' )
			->set_value( $commenter['comment_author_url'] )
			->set_NID( 'url' );

	Nmwdhj\decorate_element( 'label', $args['fields']['url'] )
			->set_label( __( 'Website', 'momtaz' ) );

	// Shared
	foreach ( $args['fields'] as &$field ) {

		$field
				->set_label_attr( 'class', 'form-label' )
				->set_atts( array(
					'class' => 'form-field regular-textbox',
					'size' => 40,
				) );

		Nmwdhj\decorate_element( 'tag', $field )
				->set_wrapper_attr( 'class', 'form-section layout-columned' );

		if ( $field->has_attr( 'required' ) && ( $label = $field->get_label() ) ) {

			$label .= '<span class="required">*</span>';
			$field->set_label( $label );

		} // end if

		$field = $field->get_output();

	} // end foreach

	// Comment Text
	$args['comment_field'] = Nmwdhj\create_element( 'textarea' )
			->set_NID( 'comment' )
			->set_atts( array(
				'class' => 'form-field large-textbox',
				'required' => true,
				'rows' => 10,
				'cols' => 60,
			) );

	Nmwdhj\decorate_element( 'tag', $args['comment_field'] )
			->set_wrapper_attr( 'class', 'form-section layout-full' );

	$args['comment_field'] = $args['comment_field']->get_output();


	$args = wp_parse_args( $args, $defaults );
	return $args;

}