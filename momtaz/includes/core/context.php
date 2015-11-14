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
 * @author Nashwan Doaqan <nashwan.doaqan@gmail.com>
 * @return array
 * @since 1.0
 */
function momtaz_get_context() {

	static $context = null;

	if ( is_null( $context ) ) {

			$context = array();

			/* Home page. */
			if ( is_home() ) {
				$context[] = 'home';
			}

			/* Front page. */
			if ( is_front_page() ){
				$context[] = 'front';
			}

			/* Singular views. */
			elseif ( is_singular() ) {

				$context[] = 'singular';

				if ( ( $object = get_queried_object() ) ) {

					$context[] = "{$object->post_type}";
					$object_id = get_queried_object_id();
					$context[] = "{$object->post_type}-{$object_id}";

				}

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

					}

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

					}

					if ( is_time() ) {
						$context[] = 'time';
					}

				}

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

			}

			$context = array_map( 'esc_attr', $context );

	}

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
			}

			// Post status
			if ( ! empty( $post->post_status ) ) {
				$post_context[] = "status-{$post->post_status}";
			}

			// Post author
			if ( ! empty( $post->post_author ) ) {

				// Get the current user ID.
				$current_user_id = get_current_user_id();

				$post_context[] = "author-{$post->post_author}";

				if ( ! empty( $post->post_author ) && $post->post_author == $current_user_id ) {
					$post_context[] = 'author-self';
				}

			}

			// Post formats
			if ( post_type_supports( $post->post_type, 'post-formats' ) ) {

				// Get the post format slug.
				$post_format = get_post_format( $post->ID );

				if ( $post_format && ! is_wp_error( $post_format ) ) {
					 $post_context[] = 'format-' . sanitize_html_class( $post_format );

				} else {
					 $post_context[] = 'format-standard';

				}

			}

			// Cache the post context array.
			$context[ $post->ID ] = array_map( 'esc_attr', $post_context );

	}

	return (array) apply_filters( 'momtaz_post_context', $context[ $post->ID ], $post );

}