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
 * URL Validation.
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
 * @param string URL to be validated
 * @return bool
 * @since 1.2
 */
function momtaz_is_self_hosted_url( $url ) {

	$retval = false;

	if ( momtaz_is_vaild_url( $url ) ) {

		$url_host = parse_url( $url, PHP_URL_HOST );
		$site_host = parse_url( get_site_url(), PHP_URL_HOST );

		$retval = ( empty( $url_host ) || $url_host === $site_host );

	} // end if

	return apply_filters( 'momtaz_is_self_hosted_url', $retval, $url );

}

/**
 * Limit the view text characters .
 *
 * @param mixed Text to be evaluated
 * @param integer Maximum number of characters to be viewed
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
 * A helper function to output HTML attributes list.
 *
 * @since 1.0
 */
function momtaz_html_atts( array $atts, array $args = null ) {
	echo momtaz_get_html_atts( $atts, $args );
}

/**
 * A helper function to convert an associative array to HTML attributes list.
 *
 * @return string
 * @since 1.0
 */
function momtaz_get_html_atts( array $atts, array $args = null ) {

   $output = '';

   if ( empty( $atts ) ) {
		return $output;
	}

   $args = wp_parse_args( $args, array(
	   'after' => '',
	   'before' => ' ',
	   'escape' => true,
   ) );

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

		} // end if

		if ( $args['escape'] ) {

			switch( $key ) {

				case 'src':
				case 'href':
					$value = esc_url( $value );
					break;

				default:
					$value = esc_attr( $value );
					break;

			} // end switch

		} // end if

		$output .= $key . '="' . $value . '" ';

   } // end foreach

	if ( ! empty( $output ) ) {
		$output = $args['before'] . trim( $output ) . $args['after'];
	}

   return $output;

}