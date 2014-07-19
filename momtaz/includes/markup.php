<?php

add_filter( 'momtaz_struct_atts-body', 'momtaz_struct_atts_body' );

/**
 * Add attributes for wrapper element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_struct_atts_body( $atts ) {
	$atts['class']	= get_body_class();
	return $atts;
}

add_filter( 'momtaz_struct_atts-wrapper', 'momtaz_struct_atts_wrapper' );

/**
 * Add attributes for wrapper element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_struct_atts_wrapper( $atts ) {
	$atts['id']		= 'wrapper';
	$atts['class']	= 'hfeed';
	return $atts;
}

add_filter( 'momtaz_struct_atts-container', 'momtaz_struct_atts_container' );

/**
 * Add attributes for container element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_struct_atts_container( $atts ) {
	$atts['id']		= 'container';
	return $atts;
}

add_filter( 'momtaz_struct_atts-content', 'momtaz_struct_atts_content' );

/**
 * Add attributes for container element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_struct_atts_content( $atts ) {
	$atts['id']		= 'content';
	$atts['role']	= 'main';
	return $atts;
}

/**
 * Output the structural element HTML markup.
 *
 * @since 1.3
 */
function momtaz_struct_markup( $context, $markup = '', array $atts = NULL ) {
	echo momtaz_get_struct_markup( $context, $markup, $atts );
}

/**
 * Get the structural element HTML markup.
 *
 * @since 1.3
 */
function momtaz_get_struct_markup( $context, $markup = '', array $atts = NULL ) {

	$markup = apply_filters( "momtaz_struct_markup-{$context}", $markup );

	if ( ! empty( $markup ) && strpos( $markup, '%atts%' ) !== FALSE ) {

		$atts = momtaz_get_struct_atts( $context, $atts );
		$markup = str_replace( '%atts%', $atts, $markup );

	}

	return $markup;

}

/**
 * Output the structural element attributes.
 *
 * @since 1.3
 */
function momtaz_struct_atts( $context, array $atts = NULL ) {
	echo momtaz_get_struct_atts( $context, $atts );
}

/**
 * Get the structural element attributes array
 * and convert them to HTML attributes list.
 *
 * @since 1.3
 */
function momtaz_get_struct_atts( $context, array $atts = NULL ) {
	$atts = apply_filters( "momtaz_struct_atts-{$context}", (array) $atts );
	$atts = momtaz_get_html_atts( array_filter( array_unique( $atts ) ) );
	return $atts;
}

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

		$key = esc_html( $key );

		if ( is_bool( $value ) ) {

		   if ( $value === true ) {
			   $value = $key;
		   } else {
			   continue;
		   }

		} elseif ( is_array( $value ) ) {

		   $value = array_filter( $value );
		   $value = implode( ' ', $value );

		}

		 if ( $args['escape'] ) {

			switch( strtolower( $key ) ) {

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

	 if ( empty( $output ) ) {
		 return $output;
	 }

	return $args['before'] . trim( $output ) . $args['after'];

}
