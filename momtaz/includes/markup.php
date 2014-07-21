<?php

add_filter( 'momtaz_struct_atts-body', 'momtaz_struct_atts_body' );

/**
 * Add attributes for the body element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_struct_atts_body( $atts ) {

	if ( ! isset( $atts['class'] ) ) {
		$atts['class'] = get_body_class();
	}

	return $atts;

}

add_filter( 'momtaz_struct_atts-header', 'momtaz_struct_atts_header' );

/**
 * Add attributes for the header element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_struct_atts_header( $atts ) {

	if ( ! isset( $atts['id'] ) ) {
		$atts['id'] = 'header';
	}

	if ( ! isset( $atts['role'] ) ) {
		$atts['role'] = 'banner';
	}

	return $atts;

}

add_filter( 'momtaz_struct_atts-wrapper', 'momtaz_struct_atts_wrapper' );

/**
 * Add attributes for the wrapper element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_struct_atts_wrapper( $atts ) {

	if ( ! isset( $atts['id'] ) ) {
		$atts['id'] = 'wrapper';
	}

	if ( ! isset( $atts['class'] ) ) {
		$atts['class'] = 'hfeed';
	}

	return $atts;

}

add_filter( 'momtaz_struct_atts-container', 'momtaz_struct_atts_container' );

/**
 * Add attributes for the container element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_struct_atts_container( $atts ) {

	if ( ! isset( $atts['id'] ) ) {
		$atts['id'] = 'container';
	}

	return $atts;

}

add_filter( 'momtaz_struct_atts-content', 'momtaz_struct_atts_content' );

/**
 * Add attributes for the main content element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_struct_atts_content( $atts ) {

	if ( ! isset( $atts['id'] ) ) {
		$atts['id'] = 'content';
	}

	if ( ! isset( $atts['role'] ) ) {
		$atts['role'] = 'main';
	}

	return $atts;

}

add_filter( 'momtaz_struct_atts-entry', 'momtaz_struct_atts_entry' );

/**
 * Add attributes for the entry element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_struct_atts_entry( $atts ) {

	$post_id = get_the_ID();

	if ( empty( $post_id ) ) {
		return $atts;
	}

	if ( ! isset( $atts['id'] ) ) {
		$atts['id']	= "post-{$post_id}";
	}

	if ( ! isset( $atts['class'] ) ) {
		$atts['class'] = momtaz_get_post_class();
	}

	return $atts;

}

add_filter( 'momtaz_struct_atts-comment', 'momtaz_struct_atts_comment' );

/**
 * Add attributes for the comment element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_struct_atts_comment( $atts ) {

	$comment_id = get_comment_ID();

	if ( empty( $comment_id ) ) {
		return $atts;
	}

	if ( ! isset( $atts['id'] ) ) {
		$atts['id']	= "comment-{$comment_id}";
	}

	if ( ! isset( $atts['class'] ) ) {
		$atts['class'] = get_comment_class();
	}

	return $atts;

}

/**
 * Output the structural element HTML markup.
 *
 * @since 1.3
 */
function momtaz_struct_markup( $context, $markup = '', array $atts = array() ) {
	echo momtaz_get_struct_markup( $context, $markup, $atts );
}

/**
 * Get the structural element HTML markup.
 *
 * @since 1.3
 */
function momtaz_get_struct_markup( $context, $markup = '', array $atts = array() ) {

	$output = $markup = apply_filters( "momtaz_struct_markup-{$context}", $markup );

	if ( ! empty( $markup ) && strpos( $markup, '%atts%' ) !== FALSE ) {
		$atts = (array) apply_filters( "momtaz_struct_atts-{$context}", $atts );
		$output = str_replace( '%atts%', momtaz_get_html_atts( $atts ), $markup );
	}

	$output = apply_filters( "momtaz_struct_output-{$context}", $output, $markup, $atts );
	return $output;

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
