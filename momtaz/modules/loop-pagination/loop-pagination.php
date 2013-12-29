<?php
/**
 * Momtaz Loop Pagination - A WordPress script for creating paginated links on archive-type pages.
 *
 * The Loop Pagination script was designed to give theme authors a quick way to paginate archive-type
 * (archive, search, and blog) pages without having to worry about which of the many plugins a user might
 * possibly be using.  Instead, they can simply build pagination right into their themes.
 *
 * This program is based on Hybird - Loop Pagination 0.2.1
 * http://themehybrid.com/docs/tutorials/loop-pagination
 *
 * @version   0.2
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @author    Nashwan Doaqan <nashwan.doaqan@ymail.com>
 * @copyright Copyright (c) 2013 - 2014, Nashwan Doaqan
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Momtaz loop pagination function for paginating loops with multiple posts.  This should be used on archive, blog, and
 * search pages.  It is not for singular views.
 *
 * @since 0.1
 * @access public
 * @uses paginate_links() Creates a string of paginated links based on the arguments given.
 * @param array $args Arguments to customize how the page links are output.
 */
function momtaz_loop_pagination( $args = '' ) {

    global $wp_query;

    // Merge the arguments input with the defaults.
    $args = wp_parse_args( $args, array(
        'base'         => '',
        'format'       => '',
        'total'        => intval( $wp_query->max_num_pages ),
        'current'      => max( 1, get_query_var( 'paged' ) ),
        'prev_next'    => true,
        'show_all'     => false,
        'end_size'     => 1,
        'mid_size'     => 1,
        'add_fragment' => '',
        'type'         => 'plain',

        // Begin momtaz_loop_pagination() arguments.
        'before'       => '<nav class="pagination loop-pagination">',
        'after'        => '</nav>',
        'echo'         => true,
    ) );

    // Allow developers to overwrite the arguments with a filter.
    $args = apply_filters( 'momtaz_loop_pagination_args', $args );

    // If there's not more than one page, return nothing.
	if ( $args['total'] < 2 ) {
        return;
	}

    // Set the default paginated links base.
	if ( empty( $args['base'] ) ) {
        $args['base'] = str_replace( 999999999, '%#%', get_pagenum_link( 999999999, true ) );
	}

    // Don't allow the user to set this to an array.
	if ( 'array' === $args['type'] ) {
        $args['type'] = 'plain';
	}

    // Get the paginated links.
    $page_links = apply_filters( 'momtaz_loop_pagination', paginate_links( $args ), $args );

    // Wrap the paginated links with the $before and $after elements.
	if ( ! empty( $page_links ) ) {
        $page_links = $args['before'] . $page_links . $args['after'];
	}

    // Return the paginated links for use in themes.
	if ( ! $args['echo'] ) {
        return $page_links;
	}

    echo $page_links;

} // end momtaz_loop_pagination()

if ( ! function_exists( 'loop_pagination' ) ) {

/**
 * Momtaz loop pagination function for paginating loops with multiple posts.  This should be used on archive, blog, and
 * search pages.  It is not for singular views.
 *
 * @since 0.1
 * @access public
 * @uses paginate_links() Creates a string of paginated links based on the arguments given.
 * @param array $args Arguments to customize how the page links are output.
 */
function loop_pagination( $args = '' ) {
    return momtaz_loop_pagination( $args );
} // end loop_pagination()

} // end if