<?php
/**
 * Handles the display and functionality of the theme settings page. This provides the needed hooks and
 * meta box calls for developers to create any number of theme settings needed.
 *
 * @package Momtaz
 * @subpackage Admin
 */

add_action( 'admin_menu', 'momtaz_settings_page_init' );

/**
 * Initializes all the theme settings page functionality. This function is used to create the theme settings
 * page, then use that as a launchpad for specific actions that need to be tied to the settings page.
 *
 * @since 1.0
 * @return void
 */
function momtaz_settings_page_init() {

	// Get the theme prefix.
	$prefix = THEME_PREFIX;

	// Register the theme setting.
	register_setting(
		"{$prefix}_theme_settings",	// Options group.
		"{$prefix}_theme_settings"	// Database option.
	);

	// Create the theme settings page.
	$settings_page = add_menu_page(
		momtaz_get_settings_page_title(),		// Settings page name.
		momtaz_get_settings_page_menu_title(),  // Menu item name.
		momtaz_settings_page_capability(),		// Required capability.
		'theme-settings',						// Screen name.
		'momtaz_theme_settings_page'			// Callback function.
	);

	// Check if the settings page is being shown before running any functions for it.
	if ( ! empty( $settings_page ) ) {

		// Add the 'momtaz_load_settings_page' hook.
		add_action( "load-{$settings_page}", function() {
			do_action( 'momtaz_load_settings_page' );
		} );

		// Handle the action request in the settings page.
		add_action( "load-{$settings_page}", 'momtaz_settings_page_action_handler' );

		// Load the default theme meta-boxes for the settings page.
		add_action( "load-{$settings_page}", 'momtaz_load_settings_page_meta_boxes' );

		// Add the 'momtaz_settings_page_add_meta_boxes' hook.
		add_action( "load-{$settings_page}", function() {
			do_action( 'momtaz_settings_page_add_meta_boxes' );
		} );

		// Load the JavaScript and Stylehsheets needed for the theme settings.
		add_action( "admin_print_styles-{$settings_page}", 'momtaz_settings_page_enqueue_styles'  );
		add_action( "admin_print_scripts-{$settings_page}", 'momtaz_settings_page_enqueue_script' );
		add_action( "admin_head-{$settings_page}", 'momtaz_settings_page_load_scripts'			);

		// Filter the settings page capability so that it recognizes the 'edit_theme_options' cap.
		add_filter( "option_page_capability_{$prefix}_theme_settings", 'momtaz_settings_page_capability' );

	} // end if

} // end momtaz_settings_page_init()

/**
 * Returns the required capability for viewing and saving theme settings.
 *
 * @return string
 * @since 1.0
 */
function momtaz_settings_page_capability() {
	return apply_filters( 'momtaz_settings_page_capability', 'edit_theme_options' );
} // end momtaz_settings_page_capability()

/**
 * Returns the theme settings page title .
 *
 * @return string
 * @since 1.0
 */
function momtaz_get_settings_page_title() {
	return apply_filters( 'momtaz_get_settings_page_title', esc_html__( 'Theme Settings', 'momtaz' ) );
} // end momtaz_get_settings_page_title()

/**
 * Returns the theme settings page menu item title .
 *
 * @return string
 * @since 1.0
 */
function momtaz_get_settings_page_menu_title() {
	return apply_filters( 'momtaz_get_settings_menu_title', wp_get_theme( get_stylesheet() )->get( 'Name' ) );
} // end momtaz_get_settings_menu_title()

/**
 * Displays the theme settings page and calls do_meta_boxes() to allow additional settings
 * meta boxes to be added to the page.
 *
 * @since 1.0
 * @return void
 */
function momtaz_theme_settings_page() {

	$screen = get_current_screen();

	if ( 'theme-settings' !== $screen->parent_base )
		return;

	do_action( momtaz_format_hook( 'before_settings_page' ) ); ?>

	<div class="wrap theme-settings">

		<?php screen_icon( 'themes' ) ?>

		<h2>
			<?php echo momtaz_get_settings_page_title(); ?>
			<a href="<?php echo admin_url( 'customize.php' ); ?>" class="add-new-h2"><?php esc_html_e( 'Customize', 'momtaz' ); ?></a>
		</h2>

		<?php settings_errors(); ?>

		<?php do_action( momtaz_format_hook( 'open_settings_page' ) ); ?>

		<div class="momtaz-core-settings-wrap">

			<form action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" method="post">

				<p class="submit">

					<?php do_action( momtaz_format_hook( 'settings_page_before_submit_button' ) ); ?>

					<?php submit_button( esc_attr__( 'Update Settings', 'momtaz' ), 'primary', 'update', false ) ?>

					<?php do_action( momtaz_format_hook( 'settings_page_after_submit_button' ) ); ?>

				</p> <!-- .submit -->

				<?php settings_fields( momtaz_format_hook( 'theme_settings' ) ); ?>
				<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

				<div class="metabox-holder">

					<div class="post-box-container column-1 normal">
						<?php do_meta_boxes( $screen->id, 'normal', null ); ?>
					</div> <!-- .column-1 -->

					<div class="post-box-container column-2 side">
						<?php do_meta_boxes( $screen->id, 'side', null ); ?>
					</div> <!-- .column-2 -->

					<div class="post-box-container column-3 advanced">
						<?php do_meta_boxes( $screen->id, 'advanced', null ); ?>
					</div> <!-- .column-3 -->

				</div> <!-- .metabox-holder -->

			</form> <!-- Form End -->

			<?php do_action( momtaz_format_hook( 'close_settings_page' ) ); ?>

		</div> <!-- .momtaz-core-settings-wrap -->

	</div> <!-- .wrap --> <?php

	do_action( momtaz_format_hook( 'after_settings_page' ) );

} // end momtaz_theme_settings_page()

/**
 * Provide a hook to handle the action request easily on the theme settings page.
 *
 * @return void
 * @since 1.0
 */
function momtaz_settings_page_action_handler() {

	if ( empty( $_REQUEST['action'] ) )
		 return;

	$action = sanitize_key( $_REQUEST['action'] );

	if ( ! current_user_can( momtaz_settings_page_capability() ) )
		return;

	do_action( 'momtaz_settings_page_action_handler', $action );

} // end momtaz_settings_page_action_handler()

/**
 * Loads the meta boxes packaged with the framework on the theme settings page.  These meta boxes are
 * merely loaded with this function.  Meta boxes are only loaded if the feature is supported by the theme.
 *
 * @return void
 * @since 1.0
 */
function momtaz_load_settings_page_meta_boxes() {

   $args = get_theme_support( 'momtaz-core-theme-settings' );

   if ( is_array( $args[0] ) ) {

		// Load the 'About' meta box.
		if ( in_array( 'about', $args[0], true ) )
			require( trailingslashit( MOMTAZ_ADMIN_DIR ) . 'meta-boxes/theme-about.php' );

   } // end if

} // end momtaz_load_settings_page_meta_boxes()

/**
 * Loads the required stylesheets for displaying the theme settings page in the WordPress admin.
 *
 * @return void
 * @since 1.0
 */
function momtaz_settings_page_enqueue_styles() {
	wp_enqueue_style( 'momtaz-core-admin' );
} // end momtaz_settings_page_enqueue_styles()

/**
 * Loads the JavaScript files required for managing the meta boxes on the theme settings
 * page, which allows users to arrange the boxes to their liking.
 *
 * @return void
 * @since 1.0
 */
function momtaz_settings_page_enqueue_script() {
	wp_enqueue_script( 'postbox' );
} // end momtaz_settings_page_enqueue_script()

/**
 * Loads the JavaScript required for toggling the meta boxes on the theme settings page.
 *
 * @return void
 * @since 1.0
 */
function momtaz_settings_page_load_scripts() {

	$screen = get_current_screen();

	if ( 'theme-settings' !== $screen->parent_base )
		return;

	?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			postboxes.add_postbox_toggles( '<?php echo $screen->id; ?>' );
		});
		//]]>
	</script><?php
} // end momtaz_settings_page_load_scripts()