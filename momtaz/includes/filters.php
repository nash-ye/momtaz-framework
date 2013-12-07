<?php
/*
 * Sets up the default filters and actions for most
 * of the Momtaz hooks.
 *
 * @package Momtaz
 * @subpackage Functions
 */

// Remove the not needed WP tags.
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'locale_stylesheet' );

// Make shortcodes aware on some WP Filters.
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'term_description', 'do_shortcode' );

// Extend the default body classes list.
add_filter( 'body_class', 'momtaz_body_class' );

// Set the default 'momtaz_title' callback.
add_action( 'momtaz_title', 'momtaz_wp_title' );
add_filter( 'wp_title', 'momtaz_filter_wp_title', 10, 2 );

// Adjusts the global $content_width variable.
add_action( 'template_redirect', 'momtaz_adjust_content_width' );

// Filters the comment form default arguments.
add_filter( 'comment_form_defaults', 'momtaz_comment_form_args' );

// Theme scripts.
add_action( 'wp_enqueue_scripts', 'momtaz_register_scripts' );
add_action( 'wp_enqueue_scripts', 'momtaz_enqueue_scripts'  );

// Theme Head tag.
add_action( momtaz_format_hook( 'head' ), 'momtaz_main_stylesheet',		1   );
add_action( momtaz_format_hook( 'head' ), 'momtaz_layout_stylesheet',   1   );
add_action( momtaz_format_hook( 'head' ), 'momtaz_locale_stylesheet',   1   );
add_action( momtaz_format_hook( 'head' ), 'momtaz_meta_generator',		10  );
add_action( momtaz_format_hook( 'head' ), 'momtaz_meta_designer',		10  );