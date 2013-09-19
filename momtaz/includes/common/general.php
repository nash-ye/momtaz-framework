<?php

/**
 * Display the current page title.
 *
 * @since 1.0
 */
function momtaz_title( $args = '' ) {
	do_action( 'momtaz_title', $args );
} // end momtaz_title()

/**
 * The default title callback using the wp_title() function.
 *
 * @since 1.0
 */
function momtaz_wp_title( $args = '' ) {

	$args = wp_parse_args( $args, array(
		'seplocation' => 'right',
		'separator' => '|',
		'echo' => true,
	) );

	$title = wp_title( $args['separator'], false, $args['seplocation'] );

	if ( ! $args['echo'] )
		return $title;

	echo $title;

} // end momtaz_wp_title()

/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @return string The filtered title.
 * @since 1.0
 */
function momtaz_filter_wp_title( $title, $sep ) {

	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );

	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'momtaz' ), max( $paged, $page ) );

	return $title;

} // end momtaz_filter_wp_title()

/**
 * Display the generator meta-tag.
 *
 * @since 1.1
*/
function momtaz_meta_generator() {

	$generator = array( 'WordPress' );

	// Add the parent theme name, which will be Momtaz!
	$generator[] = wp_get_theme( get_template() )->get( 'Name' );

	// Allow plugins/child-themes to filter the generator meta.
	$generator = apply_filters( 'momtaz_meta_generator', $generator );
	$generator = array_filter( array_unique( (array) $generator ) );

	// If it is empty, return NULL.
	if ( empty( $generator ) )
		return;

	echo '<meta name="generator" content="' . esc_attr( implode( ',', $generator ) ) . '">' ."\n";

} // end momtaz_meta_generator()

/**
 * Display the designer meta-tag.
 *
 * @link https://sites.google.com/site/metadesignerspec
 * @since 1.1
*/
function momtaz_meta_designer() {

	// Get the current theme author name.
	$designer = wp_get_theme( get_stylesheet() )->get( 'Author' );

	// Allow plugins/child-themes to filter the designer meta.
	$designer = apply_filters( 'momtaz_meta_designer', $designer );
	$designer = array_filter( array_unique( (array) $designer ) );

	// If it is empty, return NULL.
	if ( empty( $designer ) )
		return;

	echo '<meta name="designer" content="' . esc_attr( implode( ',', $designer ) ) . '">' ."\n";

} // end momtaz_meta_designer()

/**
 * Returns a "Continue Reading" link for excerpts.
 *
 * @return string
 * @since 1.0
 */
function momtaz_continue_reading_link( $post_id = 0 ) {

	if ( empty( $post_id ) )
		$post_id = get_the_ID();

	$link = '<a href="' . esc_url( get_permalink( $post_id ) ) . '" class="more-link"><span>';
	$link .= __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'momtaz' );
	$link .= '</span></a>';

	return apply_filters( 'momtaz_continue_reading_link', $link, $post_id );

} // end momtaz_continue_reading_link()