<?php
namespace Nmwdhj\Decorators;

/**
 * The Nmwdhj Decorators manager class.
 *
 * @since 1.0
 */
class Manager {

	/*** Properties ***********************************************************/

	/**
	 * Decorators list.
	 *
	 * @since 1.0
	 * @var array
	 */
	protected static $decorators = array();


	/*** Methods ***************************************************************/

	// Getters

	/**
	 * Get a decorator by key.
	 *
	 * @since 1.0
	 * @return object
	 */
	public static function get_by_key( $key ) {

		$key = sanitize_key( $key );

		if ( ! empty( $key ) ) {

			$decorators = self::get();

			if ( isset( $decorators[ $key ] ) )
				return $decorators[ $key ];

		} // end if

	} // end get_by_key()

	/**
	 * Retrieve a list of registered decorators.
	 *
	 * @since 1.0
	 * @return array
	 */
	public static function get( array $args = null, $operator = 'AND' ) {
		return wp_list_filter( self::$decorators, $args, $operator );
	} // end get()

	// Register/Deregister

	/**
	 * Register a decorator.
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

		self::$decorators[ $args['key'] ] = (object) $args;

		return true;

	} // end register()

	/**
	 * Register the Nmwdhj default decorators.
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function register_defaults() {

		self::register( 'tag', array(
			'class_name' => 'Nmwdhj\Decorators\Tag',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/decorators/Tag.php' ),
		) );

		self::register( 'label', array(
			'class_name' => 'Nmwdhj\Decorators\Label',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/decorators/Label.php' ),
		) );

		self::register( 'description', array(
			'class_name' => 'Nmwdhj\Decorators\Description',
			'class_path' => \Nmwdhj\get_path( 'view-helpers/decorators/Description.php' ),
		) );

	} // end register_defaults()

	/**
	 * Remove a registered decorator.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public static function deregister( $key ) {

		$key = sanitize_key( $key );

		if ( empty( $key ) )
			return false;

		unset( self::$decorators[ $key ] );

		return true;

	} // end deregister()

	// Checks

	/**
	 * Check a decorator class.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public static function check_class( $class_name, $autoload = true ) {

		if ( empty( $class_name ) )
			return false;

		if ( ! class_exists( $class_name, (bool) $autoload ) )
			return false;

		if ( ! is_subclass_of( $class_name, 'Nmwdhj\Decorators\Decorator' ) )
			return false;

		return true;

	} // end check_class()

	// Loaders

	/**
	 * Load decorator class file.
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function load_class( $class_name, $require_once = false ) {

		if ( ! class_exists( $class_name, false ) ) {

			$decorator = self::get( array( 'class_name' => $class_name ), 'OR' );
			$decorator = reset( $decorator ); // Get the first result.

			if ( ! empty( $decorator->class_path ) && file_exists( $decorator->class_path ) )
				( $require_once ) ? require_once $decorator->class_path : require $decorator->class_path;

		} // end if

	} // end load_class()

} // end Class Decorators

// Register the autoload function.
spl_autoload_register( 'Nmwdhj\Decorators\Manager::load_class' );