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
 */
function momtaz_is_vaild_url( $url ) {
    return (bool) filter_var( utf8_uri_encode( $url ), FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED + FILTER_FLAG_HOST_REQUIRED );
} // end momtaz_is_vaild_url()

/**
 * Limit the view text characters .
 *
 * @param mixed Text to be evaluated
 * @param integer Maximum number of characters to be viewed
 */
function momtaz_limit_characters( $text, $limit ) {

    if ( mb_strlen( $text ) > $limit )
        $text = mb_substr( $text, 0, $limit );

    return $text;

} // end momtaz_limit_characters()


/*** HTML Helper Functions ****************************************************/

/**
 * A helper function to output HTML attributes list.
 *
 * @since 1.0
 */
function momtaz_html_atts( array $atts, array $args = null ) {
    echo momtaz_get_html_atts( $atts, $args );
} // end momtaz_html_atts()

/**
 * A helper function to convert an associative array to HTML attributes list.
 *
 * @since 1.0
 * @return string
 */
function momtaz_get_html_atts( array $atts, array $args = null ) {

   $output = '';

   if ( empty( $atts ) )
        return $output;

   $args = wp_parse_args( $args, array(
       'after' => '',
       'before' => ' ',
       'escape' => true,
   ) );

   foreach ( $atts as $key => $value ) {

       $key = strtolower( $key );

        if ( is_bool( $value ) ) {

            if ( $value === true )
                 $value = $key;
            else
                 continue;

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

        // @note: Trailing space is important.
        $output .= $key . '="' . $value . '" ';

   } // end foreach

    if ( ! empty( $output ) )
        $output = $args['before'] . trim( $output ) . $args['after'];

   return $output;

} // end momtaz_get_html_atts()