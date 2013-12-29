<?php
/**
 * Theme administration functions used with other components of the framework admin.  This file is for
 * setting up any basic features and holding additional admin helper functions.
 *
 * @package Momtaz
 * @subpackage Admin
 */

add_action( 'admin_enqueue_scripts', 'momtaz_admin_register_styles' );

/**
 * Registers the framework's 'admin.css' stylesheet file. The function does not load the stylesheet. It merely
 * registers it with WordPress.
 *
 * @return void
 * @since 1.0
 */
function momtaz_admin_register_styles() {

	// Register the core admin style.
	wp_register_style( 'momtaz-core-admin', momtaz_theme_uri( 'content/styles/admin.css' ), false, Momtaz::VERSION );

}

/**
 * Some common checks to perform before save the meta data associated with a post.
 *
 * @return bool
 * @since 1.0
*/
function momtaz_verify_common_post_meta_box( $post, $args ){

	$args = wp_parse_args( $args, array(
		'nonce_action' => -1,
		'nonce_name' => '',
	) );

	if ( ! empty( $args['nonce_name'] ) ) {

		// Verify that the input is coming from the proper form
		if ( empty( $_POST[ $args['nonce_name'] ] ) ) {
			return false;
		}

		// Verify that correct nonce action was used in the form.
		if ( ! wp_verify_nonce( $_POST[ $args['nonce_name'] ], $args['nonce_action'] ) ) {
			return false;
		}

	}

	// Don't save if the user hasn't submitted the changes
	if ( wp_is_post_autosave( $post ) || wp_is_post_revision( $post ) ) {
		return false;
	}

	// Check if the current can edit the post.
	if ( ! current_user_can( 'edit_post', $post ) ) {
		return false;
	}

	return true;

}

/**
 * The common way to save the meta data associated with a post.
 *
 * @return bool
 * @since 1.1
*/
function momtaz_save_post_meta_array( $post_id, $meta ){

	if ( ! is_array( $meta ) ) {
		return false;
	}

	if ( ! is_int( $post_id ) || empty( $post_id ) ) {
		return false;
	}

	foreach ( $meta as $meta_key => $new_meta_value ) {

		// Get the meta value of the custom field key.
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		// If a new meta value was added and there was no previous value, add it.
		if ( $new_meta_value && '' == $meta_value ) {
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		// If the new meta value does not match the old value, update it.
		} elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
			update_post_meta( $post_id, $meta_key, $new_meta_value );

		// If there is no new meta value but an old value exists, delete it.
		} elseif ( '' == $new_meta_value && $meta_value ) {
			delete_post_meta( $post_id, $meta_key, $meta_value );
		}

	}

	return true;

}