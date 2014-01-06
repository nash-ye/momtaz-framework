<?php
/**
 * Functions for dealing with theme settings on both the front end of the site and the admin.  This allows us
 * to set some default settings and make it easy for theme developers to quickly grab theme settings from
 * the database.
 *
 * @package Momtaz
 * @subpackage Functions
 */

/**
 * Loads all Momtaz theme settings once . Momtaz theme settings are added with 'autoload'
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
				array()
			);

		// Append the default values of the unsaved settings.
		$momtaz->settings += momtaz_default_theme_settings();
	}

	return (array) $momtaz->settings;

}

/**
 * Update all theme settings.
 *
 * @since 1.0
 * @param mixed[] $theme_settings An array of theme settings.
 * @see momtaz_theme_settings_option() Get the name of theme settings option.
 * @return boolean
 */
function momtaz_update_settings( array $theme_settings ){

	if ( ! update_option( momtaz_theme_settings_option(), $theme_settings ) ) {
		return false;
	}

	momtaz()->settings = $theme_settings;
	return true;

}

/**
 * Delete all theme settings.
 *
 * @since 1.0
 * @return boolean
 */
function momtaz_delete_settings(){

	if ( ! delete_option( momtaz_theme_settings_option() ) ) {
		return false;
	}

	unset( momtaz()->settings );
	return true;

}

/**
 * Get the value of a theme setting depending on its ID.
 *
 * Get the value of a specific field 'setting' from the array of theme settings through
 * passing the ID of this field to the function.
 *
 * @since 1.0
 * @param string $option_ID the ID of the setting.
 * @see momtaz_get_settings() Loads all Momtaz theme settings once.
 * @return mixed
 */
function momtaz_get_setting( $option_ID ) {

	if ( ! empty( $option_ID ) ) {

		$theme_settings = momtaz_get_settings();

		if ( isset( $theme_settings[ $option_ID ] ) ) {
			return $theme_settings[ $option_ID ];
		}

	}

}

/**
 * Add a new setting to the array of theme settings.
 *
 * Add a new theme setting if not previously added by passing the ID of the setting
 * to the function as its first parameter and its value as the second parameter then,
 * update the array of all theme settings with the new one.
 *
 * @since 1.0
 * @param string $option_ID The ID of the new setting.
 * @param mixed $option_value The value of the new setting.
 * @see momtaz_get_settings() Loads all Momtaz theme settings once.
 * @see momtaz_update_settings() Update all theme settings.
 * @return boolean
 */
function momtaz_add_setting( $option_ID, $option_value ) {

	if ( empty( $option_ID ) ) {
		return false;
	}

	$theme_settings = momtaz_get_settings();

	if ( isset( $theme_settings[ $option_ID ] ) ) {
		return true;
	}

	$theme_settings[ $option_ID ] = $option_value;

	return momtaz_update_settings( $theme_settings );

}

/**
 * Update the value of a specific setting that was already added.
 *
 * Update the value of a previously added setting by passing the ID of the setting
 * to the function as its first parameter and its new value as the second parameter then,
 * update the array of all theme settings with the new one.
 *
 * @since 1.0
 * @param string $option_ID The ID of the already added setting.
 * @param mixed $option_value The new value of the already added setting.
 * @see momtaz_get_settings() Loads all Momtaz theme settings once.
 * @see momtaz_update_settings() Update all theme settings.
 * @return boolean
 */
function momtaz_update_setting( $option_ID, $option_value ) {

	if ( empty( $option_ID ) ) {
		return false;
	}

	$theme_settings = momtaz_get_settings();
	$theme_settings[ $option_ID ] = $option_value;

	return momtaz_update_settings( $theme_settings );

}

/**
 * Delete a setting that was already added.
 *
 * Delete a setting and its value from the array of theme settings by passing
 * its ID to the function and then, update all theme settings.
 *
 * @since 1.0
 * @param string $option_ID The ID of the already added setting.
 * @see momtaz_get_settings() Loads all Momtaz theme settings once.
 * @see momtaz_update_settings() Update all theme settings.
 * @return boolean
 */
function momtaz_delete_setting( $option_ID ) {

	if ( empty( $option_ID ) ) {
		return false;
	}

	$theme_settings = momtaz_get_settings();

	if ( isset( $theme_settings[ $option_ID ] ) ) {
		unset( $theme_settings[ $option_ID ] );
		return momtaz_update_settings( $theme_settings );
	}

	return true;

}

/**
 * Get the theme settings option name.
 *
 * Get the name of the option in which all theme settings are stored. By default the name is
 * 'theme_settings' preceded by the theme prefix_. It can be changed by using
 * 'momtaz_theme_settings_option' filter hook.
 *
 * @since 1.0
 * @return string
 */
function momtaz_theme_settings_option() {
	return apply_filters( 'momtaz_theme_settings_option', momtaz_format_hook( 'theme_settings' ) );
}

/**
 * Get an array of the default theme settings.
 *
 * Get the array of the default settings to use with the theme if the custom settings
 * values haven't been saved to the database yet. The array is empty by default
 * and can be hooked into by applying 'momtaz_default_theme_settings' filter.
 *
 * @since 1.0
 * @return array
 */
function momtaz_default_theme_settings() {
	return (array) apply_filters( 'momtaz_default_theme_settings', array() );
}
