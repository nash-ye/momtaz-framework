<?php

add_filter( 'momtaz_atts-body', 'momtaz_atts_body' );

/**
 * Add attributes for the body element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_body( $atts ) {

	if ( ! isset( $atts['class'] ) ) {
		$atts['class']  = get_body_class();
	} else {
		$atts['class']  = get_body_class( $atts['class'] );
	}

	$atts['itemscope']  = 'itemscope';
	$atts['itemtype']   = 'http://schema.org/WebPage';

	return $atts;

}

add_filter( 'momtaz_atts-header', 'momtaz_atts_header' );

/**
 * Add attributes for the header element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_header( $atts ) {

	$atts['role']       = 'banner';
	$atts['itemscope']  = 'itemscope';
	$atts['itemtype']   = 'http://schema.org/WPHeader';

	return $atts;

}

add_filter( 'momtaz_atts-site-title', 'momtaz_atts_site_title' );

/**
 * Add attributes for the site-title element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_site_title( $atts ) {

	$atts['itemprop'] = 'headline';

	return $atts;

}

add_filter( 'momtaz_atts-site-description', 'momtaz_atts_site_description' );

/**
 * Add attributes for the site-description element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_site_description( $atts ) {

	$atts['itemprop'] = 'description';

	return $atts;

}

add_filter( 'momtaz_atts-nav-primary', 'momtaz_atts_nav' );
add_filter( 'momtaz_atts-nav-comments', 'momtaz_atts_nav' );

/**
 * Add attributes for the nav-menus elements.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_nav( $atts ) {

	$atts['role']       = 'navigation';
	$atts['itemscope']  = 'itemscope';
	$atts['itemtype']   = 'http://schema.org/SiteNavigationElement';

	return $atts;

}

add_filter( 'momtaz_atts-content', 'momtaz_atts_content' );

/**
 * Add attributes for the content element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_content( $atts ) {

	$atts['role'] = 'main';
	$atts['itemprop'] = 'mainContentOfPage';

	if ( is_search() ) {
		$atts['itemscope']  = 'itemscope';
		$atts['itemtype']   = 'http://schema.org/SearchResultsPage';

	} elseif ( is_singular( 'post' ) || is_archive() || is_home() ) {
		$atts['itemscope']  = 'itemscope';
		$atts['itemtype']   = 'http://schema.org/Blog';
	}

	return $atts;

}

add_filter( 'momtaz_atts-entry', 'momtaz_atts_entry' );

/**
 * Add attributes for the entry element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_entry( $atts ) {

	$post = get_post();

	if ( empty( $post ) ) {
		return $atts;
	}

	$post_id = get_the_ID();

	$atts['id']	= "post-{$post_id}";

	if ( ! isset( $atts['class'] ) ) {
		$atts['class'] = momtaz_get_post_class();
	} else {
		$atts['class'] = momtaz_get_post_class( $atts['class'] );
	}

	// HTML5 Microdata
	$atts['itemscope'] = 'itemscope';

	// Blog posts microdata
	if ( 'post' === $post->post_type ) {

		$atts['itemtype'] = 'http://schema.org/BlogPosting';

		if ( momtaz_is_the_single( $post_id ) ) {
			$atts['itemprop'] = 'blogPost';
		}

	} elseif ( 'attachment' === $post->post_type ) {

		if ( wp_attachment_is_image( $post_id ) ) {
			$atts['itemtype'] = 'http://schema.org/ImageObject';
		}

	} else {

		$atts['itemtype'] = 'http://schema.org/CreativeWork';

	}

	return $atts;

}

add_filter( 'momtaz_atts-entry-title', 'momtaz_atts_entry_title' );

/**
 * Add attributes for the entry title element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_entry_title( $atts ) {

	$atts['itemprop'] = 'headline';

	return $atts;

}

add_filter( 'momtaz_atts-entry-content', 'momtaz_atts_entry_content' );

/**
 * Add attributes for the entry content element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_entry_content( $atts ) {

	$atts['itemprop'] = 'text';

	return $atts;

}

add_filter( 'momtaz_atts-entry-summary', 'momtaz_atts_entry_summary' );

/**
 * Add attributes for the entry summary element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_entry_summary( $atts ) {

	$atts['itemprop'] = 'description';

	return $atts;

}

add_filter( 'momtaz_atts-comment', 'momtaz_atts_comment' );

/**
 * Add attributes for the comment element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_comment( $atts ) {

	$comment_id = get_comment_ID();

	if ( empty( $comment_id ) ) {
		return $atts;
	}

	$atts['id']	       = "comment-{$comment_id}";
	$atts['class']     = get_comment_class();
	$atts['itemprop']  = 'comment';
	$atts['itemscope'] = 'itemscope';
	$atts['itemtype']  = 'http://schema.org/UserComments';

	return $atts;

}

add_filter( 'momtaz_atts-comment-author', 'momtaz_atts_comment_author' );

/**
 * Add attributes for the comment author element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_comment_author( $atts ) {

	$atts['itemprop']   = 'creator';
	$atts['itemscope']  = 'itemscope';
	$atts['itemtype']   = 'http://schema.org/Person';

	return $atts;

}

add_filter( 'momtaz_atts-comment-time', 'momtaz_atts_comment_time' );

/**
 * Add attributes for the comment time element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_comment_time( $atts ) {

	$atts['datetime']   = get_comment_date( 'c' );
	$atts['itemprop']   = 'commentTime';

	return $atts;

}

add_filter( 'momtaz_atts-comment-permalink', 'momtaz_atts_comment_permalink' );

/**
 * Add attributes for the comment permalink element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_comment_permalink( $atts ) {

	$atts['href']       = get_comment_link();
	$atts['itemprop']   = 'url';

	return $atts;

}

add_filter( 'momtaz_atts-comment-content', 'momtaz_atts_comment_content' );

/**
 * Add attributes for the comment content element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_comment_content( $atts ) {

	$atts['itemprop'] = 'commentText';

	return $atts;

}

add_filter( 'momtaz_atts-sidebar', 'momtaz_atts_sidebar' );

/**
 * Add attributes for the sidebar element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_sidebar( $atts ) {

	$atts['role']      = 'complementary';
	$atts['itemscope'] = 'itemscope';
	$atts['itemtype']  = 'http://schema.org/WPSideBar';

	return $atts;

}

add_filter( 'momtaz_atts-footer', 'momtaz_atts_footer' );

/**
 * Add attributes for the footer element.
 *
 * @return array
 * @since 1.3
 */
function momtaz_atts_footer( $atts ) {

	$atts['role']      = 'contentinfo';
	$atts['itemscope'] = 'itemscope';
	$atts['itemtype']  = 'http://schema.org/WPFooter';

	return $atts;

}

/**
 * Output the structural element HTML markup.
 *
 * @since 1.3
 */
function momtaz_markup( $context, $markup = '', array $atts = array() ) {
	echo momtaz_get_markup( $context, $markup, $atts );
}

/**
 * Get the structural element HTML markup.
 *
 * @return string
 * @since 1.3
 */
function momtaz_get_markup( $context, $markup = '', array $atts = array() ) {

	$markup = apply_filters( "momtaz_markup-{$context}", $markup );

	if ( ! empty( $markup ) && strpos( $markup, '%atts%' ) !== FALSE ) {
		$atts = momtaz_get_atts( $context, $atts );
		$markup = str_replace( '%atts%', $atts, $markup );
	}

	return $markup;

}

/**
 * Output the structural element HTML markup.
 *
 * @since 1.3
 */
function momtaz_atts( $context, array $atts = array() ) {
	echo momtaz_get_atts( $context, $atts );
}

/**
 * Get the structural element HTML markup.
 *
 * @return string
 * @since 1.3
 */
function momtaz_get_atts( $context, array $atts = array() ) {
	$atts = (array) apply_filters( "momtaz_atts-{$context}", $atts );
	return momtaz_get_html_atts( $atts );
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
		'after'  => '',
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
