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

} // end momtaz_comments_callback()

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
    $required = false;

    // Remove the notes after the form.
    $args['comment_notes_after'] = '';

    // Get current commenter's name, email, and URL.
    $commenter = wp_get_current_commenter();

    // Is the "Name" and "Email" fields are required?
    if ( (bool) get_option( 'require_name_email' ) )
        $required = '<span class="required">*</span>';

    // Load the Momtaz Nmwdhj module.
    Momtaz_Modules::load_module( 'momtaz-nmwdhj' );

    
    /*** Comment Author Fields ************************************************/

    $args['fields']['author'] = Momtaz_Nmwdhj::create_element( 'input_text' )
            ->set_atts( array(
                'class' => 'regular-textbox form-field',
                'required' => (bool) $required,
                'size' => 40,
              ) )
            ->set_value( $commenter['comment_author'] )
            ->set_NID( 'author' );

    $args['fields']['author'] = Momtaz_Nmwdhj::decorate_element( 'label', $args['fields']['author'] )
            ->set_label( __( 'Name', 'momtaz' ) . $required );


    $args['fields']['email'] = Momtaz_Nmwdhj::create_element( 'input_email' )
            ->set_atts( array(
                'class' => 'regular-textbox form-field',
                'required' => (bool) $required,
                'size' => 40,
              ) )
            ->set_value( $commenter['comment_author_email'] )
            ->set_NID( 'email' );

    $args['fields']['email'] = Momtaz_Nmwdhj::decorate_element( 'label', $args['fields']['email'] )
            ->set_label( __( 'Email', 'momtaz' ) . $required );


    $args['fields']['url'] = Momtaz_Nmwdhj::create_element( 'input_url' )
            ->set_atts( array( 'class' => 'regular-textbox form-field', 'size' => 40 ) )
            ->set_value( $commenter['comment_author_url'] )
            ->set_NID( 'url' );

    $args['fields']['url'] = Momtaz_Nmwdhj::decorate_element( 'label', $args['fields']['url'] )
            ->set_label( __( 'Website', 'momtaz' ) );


    foreach ( $args['fields'] as $key => $field ) {

        $field->set_label_atts( array( 'class' => 'form-label' ) );

        $args['fields'][ $key ] = Momtaz_Nmwdhj::decorate_element( 'tag', $field )
                ->set_wrapper_atts( array( 'class' => 'form-section layout-columned' ) )
                ->set_wrapper_tag( 'div' );

    } // end foreach

    $args['fields'] = array_map( 'momtaz_nmwdhj_get_element_output', $args['fields'] );


    /*** Comment Text Field ***************************************************/

    $args['comment_field'] = Momtaz_Nmwdhj::create_element( 'textarea' )
            ->set_atts( array(
                'class' => 'large-textbox form-field',
                'required' => true,
                'rows' => 10,
                'cols' => 60,
              ) )
            ->set_NID( 'comment' );

    $args['comment_field'] = Momtaz_Nmwdhj::decorate_element( 'tag', $args['comment_field'] )
            ->set_wrapper_atts( array( 'class' => 'form-section layout-full' ) )
            ->set_wrapper_tag( 'div' )
            ->get_output();

    // Return the new comment form args.
    return wp_parse_args( $args, $defaults );

} // end momtaz_comment_form_args()