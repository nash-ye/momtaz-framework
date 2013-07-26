<?php
/**
 * Functions file for loading styles.
 *
 * @package Momtaz
 * @subpackage Functions
 */

/**
 * Display the main stylesheet link tag.
 *
 * @since 1.1
 */
function momtaz_main_stylesheet() {
    echo momtaz_get_style_loader_tag( momtaz_get_main_stylesheet_uri() );
} // end momtaz_main_stylesheet()

/**
 * Get the main stylesheet URI.
 *
 * @since 1.1
 */
function momtaz_get_main_stylesheet_uri() {

    $stylesheet_uri = get_stylesheet_uri();
    $stylesheet_dir = get_stylesheet_directory();

    return apply_filters( 'momtaz_get_main_stylesheet_uri',
                momtaz_get_dev_stylesheet_uri( $stylesheet_uri, $stylesheet_dir ),
                $stylesheet_uri
            );

} // end momtaz_get_main_stylesheet_uri()

/**
 * Display the localized stylesheet link tag.
 *
 * @since 1.1
 */
function momtaz_locale_stylesheet() {
    echo momtaz_get_style_loader_tag( momtaz_get_locale_stylesheet_uri() );
} // end momtaz_locale_stylesheet()

/**
 * Get the localized stylesheet URI.
 *
 * @since 1.1
 */
function momtaz_get_locale_stylesheet_uri() {

    $stylesheet_dir = get_stylesheet_directory();
    $stylesheet_uri = get_locale_stylesheet_uri();

    return apply_filters( 'momtaz_get_locale_stylesheet_uri',
                momtaz_get_dev_stylesheet_uri( $stylesheet_uri, $stylesheet_dir ),
                $stylesheet_uri
            );

} // end momtaz_get_locale_stylesheet_uri()

/**
 * Get the development stylesheet URI when Style Development Mode is on.
 *
 * @return string
 * @since 1.0
 */
function momtaz_get_dev_stylesheet_uri( $stylesheet_uri, $stylesheet_dir ) {

    if ( ! empty( $stylesheet_uri ) && momtaz_is_style_dev_mode() ) {

        $stylesheet = pathinfo( $stylesheet_uri );

        $stylesheet['basename'] = ltrim( $stylesheet['basename'], '/' );

        foreach ( momtaz_get_dev_stylesheet_suffixs() as $dev_suffix ) {

            $stylesheet['basename'] = str_replace( '.css', $dev_suffix, $stylesheet['basename'] );

            if ( file_exists( trailingslashit( $stylesheet_dir ) . $stylesheet['basename'] ) )
                 $stylesheet_uri = trailingslashit( $stylesheet['dirname'] ) . $stylesheet['basename'];

        } // end foreach

    } // end if

    return $stylesheet_uri;

} // end momtaz_get_dev_stylesheet_uri()

/**
 * Change the the type attribute when using LESS styles.
 *
 * @return string
 * @since 1.0
 */
 function momtaz_get_style_loader_tag( $stylesheet_uri, $atts = '' ){

    if ( empty( $stylesheet_uri ) )
        return;

    $atts = wp_parse_args( $atts, array(
        'href' => $stylesheet_uri,
        'type' => 'text/css',
        'media' => 'all',
    ) );

    if ( empty( $atts['rel'] ) ) {

        switch( pathinfo( $atts['href'], PATHINFO_EXTENSION ) ) {

            case 'less':
                $atts['rel'] = 'stylesheet/less';
                break;

            default:
                $atts['rel'] = 'stylesheet';
                break;

        } // end Switch

    } // end if

    $output = '<link' . momtaz_get_html_atts( $atts ) . ' />' . "\n";

    return apply_filters( 'momtaz_get_style_loader_tag', $output, $atts );

 } // end momtaz_get_style_loader_tag()

/**
 * Get the development stylesheet filename suffixs.
 *
 * @return array
 * @since 1.0
 */
function momtaz_get_dev_stylesheet_suffixs() {
    return apply_filters( 'momtaz_get_dev_stylesheet_suffixs', array( '.less', '.dev.css' ) );
} // end momtaz_get_dev_stylesheet_suffixs()

/**
 * Check if the Style Development Mode is on.
 *
 * @return boolean
 * @since 1.0
 */
function momtaz_is_style_dev_mode() {

    if ( defined( 'MOMTAZ_STYLE_DEV' ) && MOMTAZ_STYLE_DEV )
        return true;

    if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG )
        return true;

    return false;

} // end momtaz_is_style_dev_mode()