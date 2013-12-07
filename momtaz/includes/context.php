<?php
/**
 * Functions for making various theme elements context-aware. This controls things such as the
 * <body> and entry CSS classes as well as contextual hooks. By using a context, developers and
 * users can create page-specific code.
 *
 * @package Momtaz
 * @subpackage Functions
 */

/**
 * Momtaz's main contextual function.  This allows code to be used more than once without running
 * hundreds of conditional checks within the theme.  It returns an array of contexts based on what
 * page a visitor is currently viewing on the site.  This function is useful for making dynamic/contextual
 * classes, action and filter hooks, and handling the templating system.
 *
 * Note that time and date can be tricky because any of the conditionals may be true on time-/date-
 * based archives depending on several factors.  For example, one could load an archive for a specific
 * second during a specific minute within a specific hour on a specific day and so on.
 *
 * @author Justin Tadlock <justin@justintadlock.com>
 * @author Nashwan Doaqan <nashwan.doaqan@ymail.com>
 * @return array
 * @since 1.0
 */
function momtaz_get_context() {

	static $context = null;

	if ( is_null( $context ) ) {

			$context = array();

			/* Front page. */
			if ( is_front_page() ){
				$context[] = 'home';
			}

			/* Blog page. */
			if ( is_home() ) {
				$context[] = 'blog';
			}

			/* Singular views. */
			elseif ( is_singular() ) {

				$context[] = 'singular';

				if ( ( $object = get_queried_object() ) ) {

					$context[] = "{$object->post_type}";
					$object_id = get_queried_object_id();
					$context[] = "{$object->post_type}-{$object_id}";

				} // end if

			}

			/* Archive views. */
			elseif ( is_archive() ) {

				$context[] = 'archive';

				/* Taxonomy archives. */
				if ( is_tax() || is_category() || is_tag() ) {

					$context[] = 'taxonomy';

					if ( ( $object = get_queried_object() ) ) {

						if ( 'post_format' === $object->taxonomy ) {
							$object->slug = str_replace( 'post-format-', '', $object->slug );
						}

						$context[] = "taxonomy-{$object->taxonomy}";
						$context[] = "taxonomy-{$object->taxonomy}-" . sanitize_html_class( $object->slug, $object->term_id );

					} // end if

				}

				/* Post type archives. */
				elseif ( is_post_type_archive() ) {

					$post_type = get_post_type_object( user_trailingslashit( get_query_var( 'post_type' ) ) );

					if ( ! empty( $post_type ) ) {
						$context[] = "archive-{$post_type->name}";
					}

				}

				/* User/author archives. */
				elseif ( is_author() ) {

					$context[] = 'user';
					$user_id = user_trailingslashit( get_query_var( 'author' ) );
					$context[] = 'user-' . sanitize_html_class( get_the_author_meta( 'user_nicename', $user_id ), $user_id );

				}

				/* Time/Date archives. */
				else {

					if ( is_date() ) {

						$context[] = 'date';

						if ( is_year() ){
							$context[] = 'year';
						}

						if ( is_month() ) {
							$context[] = 'month';
						}

						if ( is_day() ) {
							$context[] = 'day';
						}

					} // end if

					if ( is_time() ) {
						$context[] = 'time';
					} // end if

				} // end if

			}

			/* Search results. */
			elseif ( is_search() ) {
				$context[] = 'search';
			}

			/* Error 404 pages. */
			elseif ( is_404() ) {
				$context[] = 'error-404';

			/* Other pages. */
			} else {
				$context[] = 'other';

			} // end if

			$context = array_map( 'esc_attr', $context );

	} // end if

	return (array) apply_filters( 'momtaz_get_context', $context );

}

/**
 * Momtaz's post contextual function.  This allows code to be used more than once without running
 * hundreds of conditional checks within the theme. It returns an array of contexts based on the post
 * data such as post-type, post-status, post-format ...etc , This function is useful for making
 * dynamic/contextual classes, action and filter hooks, and handling the templating system.
 *
 * @return array|boolean
 * @since 1.0
 */
function momtaz_get_post_context( $post = null ) {

	static $context = array();

	// Get the post object.
	$post = get_post( $post );

	// Is the post exists?
	if ( empty( $post ) ) {
		 return false;
	}

	if ( ! isset( $context[ $post->ID ] ) ) {

			$post_context = array();

			// Post ID
			$post_context[] = 'post-' . $post->ID;

			// Post type
			if ( ! empty( $post->post_type ) ){
				$post_context[] = $post->post_type;
				$post_context[] = 'type-' . $post->post_type;
			} // end if

			// Post status
			if ( ! empty( $post->post_status ) ) {
				$post_context[] = "status-{$post->post_status}";
			} // end if

			// Post author
			if ( ! empty( $post->post_author ) ) {

				// Get the current user ID.
				$current_user_id = get_current_user_id();

				$post_context[] = "author-{$post->post_author}";

				if ( ! empty( $post->post_author ) && $post->post_author == $current_user_id ) {
					$post_context[] = 'author-self';
				}

			} // end if

			// Post formats
			if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post->post_type, 'post-formats' ) ) {

				// Get the post format slug.
				$post_format = get_post_format( $post->ID );

				if ( $post_format && ! is_wp_error( $post_format ) ) {
					 $post_context[] = 'format-' . sanitize_html_class( $post_format );

				} else {
					 $post_context[] = 'format-standard';

				} // end-if

			} // end-if

			// Cache the post context array.
			$context[ $post->ID ] = array_map( 'esc_attr', $post_context );

	} // end if

	return (array) apply_filters( 'momtaz_post_context', $context[ $post->ID ], $post );

}

/**
 * Display the classes for the body element.
 *
 * @param string|array $class One or more classes to add to the class list.
 * @since 1.0
*/
function momtaz_body_class( $classes ) {

	/* Date classes. */
	$time = time() + ( get_option( 'gmt_offset' ) * 3600 );
	$classes[] = strtolower( gmdate( '\yY \mm \dd \hH l', $time ) );


	/* Check if the current theme is a parent or child theme. */
	$classes[] = ( is_child_theme() ? 'child-theme' : 'parent-theme' );


	// Get the global browsers vars.
	global $is_lynx, $is_gecko, $is_IE , $is_opera, $is_NS4, $is_safari, $is_chrome;

	/* Browser Detection Loop. */
	foreach ( array( 'gecko' => $is_gecko, 'opera' => $is_opera, 'lynx' => $is_lynx, 'ns4' => $is_NS4, 'safari' => $is_safari, 'chrome' => $is_chrome, 'ie' => $is_IE ) as $key => $value ) {

		if ( $value ) {
			$classes[] = 'browser-' . $key;
			break;
		} // end if

	} // end foreach


	// Register devices vars
	global $is_iphone;
	$is_ipod = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'iPod' );
	$is_ipad = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'iPad' );
	$is_android = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'Android' );
	$is_palmpre = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'webOS' );
	$is_blackberry = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' );

	/* Devices Detection Loop. */
	foreach ( array( 'ipod' => $is_ipod, 'ipad' => $is_ipad, 'android' => $is_android, 'webos' => $is_palmpre, 'blackberry' => $is_blackberry , 'iphone' => $is_iphone ) as $key => $value ) {

		if ( $value ) {
			$classes[] = 'device-' . $key;
			break;
		} // end if

	} // end foreach


	// Register systems vars
	$is_Mac = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'Mac' );
	$is_Windows = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'Win' );
	$is_Linux = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'Linux' );

	/* Systems Detection Loop. */
	foreach ( array( 'windows' => $is_Windows, 'linux' => $is_Linux, 'mac' => $is_Mac ) as $key => $value ) {

		if ( $value ) {
			$classes[] = 'system-' . $key;
			break;
		} // end if

	} // end foreach


	/* Momtaz Current Layout. */
	if ( ( $current_layout = Momtaz_Layouts::get_current_layout() ) ) {
		$classes[] = 'layout-' . trim( $current_layout->key );
	}

	return (array) apply_filters( 'momtaz_body_class', $classes );

}

/**
 * Display the post class attribute.
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param int $post_id An optional post ID.
 * @since 1.1
 */
function momtaz_post_class( $class = '', $post_id = 0 ) {

	// Get post classes array.
	$classes = momtaz_get_post_class( $class, $post_id );

	if ( ! empty( $classes ) ) {

		// Output the class attribute.
		echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';

	} // end if

}

/**
 * Creates a set of classes for each site entry upon display. Each entry is given the class of
 * 'hentry'. Posts are given category, tag, and author classes. Alternate post classes of odd,
 * even, and alt are added.
 *
 * @since 1.1
 * @param string|array $class One or more classes to add to the class list.
 * @param int $post_id An optional post ID.
 */
function momtaz_get_post_class( $class = '', $post_id = 0 ) {

	$classes = array();

	// Get post object
	$post = get_post( $post_id );

	if ( empty( $post ) ) {
		return $classes;
	}

	// hAtom compliance.
	$classes[] = 'hentry';

	// Get post context.
	$context = momtaz_get_post_context( $post_id );

	// Merge the classes array with post context.
	$classes = array_merge( $classes, (array) $context );

	// Post alt class.
	if ( ! momtaz_is_single( $post ) ) {

		static $post_alt = 0;
		$classes[] = 'set-' . ++$post_alt;
		$classes[] = ( $post_alt % 2 ) ? 'odd' : 'even alt';

	} // end if

	// Post publish date.
	if ( ! empty( $post->post_date ) ) {

		// Post Publish Year
		$classes[] = 'pubdate-y-' . get_post_time( 'y', false, $post );

		// Post Publish Month
		$classes[] = 'pubdate-m-' . get_post_time( 'm', false, $post );

		// Post Publish Day
		$classes[] = 'pubdate-d-' . get_post_time( 'd', false, $post );

		// Post Publish Date
		$classes[] = 'pubdate-' . get_post_time( 'y-m-d', false, $post );

	} // end if

	// Post taxonomies
	$obj_taxonomies = get_object_taxonomies( $post );

	if ( is_array( $obj_taxonomies ) && ! empty( $obj_taxonomies ) ){

		foreach ( $obj_taxonomies as $taxonomy ) {

			$terms = get_the_terms( $post->ID, $taxonomy );

			if ( ! empty( $terms ) ) {

				foreach( $terms as $term ) {
					$classes[] = 'term-'. sanitize_html_class( $term->slug, $term->term_id );
				}

			} // end if

		} // end-foreach

	} // end-if

	// Sticky posts.
	if ( is_home() && ! is_paged() && is_sticky( $post->ID ) ) {
		$classes[] = 'sticky';
	}

	// Is this post protected by a password?
	if ( post_password_required( $post ) ) {
		$classes[] = 'password-protected';
	}

	// Has a custom excerpt?
	if ( has_excerpt( $post ) ) {
		$classes[] = 'has-excerpt';
	}

	// Custom classes.
	if ( ! empty( $class ) ) {

		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}

		$classes = array_merge( $classes, $class );

	} // end if

	// Apply the WordPress filters.
	$classes = apply_filters( 'post_class', $classes, $class, $post->ID );

	// Apply the Momtaz FW filters.
	$classes = apply_filters( 'momtaz_get_post_class', $classes, $post );

	// Removes any duplicate and empty classes.
	$classes = array_unique( array_filter( $classes ) );

	return $classes;

}

/**
 * Is the query for an existing single post?
 * Works for any post type, including attachments and pages
 *
 * @param mixed $post Post ID, title, slug, or array of such.
 * @param mixed $post_types Optional. Post Type or array of Post Types
 * @return boolean
 * @since 1.1
 */
function momtaz_is_single( $post = false, $post_types = '' ) {

	$retval = false;

	if ( is_singular( $post_types ) ) {

		if ( ! empty( $post ) ) {

			$post = (array) $post;
			$post_obj = get_queried_object();

			if ( in_array( $post_obj->ID, $post, true ) ){
				$retval = true;
			} elseif ( in_array( $post_obj->post_title, $post, true ) ) {
				$retval = true;
			} elseif ( in_array( $post_obj->post_name, $post, true ) ) {
				$retval = true;
			}

		} else {

			$retval = true;

		} // end if

	} // end if

	return $retval;

}