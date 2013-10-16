<?php
namespace Nmwdhj\Elements;

/**
 * The Nmwdhj elements manager class.
 *
 * @since 1.0
 */
final class Manager {

	/*** Properties ***********************************************************/

	/**
	 * Elements list.
	 *
	 * @since 1.0
	 * @var array
	 */
	protected static $elements = array();


	/*** Methods **************************************************************/

	// Getters

	/**
	 * Get an element by key.
	 *
	 * @since 1.0
	 * @return object
	 */
	public static function get_by_key( $key ) {

		$key = sanitize_key( $key );

		if ( ! empty( $key ) ) {

			$elements = self::get();

			if ( isset( $elements[ $key ] ) )
				return $elements[ $key ];

			foreach ( $elements as $element ) {

				if ( in_array( $key, (array) $element->aliases ) )
					return $element;

			} // end foreach

		} // end if

	} // end get_by_key()

	/**
	 * Retrieve a list of registered elements.
	 *
	 * @since 1.0
	 * @return array
	 */
	public static function get( array $args = null, $operator = 'AND' ) {
		return wp_list_filter( self::$elements, $args, $operator );
	} // end get()

	// Register/Deregister

	/**
	 * Register an element.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public static function register( $key, array $args ) {

		$args['key'] = sanitize_key( $key );

		if ( empty( $args['key'] ) )
			return false;

		$args = wp_parse_args( $args, array(
			'aliases' => array(),
			'class_name' => '',
			'class_path' => '',
		) );

		if ( empty( $args['class_name'] ) )
			return false;

		$args['aliases'] = (array) $args['aliases'];
		array_walk( $args['aliases'], 'sanitize_key' );

		// Register the element.
		self::$elements[ $args['key'] ] = (object) $args;

		return true;

	} // end register()

	/**
	 * Register the default elements.
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function register_defaults() {

		self::register( 'button', array(
			'class_name' => 'Nmwdhj\Elements\Button',
			'class_path' => \Nmwdhj\get_path( 'elements/Button.php' ),
			'aliases' => array( 'button_submit', 'button_reset' ),
		) );

		self::register( 'select', array(
			'class_name' => 'Nmwdhj\Elements\Select',
			'class_path' => \Nmwdhj\get_path( 'elements/Select.php' ),
		) );

		self::register( 'textarea', array(
			'class_name' => 'Nmwdhj\Elements\Textarea',
			'class_path' => \Nmwdhj\get_path( 'elements/Textarea.php' ),
		) );

		self::register( 'wp_editor', array(
			'class_name' => 'Nmwdhj\Elements\WP_Editor',
			'class_path' => \Nmwdhj\get_path( 'elements/WP_Editor.php' ),
		) );

		self::register( 'checkbox', array(
			'class_name' => 'Nmwdhj\Elements\Checkbox',
			'class_path' => \Nmwdhj\get_path( 'elements/Checkbox.php' ),
			'aliases' => array( 'input_checkbox' ),
		) );

		self::register( 'checkboxes', array(
			'class_name' => 'Nmwdhj\Elements\Checkboxes',
			'class_path' => \Nmwdhj\get_path( 'elements/Checkboxes.php' ),
			'aliases' => array( 'multi_checkbox' ),
		) );

		self::register( 'input', array(
			'class_name' => 'Nmwdhj\Elements\Input',
			'class_path' => \Nmwdhj\get_path( 'elements/Input.php' ),
			'aliases' => array(
				'input_text', 'input_url', 'input_email', 'input_range', 'input_search', 'input_date', 'input_file',
				'input_hidden', 'input_number', 'input_password', 'input_color', 'input_submit', 'input_week',
				'input_time', 'input_radio', 'input_month', 'input_image',
			),
		) );

	} // end register_defaults()

	/**
	 * Remove a registered element.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public static function deregister( $key ) {

		$key = sanitize_key( $key );

		if ( empty( $key ) )
			return false;

		if ( ! isset( self::$elements[ $key ] ) ) {

			foreach ( self::$elements as &$element )
				$element->aliases = array_diff( (array) $element->aliases, array( $key ) );

		} else {

			unset( self::$elements[ $key ] );

		} // end if

		return true;

	} // end deregister()

	// Checks

	/**
	 * Check an element class.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public static function check_class( $class_name, $autoload = true ) {

		if ( empty( $class_name ) )
			return false;

		if ( ! class_exists( $class_name, (bool) $autoload ) )
			return false;

		if ( ! is_subclass_of( $class_name, 'Nmwdhj\Elements\Element' ) )
			return false;

		return true;

	} // end check_class()

	// Loaders

	/**
	 * Load element class file.
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function load_class( $class_name, $require_once = false ) {

		if ( ! class_exists( $class_name, false ) ) {

			$element = self::get( array( 'class_name' => $class_name ), 'OR' );
			$element = reset( $element ); // Get the first result.

			if ( ! empty( $element->class_path ) && file_exists( $element->class_path ) )
				( $require_once ) ? require_once $element->class_path : require $element->class_path;

		} // end if

	} // end load_class()

} // end Class Manager

// Register the autoload function.
spl_autoload_register( 'Nmwdhj\Elements\Manager::load_class' );