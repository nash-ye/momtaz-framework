<?php
namespace Nmwdhj\Views;

/**
 * The Nmwdhj Views class.
 *
 * @since 1.0
 */
final class Manager {

	/*** Properties ***********************************************************/

	/**
	 * Views list.
	 *
	 * @since 1.0
	 * @var array
	 */
	protected static $views = array();


	/*** Methods ***************************************************************/

	// Getters

	/**
	 * Get a view by key.
	 *
	 * @since 1.0
	 * @return object
	 */
	public static function get_by_key( $key ) {

		$key = sanitize_key( $key );

		if ( ! empty( $key ) ) {

			$views = self::get();

			if ( isset( $views[ $key ] ) )
				return $views[ $key ];

		} // end if

	} // end get_by_key()

	/**
	 * Retrieve a list of registered views.
	 *
	 * @since 1.0
	 * @return array
	 */
	public static function get( array $args = null, $operator = 'AND' ) {
		return wp_list_filter( self::$views, $args, $operator );
	} // end get()

	// Register/Deregister

	/**
	 * Register a view.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public static function register( $key, array $args ) {

		$args['key'] = sanitize_key( $key );

		if ( empty( $args['key'] ) )
			return false;

		$args = wp_parse_args( $args, array(
			'class_name' => '',
			'class_path' => '',
		) );

		if ( empty( $args['class_name'] ) )
			return false;

		self::$views[ $args['key'] ] = (object) $args;

		return true;

	} // end register()

	/**
	 * Register the default views.
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function register_defaults() {

		self::register( 'button', array(
			'class_name' => 'Nmwdhj\Views\Button',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Button.php' ),
		) );

		self::register( 'input', array(
			'class_name' => 'Nmwdhj\Views\Input',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Input.php' ),
		) );

		self::register( 'select', array(
			'class_name' => 'Nmwdhj\Views\Select',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Select.php' ),
		) );

		self::register( 'textarea', array(
			'class_name' => 'Nmwdhj\Views\Textarea',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Textarea.php' ),
		) );

		self::register( 'wp_editor', array(
			'class_name' => 'Nmwdhj\Views\WP_Editor',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/WP_Editor.php' ),
		) );

		self::register( 'checkbox', array(
			'class_name' => 'Nmwdhj\Views\Checkbox',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Checkbox.php' ),
		) );

		self::register( 'checkboxes', array(
			'class_name' => 'Nmwdhj\Views\Checkboxes',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/Checkboxes.php' ),
		) );

	} // end register_defaults()

	/**
	 * Remove a registered view.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public static function deregister( $key ) {

		$key = sanitize_key( $key );

		if ( empty( $key ) )
			return false;

		unset( self::$views[ $key ] );

		return true;

	} // end deregister()

	// Checks

	/**
	 * Check a view class.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public static function check_class( $class_name, $autoload = true ) {

		if ( empty( $class_name ) )
			return false;

		if ( ! class_exists( $class_name, (bool) $autoload ) )
			return false;

		if ( ! is_subclass_of( $class_name, 'Nmwdhj\Views\View' ) )
			return false;

		return true;

	} // end check_class()

	// Loaders

	/**
	 * Load view class file.
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function load_class( $class_name, $require_once = false ) {

		if ( ! class_exists( $class_name, false ) ) {

			$view = self::get( array( 'class_name' => $class_name ), 'OR' );
			$view = reset( $view );

			if ( ! empty( $view->class_path ) && file_exists( $view->class_path ) )
				( $require_once ) ? require_once $view->class_path : require $view->class_path;

		} // end if

	} // end load_class()

} // end Class Views

// Register the autoload function.
spl_autoload_register( 'Nmwdhj\Views\Manager::load_class' );