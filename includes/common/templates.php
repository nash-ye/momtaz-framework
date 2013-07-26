<?php
/*
 * Templates Functions.
 *
 * @package Momtaz
 * @subpackage Functions
 */

/*
 * Theme Template URI Functions.
 -----------------------------------------------------------------------------*/

/**
 * Retrieve the URI of a template file in the current theme.
 *
 * Searches in the child theme directory so themes which inherit from
 * a parent theme can just overload one file.
 *
 * @since 1.0
 */
function momtaz_theme_uri( $template_name = '' ){

    // Get the child theme URL.
    $template_uri = CHILD_THEME_URI;

    // Remove the slash from the beginning of the string.
    $template_name = ltrim( $template_name, '/' );

    if ( ! empty( $template_name ) ) {

        // If the file exists in the stylesheet (child theme) directory.
        if ( file_exists( trailingslashit( CHILD_THEME_DIR ) . $template_name ) ) {
            $template_uri = momtaz_child_theme_uri( $template_name );

        // No matter the file exists or not in the template (parent theme) directory.
        } else {
            $template_uri = momtaz_parent_theme_uri( $template_name );

        } // end-if

    } // end if

    return apply_filters( 'momtaz_theme_uri', $template_uri, $template_name );

} // end momtaz_theme_uri()

/**
 * Retrieve the URI of a template file in the child theme.
 *
 * @since 1.0
 */
function momtaz_child_theme_uri( $template_name = '' ){

    // Get the child theme URI.
    $template_uri = CHILD_THEME_URI;

    // Remove the slash from the beginning of the string.
    $template_name = ltrim( $template_name , '/' );

    if ( ! empty( $template_name ) )
        $template_uri = trailingslashit( $template_uri ) . $template_name;

    return apply_filters( 'momtaz_child_theme_uri', $template_uri, $template_name );

} // end momtaz_child_theme_uri()

/**
 * Retrieve the URI of a template file in the parent theme.
 *
 * @since 1.0
 */
function momtaz_parent_theme_uri( $template_name = '' ){

    // Get parent theme URI.
    $template_uri = THEME_URI;

    // Remove the slash from the beginning of the string.
    $template_name = ltrim( $template_name, '/' );

    if ( ! empty( $template_name ) )
        $template_uri = trailingslashit( $template_uri ) . $template_name;

    return apply_filters( 'momtaz_parent_theme_uri', $template_uri, $template_name );

} // end momtaz_parent_theme_uri()

/**
 * Retrieve the URI of the highest priority template file that exists.
 *
 * Searches in the registered template stacks so that locations
 * which have a high priority checked first.
 *
 * @since 1.0
 */
function momtaz_locate_template_uri( $template_names ) {

    $located = '';

    foreach ( (array) $template_names as $template_name ) {

        if ( empty( $template_name ) )
            continue;

        // Remove the slash from the beginning of the string.
        $template_name = ltrim( $template_name, '/' );

        // Loop through template stack
        foreach ( momtaz_get_template_stack() as $template_stack ) {

            if ( empty( $template_stack->path ) )
                continue;

            if ( file_exists( trailingslashit( $template_stack->path ) . $template_name ) ){
                $located = trailingslashit( $template_stack->uri ) . $template_name;
                break;
            } // end if

        } // end foreach

        if ( ! empty( $located ) )
            break;

    } // end foreach

    return $located;

} // end momtaz_locate_template_uri()

/*
 * Template Part Functions.
 -----------------------------------------------------------------------------*/

/**
 * Looks for a template based on the momtaz_get_context() function. The function looks for
 * templates based on the context of the current page being viewed by the user.
 *
 * @since 1.0
 */
function momtaz_context_template( $name, $slug = '', $load = true, $args = null ) {

    $context = array();

    foreach ( array_reverse( momtaz_get_context() ) as $value ) {

        if ( ! empty( $slug ) )
            $context[] = "{$slug}-{$value}";
        else
            $context[] = "{$value}";

    } // end foreach

    if ( ! empty( $slug ) )
        $context[] = $slug;

    return momtaz_template_part( $name, $context, $load, $args );

} // end momtaz_context_template()

/**
 * Looks for a template based on the momtaz_get_post_context() function. The function looks for
 * templates based on the context of the post data.
 *
 * @since 1.0
 */
function momtaz_post_context_template( $name, $slug = '', $post = 0, $load = true, $args = null ) {

    $context = array();

    if ( empty( $post ) )
        $post = get_the_ID();

    foreach ( array_reverse( momtaz_get_post_context( $post ) ) as $value ) {

        if ( ! empty( $slug ) )
            $context[] = "{$slug}-{$value}";
        else
            $context[] = "{$value}";

    } // end foreach

    if ( ! empty( $slug ) )
        $context[] = $slug;

    return momtaz_template_part( $name, $context, $load, $args );

} // end momtaz_post_context_template()

/**
 * A more powerfull version of get_template_part() funcation .
 *
 * @see get_template_part()
 * @since 1.0
 */
function momtaz_template_part( $name, $context = '', $load = true, $args = null ) {

    $template_names = array();

    if ( empty( $name ) )
        return false;

    $is_dir = is_dir( momtaz_locate_template( $name, false ) );

    do_action( 'momtaz_template_part', $name, $context, $load );

    foreach ( (array) $context as $slug ) {

        $slug = untrailingslashit( $slug );

        if ( $is_dir )
            $template_names[] = "{$name}/{$slug}.php";
        else
            $template_names[] = "{$name}-{$slug}.php";

    } // end foreach

    $template_names[] = ($is_dir) ? "{$name}/default.php" : "{$name}.php";

    // Locate the highest priority template file.
    return momtaz_locate_template( $template_names, $load, false, $args );

} // end momtaz_template_part()

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the registered template stacks so that locations
 * which have a high priority checked first.
 *
 * @since 1.1
 */
function momtaz_locate_template( $template_names, $load = false, $load_once = true, $args = null ) {

    // No file found yet
    $located = '';

    // Try to find a template file
    foreach ( (array) $template_names as $template_name ) {

        // Continue if template is empty
        if ( empty( $template_name ) )
            continue;

        // Trim off any slashes from the template name
        $template_name  = ltrim( $template_name, '/' );

        // Loop through template stack
        foreach ( momtaz_get_template_stack() as $template_stack ) {

            if ( empty( $template_stack->path ) )
                continue;

            if ( file_exists( trailingslashit( $template_stack->path ) . $template_name ) ) {
                $located = trailingslashit( $template_stack->path ) . $template_name;
                break;
            } // end if

        } // end foreach

        if ( ! empty( $located ) )
            break;

    } // end foreach

    // Maybe load the template if one was located
    if ( $load && ! empty( $located ) )
        momtaz_load_template( $located, $load_once, $args );

    return $located;

} // end momtaz_locate_template()

/**
 * Require the template file with optional arguments.
 *
 * This function doesn't setup the WordPress environment variables,
 * simply you must use the 'global' operator to use the needed
 * variables in your templates.
 *
 * @since 1.1
 */
function momtaz_load_template( $_template, $_load_once = true, $_args = null ) {

    if ( empty( $_template ) )
        return;

    $_load_once = (bool) $_load_once;

    ( $_load_once ) ? require_once( $_template ) : require( $_template );

} // end momtaz_load_template()

/*
 * Template Stack Functions.
 -----------------------------------------------------------------------------*/

/**
 * Get the template stack list.
 *
 * @return array
 * @since 1.1
 */
function momtaz_get_template_stack( $slug = '' ) {

    $list = array();
    $momtaz = momtaz();

    if ( isset( $momtaz->template_stack ) ) {

        if ( empty( $slug ) ) {
            $list = $momtaz->template_stack;

        } else {

            $slug = sanitize_key( $slug );

            if ( isset( $momtaz->template_stack[ $slug ] ) )
                $list = $momtaz->template_stack[ $slug ];

        } // end if

    } // end if

    return $list;

} // end momtaz_get_template_stack()

/**
 * Register a template stack location.
 *
 * @return boolean
 * @since 1.1
 */
function momtaz_register_template_stack( $stack ){

    $momtaz = momtaz();

    $stack = (object) wp_parse_args( $stack, array(
        'priority' => 10,
        'slug' => '',
        'path' => '',
        'uri' => '',
     ) );

    $stack->slug = sanitize_key( $stack->slug );

    if ( empty( $stack->slug ) )
        return false;

    $momtaz->template_stack[$stack->slug] = $stack;

    usort( $momtaz->template_stack, function( $a, $b ) {

        $p1 = (int) $a->priority;
        $p2 = (int) $b->priority;

        if ( $p1 === $p2 )
            return 0;

        return ( $p1 > $p2 ) ? +1 : -1;

    } );

    return true;

} // end momtaz_register_template_stack()

/**
 * Deregister a template stack.
 *
 * @return boolean
 * @since 1.1
 */
function momtaz_deregister_template_stack( $slug ){

    $slug = sanitize_key( $slug );

    if ( empty( $slug ) )
        return false;

    unset( momtaz()->template_stack[$slug] );

    return true;

} // end momtaz_deregister_template_stack()