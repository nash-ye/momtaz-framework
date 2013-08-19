<?php
/**
 * The core functions file for the Momtaz framework. Functions defined here are generally
 * used across the entire framework to make various tasks faster. This file should be loaded
 * prior to any other files because its functions are needed to run the framework.
 *
 * @package Momtaz
 * @subpackage Functions
 */

/**
 * Gets the parent theme textdomain ID and path.
 *
 * @since 1.1
 * @return array
 */
function momtaz_get_parent_theme_textdomain(){

    // Get the parent theme object.
    $theme = wp_get_theme( get_template() );

    return apply_filters( 'momtaz_parent_theme_textdomain', array(
        'path' => $theme->get( 'DomainPath' ),
        'id' => $theme->get( 'TextDomain' ),
    ) );

} // momtaz_get_parent_theme_textdomain()

/**
 * Loads the parent theme translation files.
 *
 * @since 1.1
 * @return bool
 */
function momtaz_load_parent_theme_textdomain(){

    $textdomain = momtaz_get_parent_theme_textdomain();

    if ( empty( $textdomain['id'] ) )
        return false;

    // Load the theme's translated strings.
    return load_theme_textdomain( $textdomain['id'] );

} // momtaz_load_parent_theme_textdomain()

/**
 * Gets the child theme textdomain ID and path.
 *
 * @since 1.1
 * @return array
 */
function momtaz_get_child_theme_textdomain(){

    $textdomain = array();

    if ( is_child_theme() ) {

        $theme = wp_get_theme( get_stylesheet() );

        $textdomain = array(
            'path' => $theme->get( 'DomainPath' ),
            'id' => $theme->get( 'TextDomain' ),
        );


    } // end if

    return apply_filters( 'momtaz_get_child_theme_textdomain', $textdomain );

} // momtaz_get_child_theme_textdomain()

/**
 * Loads the child theme translation files.
 *
 * @since 1.1
 * @return bool
 */
function momtaz_load_child_theme_textdomain(){

    $textdomain = momtaz_get_child_theme_textdomain();

    if ( empty( $textdomain['id'] ) )
        return false;

    return load_theme_textdomain( $textdomain['id'] );

} // momtaz_load_child_theme_textdomain()

add_filter( 'load_textdomain_mofile', 'momtaz_load_textdomain_mofile', 10, 2 );

/**
 * Filters the 'load_textdomain_mofile' filter hook so that we can change the directory and file name
 * of the mofile for translations.  This allows child themes to have a folder called /languages with translations
 * of their parent theme so that the translations aren't lost on a parent theme upgrade.
 *
 * @since 1.0
 * @return string
 */
function momtaz_load_textdomain_mofile( $mofile, $domain ) {

    $locale = apply_filters( 'theme_locale', get_locale(), $domain );

    foreach( array( 'parent', 'child' ) as $source ) {

        switch( $source ) {

            case 'parent':
                $textdomain = momtaz_get_parent_theme_textdomain();
                break;

            case 'child':
                $textdomain = momtaz_get_child_theme_textdomain();
                break;

        } // end Switch

        if ( $textdomain['id'] === $domain ) {

            $path = trailingslashit( $textdomain['path'] ) . "{$domain}-{$locale}.mo";

            if ( ( $path = locate_template( $path ) ) )
                $mofile = $path;

            break; // Stop looping!

        } // end if

    } // end foreach

    return $mofile;

} // end momtaz_load_textdomain_mofile()

/**
 * Function for formatting a hook name if needed. It automatically adds the
 * theme's prefix to begining of the hook name.
 *
 * @since 1.0
 * @access public
 * @param string $tag The basic name of the hook (e.g., 'before_header').
 */
function momtaz_format_hook( $tag ) {
    return THEME_PREFIX . "_{$tag}";
} // end momtaz_format_hook()