<?php
/**
 * The core functions file for the Momtaz framework. Functions defined here are generally
 * used across the entire framework to make various tasks faster. This file should be loaded
 * prior to any other files because its functions are needed to run the framework.
 *
 * @package Momtaz
 * @subpackage Functions
 */

// Allow child themes to load the parent theme translation.
add_filter( 'load_textdomain_mofile', 'momtaz_load_textdomain', 10, 2 );

/**
 * Filters the 'load_textdomain_mofile' filter hook so that we can change the directory and file name
 * of the mofile for translations.  This allows child themes to have a folder called /languages with translations
 * of their parent theme so that the translations aren't lost on a parent theme upgrade.
 *
 * @since 1.0
 */
function momtaz_load_textdomain( $mofile, $domain ) {

    if ( $domain === THEME_TEXTDOMAIN ) {

        $locale = get_locale();

        $locate_mofile = locate_template( array(
                "content/languages/{$domain}-{$locale}.mo",
                "languages/{$domain}-{$locale}.mo",
                "{$domain}-{$locale}.mo"
            ) );

        if ( ! empty( $locate_mofile ) )
             $mofile = $locate_mofile;

    } // end if

    return $mofile;

} // end momtaz_load_textdomain()

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