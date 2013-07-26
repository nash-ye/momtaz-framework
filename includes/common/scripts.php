<?php
/**
 * Functions file for loading theme scripts.
 *
 * @package Momtaz
 * @subpackage Functions
 */

/**
 * Registers JavaScript files for the framework.  This function merely registers scripts with WordPress using
 * the wp_register_script() function.  It does not load any script files on the site.  If a theme wants to register
 * its own custom scripts, it should do so on the 'wp_enqueue_scripts' hook.
 *
 * @return void
 * @since 1.0
 */
function momtaz_register_scripts() {

    // Register LessCSS script.
    wp_register_script( 'less', momtaz_theme_uri( 'content/scripts/less.js' ), false, Momtaz::VERSION );

    // Register the drop-downs script.
    if ( current_theme_supports( 'momtaz-core-drop-downs' ) )
         wp_register_script( 'drop-downs', momtaz_theme_uri( 'content/scripts/dropdowns.js' ), array( 'jquery' ), Momtaz::VERSION, true );

} // end momtaz_register_scripts()

/**
 * Tells WordPress to load the scripts needed for the framework using the wp_enqueue_script() function.
 *
 * @return void
 * @since 1.0
 */
function momtaz_enqueue_scripts() {

    if ( momtaz_is_style_dev_mode() )
        wp_enqueue_script( 'less' );

    if ( current_theme_supports( 'momtaz-core-drop-downs' ) )
        wp_enqueue_script( 'drop-downs' );

    if ( is_singular() && get_option( 'thread_comments' ) && comments_open() )
        wp_enqueue_script( 'comment-reply' );

} // end momtaz_enqueue_scripts()