<?php
/**
 * Momtaz Formatting API.
 *
 * Handles many functions for formatting output.
 *
 * @package Momtaz
 * @subpackage Functions
 */

/**
 * Check the given URL for a valid structure.
 *
 * @param string URL to be validated
 * @return bool Validation result
 * @since 1.0
 */
function momtaz_is_vaild_url( $url ) {
	return (bool) filter_var( utf8_uri_encode( $url ), FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED + FILTER_FLAG_HOST_REQUIRED );
}

/**
 * Check if the given URL is vaild and self-hosted.
 *
 * Check if the structure of a given URL is valid and the URL is a self hosted one. The 'momtaz_is_self_hosted_url'
 * filter hook can be used to hook into this function if needed.
 *
 * @param string The URL to be validated.
 * @uses momtaz_is_vaild_url() Check the given URL for a valid structure.
 * @uses get_site_url() Get the site URL.
 * @return bool
 * @since 1.2
 */
function momtaz_is_self_hosted_url( $url ) {

	$retval = false;

	if ( momtaz_is_vaild_url( $url ) ) {

		$url_host = parse_url( $url, PHP_URL_HOST );
		$site_host = parse_url( get_site_url(), PHP_URL_HOST );

		$retval = ( empty( $url_host ) || $url_host === $site_host );

	}

	$retval = apply_filters( 'momtaz_is_self_hosted_url', $retval, $url );
	return $retval;

}

/**
 * Limit the view text characters .
 *
 * @param mixed Text to be evaluated
 * @param int Maximum number of characters to be viewed
 * @return string
 * @since 1.0
 */
function momtaz_limit_characters( $text, $limit ) {

	if ( mb_strlen( $text ) > $limit ) {
		$text = mb_substr( $text, 0, $limit );
	}

	return $text;

}


/*** HTML Helper Functions ****************************************************/

/**
 * Output HTML attributes list.
 *
 * @param array $atts An associative array of attributes and their values.
 * @param array $args An array of arguments to be applied on the function output.
 * @uses  momtaz_get_html_atts() Convert an associative array to HTML attributes list.
 * @since 1.0
 */
function momtaz_html_atts( array $atts, array $args = null ) {
	echo momtaz_get_html_atts( $atts, $args );
}

/**
 * Convert an associative array to HTML attributes list.
 *
 * Convert an associative array of attributes and their values 'attribute => value' To
 * an inline list of HTML attributes.
 *
 * @param array $atts An associative array of attributes and their values.
 * @param array $args An array of arguments to be applied on the function output.
 * @return string
 * @since 1.0
 */
function momtaz_get_html_atts( array $atts, array $args = null ) {

	$output = '';

	if ( empty( $atts ) ) {
		return $output;
	 }

	$args = array_merge( array(
		'after' => '',
		'before' => ' ',
		'escape' => true,
	), (array) $args );

	foreach ( $atts as $key => $value ) {

		$key = strtolower( $key );

		 if ( is_bool( $value ) ) {

			if ( $value === true ) {
				$value = $key;
			} else {
				continue;
			}

		 } elseif ( is_array( $value ) ) {

			$value = implode( ' ', array_filter( $value ) );

		 }

		 if ( $args['escape'] ) {

			switch( $key ) {

				case 'src':
				case 'href':
					$value = esc_url( $value );
					break;

				default:
					$value = esc_attr( $value );
					break;

			}

		 }

		$output .= $key . '="' . $value . '" ';

	 }

	 if ( ! empty( $output ) ) {
		$output = $args['before'] . trim( $output ) . $args['after'];
	 }

	return $output;

}
