<?php
/**
 * Functions for dealing with theme settings on both the front end of the site and the admin.  This allows us
 * to set some default settings and make it easy for theme developers to quickly grab theme settings from
 * the database.  This file is only loaded if the theme adds support for the 'momtaz-core-theme-settings'
 * feature.
 *
 * @package Momtaz
 * @subpackage Functions
 */

/**
 * Loads the Momtaz theme settings once . Momtaz theme settings are added with 'autoload'
 * set to 'yes', so the settings are only loaded once on each page load.
 *
 * @since 1.0
 * @return array
 */
function momtaz_get_settings(){

    $momtaz = momtaz();

    if ( ! isset( $momtaz->settings ) ) {

        // Get the option and cache it.
        $momtaz->settings = get_option(
                momtaz_theme_settings_option(),
                momtaz_default_theme_settings()
            );

    } // end if

    return (array) $momtaz->settings;

} // end momtaz_get_settings()

/**
 * Update the all theme settings.
 *
 * @since 1.0
 * @return boolean
 */
function momtaz_update_settings( array $theme_settings ){

    if ( ! update_option( momtaz_theme_settings_option(), $theme_settings ) )
        return false;

    momtaz()->settings = $theme_settings;

    // :)
    return true;

} // end momtaz_update_settings()

/**
 * Delete all theme settings.
 *
 * @since 1.0
 * @return boolean
 */
function momtaz_delete_settings(){

    if ( ! delete_option( momtaz_theme_settings_option() ) )
        return false;

    unset( momtaz()->settings );

    // :)
    return true;

} // end momtaz_delete_settings()

/**
 * Loads the Momtaz theme settings once and allows the input of the specific field the user would
 * like to show.  Momtaz theme settings are added with 'autoload' set to 'yes', so the settings are
 * only loaded once on each page load.
 *
 * @since 1.0
 * @return mixed
 */
function momtaz_get_setting( $option_ID ) {

    if ( ! empty( $option_ID ) ) {

        $theme_settings = momtaz_get_settings();

        if ( isset( $theme_settings[ $option_ID ] ) )
            return $theme_settings[ $option_ID ];

    } // end if

} // end momtaz_get_setting()

/**
 * Add a new setting.
 *
 * @since 1.0
 * @return boolean
 */
function momtaz_add_setting( $option_ID, $option_value ) {

    if ( empty( $option_ID ) )
        return false;

    $theme_settings = momtaz_get_settings();

    if ( isset( $theme_settings[ $option_ID ] ) )
        return true;

    $theme_settings[ $option_ID ] = $option_value;

    return momtaz_update_settings( $theme_settings );

} // end momtaz_add_setting()

/**
 * Update the value of an setting that was already added.
 *
 * @since 1.0
 * @return boolean
 */
function momtaz_update_setting( $option_ID, $option_value ) {

    if ( empty( $option_ID ) )
        return false;

    $theme_settings = momtaz_get_settings();

    $theme_settings[ $option_ID ] = $option_value;

    return momtaz_update_settings( $theme_settings );

} // end momtaz_update_setting()

/**
 * Delete the value of an setting that was already added.
 *
 * @since 1.0
 * @return boolean
 */
function momtaz_delete_setting( $option_ID ) {

    if ( empty( $option_ID ) )
        return false;

    $theme_settings = momtaz_get_settings();

    if ( ! isset( $theme_settings[ $option_ID ] ) )
        return true;

    unset( $theme_settings[ $option_ID ] );

    return momtaz_update_settings( $theme_settings );

} // end momtaz_delete_setting()

/**
 * Get the theme settings option name.
 *
 * @since 1.0
 * @return string
 */
function momtaz_theme_settings_option() {
    return apply_filters( 'momtaz_theme_settings_option', momtaz_format_hook( 'theme_settings' ) );
} // end momtaz_theme_settings_option()

/**
 * Get the default array of theme settings for use with the theme.
 *
 * @since 1.0
 * @return array
 */
function momtaz_default_theme_settings() {
    return (array) apply_filters( 'momtaz_default_theme_settings', array() );
} // end momtaz_default_theme_settings()