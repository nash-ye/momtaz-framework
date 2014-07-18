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
 * @return string
 * @since 1.0
 */
function momtaz_theme_uri( $name = '' ){

	$name = ltrim( $name, '/' );

	// If the file exists in the stylesheet (child theme) directory.
	if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $name ) ) {
		$uri = momtaz_child_theme_uri( $name );

	// No matter the file exists or not in the template (parent theme) directory.
	} else {
		$uri = momtaz_parent_theme_uri( $name );

	}

	$uri = apply_filters( 'momtaz_theme_uri', $uri, $name );
	return $uri;

}

/**
 * Retrieve the URI of a template file in the child theme.
 *
 * @return string
 * @since 1.0
 */
function momtaz_child_theme_uri( $name = '' ){

	$name = ltrim( $name, '/' );
	$uri = get_stylesheet_directory_uri();

	if ( ! empty( $name ) ) {
		$uri = trailingslashit( $uri ) . $name;
	}

	$uri = apply_filters( 'momtaz_child_theme_uri', $uri, $name );
	return $uri;

}

/**
 * Retrieve the URI of a template file in the parent theme.
 *
 * @return string
 * @since 1.0
 */
function momtaz_parent_theme_uri( $name = '' ){

	$name = ltrim( $name, '/' );
	$uri = get_template_directory_uri();

	if ( ! empty( $name ) ) {
		$uri = trailingslashit( $uri ) . $name;
	}

	$uri = apply_filters( 'momtaz_parent_theme_uri', $uri, $name );
	return $uri;

}

/**
 * Retrieve the URI of the highest priority template file that exists.
 *
 * Searches in the registered template stacks so that locations
 * which have a high priority checked first.
 *
 * @return string
 * @since 1.0
 */
function momtaz_locate_template_uri( $template_names ) {

	$located = '';

	foreach ( (array) $template_names as $template_name ) {

		if ( empty( $template_name ) ) {
			continue;
		}

		// Remove the slash from the beginning of the string.
		$template_name = ltrim( $template_name, '/' );

		// Loop through template stack
		foreach ( Momtaz_Stacks::get() as $template_stack ) {

			if ( empty( $template_stack->path ) ) {
				continue;
			}

			if ( file_exists( trailingslashit( $template_stack->path ) . $template_name ) ){
				$located = trailingslashit( $template_stack->uri ) . $template_name;
				break;
			}

		}

		if ( ! empty( $located ) ) {
			break;
		}

	}

	return $located;

}

/*
 * Template Part Functions.
 -----------------------------------------------------------------------------*/

/**
 * Looks for a template based on the momtaz_get_context() function. The function looks for
 * templates based on the context of the current page being viewed by the user.
 *
 * @since 1.0
 */
function momtaz_context_template( $name, $slug = '', $load = true, $_args = null ) {

	$context = array();

	foreach ( array_reverse( momtaz_get_context() ) as $value ) {

		if ( ! empty( $slug ) ) {
			$context[] = "{$slug}-{$value}";
		} else {
			$context[] = "{$value}";
		}

	}

	if ( ! empty( $slug ) ) {
		$context[] = $slug;
	}

	return momtaz_template_part( $name, $context, $load, $_args );

}

/**
 * Looks for a template based on the momtaz_get_post_context() function. The function looks for
 * templates based on the context of the post data.
 *
 * @since 1.0
 */
function momtaz_post_context_template( $name, $slug = '', $post = 0, $load = true, $_args = null ) {

	$context = array();

	if ( empty( $post ) ) {
		$post = get_the_ID();
	}

	foreach ( array_reverse( momtaz_get_post_context( $post ) ) as $value ) {

		if ( ! empty( $slug ) ) {
			$context[] = "{$slug}-{$value}";
		} else {
			$context[] = "{$value}";
		}

	}

	if ( ! empty( $slug ) ) {
		$context[] = $slug;
	}

	return momtaz_template_part( $name, $context, $load, $_args );

}

/**
 * A more powerfull version of get_template_part() funcation.
 *
 * @see get_template_part()
 * @since 1.0
 */
function momtaz_template_part( $name, $context = '', $load = true, $_args = null ) {

	$template_names = array();

	if ( empty( $name ) ) {
		return false;
	}

	$is_dir = is_dir( momtaz_locate_template( $name, false ) );

	do_action( 'momtaz_template_part', $name, $context, $load );

	foreach ( (array) $context as $slug ) {

		$slug = untrailingslashit( $slug );

		if ( $is_dir ) {
			$template_names[] = "{$name}/{$slug}.php";
		} else {
			$template_names[] = "{$name}-{$slug}.php";
		}

	}

	$template_names[] = ($is_dir) ? "{$name}/default.php" : "{$name}.php";

	// Locate the highest priority template file.
	return momtaz_locate_template( $template_names, $load, false, $_args );

}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the registered template stacks so that locations
 * which have a high priority checked first.
 *
 * @since 1.1
 */
function momtaz_locate_template( $template_names, $load = false, $load_once = true, $_args = null ) {

	// No file found yet
	$located = '';

	// Try to find a template file
	foreach ( (array) $template_names as $template_name ) {

		// Continue if template is empty
		if ( empty( $template_name ) ) {
			continue;
		}

		// Trim off any slashes from the template name
		$template_name  = ltrim( $template_name, '/' );

		// Loop through template stack
		foreach ( Momtaz_Stacks::get() as $template_stack ) {

			if ( empty( $template_stack->path ) ) {
				continue;
			}

			if ( file_exists( trailingslashit( $template_stack->path ) . $template_name ) ) {
				$located = trailingslashit( $template_stack->path ) . $template_name;
				break;
			}

		}

		if ( ! empty( $located ) ) {
			break;
		}

	}

	// Maybe load the template if one was located
	if ( $load && ! empty( $located ) ) {
		momtaz_load_template( $located, $load_once, $_args );
	}

	return $located;

}

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

	if ( empty( $_template ) ) {
		return;
	}

	$_load_once = (bool) $_load_once;

	( $_load_once ) ? require_once( $_template ) : require( $_template );

}