<?php
/**
 * Creates a meta box for the theme settings page, which displays information about the theme.  If a child theme
 * is in use, an additional meta box will be added with its information.  To use this feature, the theme must
 * support the 'about' argument for 'momtaz-core-theme-settings' feature.
 *
 * @package Momtaz
 * @subpackage Admin
 */

// Create the about theme meta box on the 'add_meta_boxes' hook.
add_action( 'momtaz_settings_page_add_meta_boxes', 'momtaz_meta_box_theme_add_about' );

/**
 * Adds the core about theme meta box to the theme settings page.
 *
 * @since 1.0
 * @return void
 */
function momtaz_meta_box_theme_add_about( $screen_ID ) {

	// Get theme information.
	$theme = wp_get_theme( get_template() );

	// Adds the About box for the parent theme.
	add_meta_box( 'momtaz-core-about-theme', sprintf( __( 'About %s', 'momtaz' ), $theme->get( 'Name' ) ), 'momtaz_meta_box_theme_display_about', $screen_ID, 'side', 'high' );

	// If the user is using a child theme, add an About box for it.
	if ( is_child_theme() ) {

		// Get child theme info.
		$child_theme = wp_get_theme( get_stylesheet() );

		// Adds the About box for the child theme.
		add_meta_box( 'momtaz-core-about-child', sprintf( __( 'About %s', 'momtaz' ), $child_theme->get( 'Name' ) ), 'momtaz_meta_box_theme_display_about', $screen_ID, 'side', 'high' );

	}

}

/**
 * Creates an information meta box with no settings about the theme. The meta box will display
 * information about both the parent theme and child theme. If a child theme is active, this function
 * will be called a second time.
 *
 * @since 1.0
 * @param $object Variable passed through the do_meta_boxes() call.
 * @param array $box Specific information about the meta box being loaded.
 */
function momtaz_meta_box_theme_display_about( $object, $box ) {

	switch( $box['id'] ) {

		// Grab theme information for the parent theme.
		case 'momtaz-core-about-theme':
			$theme_data = wp_get_theme( get_template() );
			break;

		// Grab theme information for the child theme.
		default:
		case 'momtaz-core-about-child':
			$theme_data = wp_get_theme( get_stylesheet() );
			break;

	}

	?>

	<table class="form-table">
		<tr>
			<th><?php _e( 'Theme:', 'momtaz' ); ?></th>
			<td><a href="<?php echo esc_url( $theme_data->display( 'ThemeURI' ) ); ?>"><?php echo $theme_data->display( 'Name' ); ?> <?php echo $theme_data->display( 'Version' ); ?></a></td>
		</tr>
		<tr>
			<th><?php _e( 'Author:', 'momtaz' ); ?></th>
			<td><?php echo $theme_data->display( 'Author' ); ?></td>
		</tr>
		<tr>
			<th><?php _e( 'Description:', 'momtaz' ); ?></th>
			<td><?php echo $theme_data->display( 'Description' ); ?></td>
		</tr>
	</table> <!-- .form-table -->

<?php }