<?php
/**
 * Default Structure Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

momtaz_template_part( 'head' );

	momtaz_struct_markup( 'body', '<body%atts%>' );

		do_action( momtaz_format_hook( 'before_wrapper' ) );

		momtaz_struct_markup( 'wrapper', '<div%atts%>' );

			get_header();

			do_action( momtaz_format_hook( 'before_container' ) );

			momtaz_struct_markup( 'container', '<div%atts%>' );

				momtaz_struct_markup( 'content', '<div%atts%>' );

					do_action( momtaz_format_hook( 'content' ) );

				momtaz_struct_markup( 'content', '</div> <!-- #content -->' );

				get_sidebar();

			momtaz_struct_markup( 'container', '</div> <!-- #container -->' );

			do_action( momtaz_format_hook( 'after_container' ) );

			get_footer();

		momtaz_struct_markup( 'wrapper', '</div> <!-- #wrapper -->' );

		do_action( momtaz_format_hook( 'after_wrapper' ) );

		wp_footer();

	momtaz_struct_markup( 'body', '</body> <!-- body -->' );

momtaz_struct_markup( 'html', '</html> <!-- html -->' );