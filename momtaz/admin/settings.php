<?php
/**
 * Handles the display and functionality of the theme settings page. This provides the needed hooks and
 * meta box calls for developers to create any number of theme settings needed.
 *
 * @package Momtaz
 * @subpackage Admin
 */

/**
 * @since 1.2
 */
class Momtaz_Settings_Page {

	/*** Properties ***********************************************************/

	/**
	 * The page hook suffix.
	 *
	 * @var string
	 * @since 1.2
	 */
	protected $screen_id;

	/*** Methods **************************************************************/

	/**
	 * @return void
	 * @since 1.2
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * @return void
	 * @since 1.2
	 */
	public function admin_menu() {

		if ( empty( $this->screen_id ) ) {

			// Add the theme settings page.
			$this->screen_id = add_menu_page(
				$this->get_page_title(),				// Page title.
				$this->get_menu_title(),				// Menu item title.
				$this->get_capability(),				// User capability.
				$this->get_menu_slug(),					// Screen name.
				array( $this, 'page_content' )			// Callback function.
			);

		}

	}

	/**
	 * @return void
	 * @since 1.2
	 */
	public function admin_init() {

		// Check if the settings page is being shown before running any functions for it.
		if ( ! empty( $this->screen_id ) ) {

			// Get the theme prefix.
			$prefix = THEME_PREFIX;

			// Register the theme setting.
			register_setting(
				"{$prefix}_theme_settings",	// Options group.
				"{$prefix}_theme_settings"	// Database option.
			);

			add_action( "load-{$this->screen_id}", array( $this, 'load_page' ) );

			// Load the needed styles and scripts for the theme settings page.
			add_action( "admin_print_styles-{$this->screen_id}",	array( $this, 'enqueue_styles'	) );
			add_action( "admin_print_scripts-{$this->screen_id}",	array( $this, 'enqueue_scripts' ) );
			add_action( "admin_head-{$this->screen_id}",			array( $this, 'print_scripts'	) );

			// Filter the settings page capability so that it recognizes the 'edit_theme_options' cap.
			add_filter( "option_page_capability_{$prefix}_theme_settings", array( $this, 'get_capability' ) );

		}

	}

	/**
	 * @return void
	 * @since 1.2
	 */
	public function load_page() {

		do_action( 'momtaz_load_settings_page' );

		$this->action_handler();
		$this->load_meta_boxes();

	}

	/**
	 * Returns the theme settings page title.
	 *
	 * @return string
	 * @since 1.2
	 */
	public function get_page_title() {
		$page_title = esc_html__( 'Theme Settings', 'momtaz' );
		return apply_filters( 'momtaz_settings_page_title', $page_title );
	}

	/**
	 * Returns the theme settings page menu item title.
	 *
	 * @return string
	 * @since 1.2
	 */
	public function get_menu_title() {
		$menu_title = wp_get_theme( get_stylesheet() )->get( 'Name' );
		return apply_filters( 'momtaz_settings_page_menu_title', $menu_title );
	}

	/**
	 * Returns the theme settings page menu item slug.
	 *
	 * @return string
	 * @since 1.2
	 */
	public function get_menu_slug() {
		return apply_filters( 'momtaz_settings_page_menu_slug', 'theme-settings' );
	}

	/**
	 * Returns the required capability for viewing and saving theme settings.
	 *
	 * @return string
	 * @since 1.2
	 */
	public function get_capability() {
		return apply_filters( 'momtaz_settings_page_capability', 'edit_theme_options' );
	}

	/**
	 * Displays the theme settings page and calls do_meta_boxes() to allow additional settings
	 * meta boxes to be added to the page.
	 *
	 * @return string
	 * @since 1.2
	 */
	public function page_content() {

		if ( ! current_user_can( $this->get_capability() ) ) {
			return;
		}

		do_action( momtaz_format_hook( 'before_settings_page' ) ); ?>

		<div class="wrap theme-settings">

			<h2>
				<?php echo $this->get_page_title(); ?>
				<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="add-new-h2"><?php esc_html_e( 'Customize', 'momtaz' ); ?></a>
			</h2>

			<?php settings_errors(); ?>

			<?php do_action( momtaz_format_hook( 'open_settings_page' ) ); ?>

			<div class="momtaz-core-settings-wrap">

				<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">

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
							<?php do_meta_boxes( NULL, 'normal', NULL ); ?>
						</div> <!-- .column-1 -->

						<div class="post-box-container column-2 side">
							<?php do_meta_boxes( NULL, 'side', NULL ); ?>
						</div> <!-- .column-2 -->

						<div class="post-box-container column-3 advanced">
							<?php do_meta_boxes( NULL, 'advanced', NULL ); ?>
						</div> <!-- .column-3 -->

					</div> <!-- .metabox-holder -->

				</form> <!-- Form End -->

				<?php do_action( momtaz_format_hook( 'close_settings_page' ) ); ?>

			</div> <!-- .momtaz-core-settings-wrap -->

		</div> <!-- .wrap --> <?php

		do_action( momtaz_format_hook( 'after_settings_page' ) );

	}

	/**
	 * Loads the meta boxes packaged with the framework on the theme settings page.  These meta boxes are
	 * merely loaded with this function.  Meta boxes are only loaded if the feature is supported by the theme.
	 *
	 * @return void
	 * @since 1.2
	 */
	public function load_meta_boxes() {

		$args = get_theme_support( 'momtaz-core-theme-settings' );

		if ( is_array( $args[0] ) ) {

			 // Load the 'About' meta box.
			 if ( in_array( 'about', $args[0], true ) ) {
				 require Momtaz::path( 'admin/meta-boxes/theme-about.php' );
			 }

		}

		do_action( 'momtaz_settings_page_add_meta_boxes' );

	}

	/**
	 * Provide a hook to handle the action request easily on the theme settings page.
	 *
	 * @return void
	 * @since 1.2
	 */
	public function action_handler() {

		if ( empty( $_REQUEST['action'] ) ) {
			 return;
		}

		$action = sanitize_key( $_REQUEST['action'] );

		if ( ! current_user_can( momtaz_settings_page_capability() ) ) {
			return;
		}

		do_action( 'momtaz_settings_page_action_handler', $action );

	}

	/**
	 * Loads the required stylesheets for displaying the theme settings page in the WordPress admin.
	 *
	 * @return void
	 * @since 1.2
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'momtaz-core-admin' );
	}

	/**
	 * Loads the JavaScript files required for managing the meta boxes on the theme settings
	 * page, which allows users to arrange the boxes to their liking.
	 *
	 * @return void
	 * @since 1.2
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'postbox' );
	}

	/**
	 * Loads the JavaScript required for toggling the meta boxes on the theme settings page.
	 *
	 * @return void
	 * @since 1.2
	 */
	public function print_scripts() {

		if ( ! $this->is_current_screen() ) {
			return;
		}

		?>
		<script type="text/javascript">
			//<![CDATA[
			jQuery(document).ready( function($) {
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				postboxes.add_postbox_toggles('<?php echo get_current_screen()->id; ?>');
			});
			//]]>
		</script><?php

	}

	/**
	 * Check if the current screen is the theme settings page.
	 *
	 * @return bool
	 * @since 1.2
	 */
	public function is_current_screen() {

		$current_scrren = get_current_screen();

		if ( ! $current_scrren ) {
			return false;
		}

		return ( $current_scrren->id === $this->screen_id );

	}

}

new Momtaz_Settings_Page();