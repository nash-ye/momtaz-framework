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

// Filters the comment form default arguments.
add_filter( 'comment_form_defaults', 'momtaz_comment_form_args' );

// Theme scripts.
add_action( 'wp_enqueue_scripts', 'momtaz_register_core_scripts'	);
add_action( 'wp_enqueue_scripts', 'momtaz_enqueue_core_scripts'		);

// Momtaz Init
add_action( 'momtaz_init', 'momtaz_register_core_stacks'	);
add_action( 'momtaz_init', 'momtaz_register_core_zones'		);
add_action( 'momtaz_init', 'momtaz_register_core_layouts'	);
add_action( 'momtaz_init', 'momtaz_ajust_current_layout'	);