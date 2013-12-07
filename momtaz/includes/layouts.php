<?php
/**
 * Momtaz Layouts Manager Class.
 *
 * @since 1.2
 */
class Momtaz_Layouts {

	/*** Properties ***********************************************************/

	/**
	 * The current layout key.
	 *
	 * @var string
	 * @since 1.2
	 */
	protected static $current_layout;

	/**
	 * The Layouts List.
	 *
	 * @var array
	 * @since 1.2
	 */
	protected static $layouts = array();


	/*** Methods **************************************************************/

	// Getters

	/**
	 * Get a layout object by key.
	 *
	 * @return object
	 * @since 1.1
	 */
	public static function get_by_key( $key ) {

		$key = sanitize_key( $key );

		if ( isset( self::$layouts[ $key ] ) ) {
			return self::$layouts[ $key ];
		}

	}

	/**
	 * Get a layout object by key.
	 *
	 * @return object
	 * @since 1.1
	 */
	public static function get_current_layout() {
		return self::get_by_key( self::$current_layout );
	}

	/**
	 * Get the layouts list.
	 *
	 * @return array
	 * @since 1.2
	 */
	public static function get( $args = '', $operator = 'AND' ) {
		return wp_list_filter( self::$layouts, $args, $operator );
	}

	/**
	 * Set the current layout key.
	 *
	 * @return array
	 * @since 1.2
	 */
	public static function set_current_layout( $key ) {

		$key = sanitize_key( $key );

		if ( ! self::get_by_key( $key ) ) {
			return false;
		}

		self::$current_layout = $key;

		return true;

	}

	/**
	 * Register a layout.
	 *
	 * @return bool
	 * @since 1.2
	 */
	public static function register( array $args ) {

		$args = (object) wp_parse_args( $args, array(
			'content_width' => 0,
			'style_path' => '',
			'style_uri' => '',
			'title' => '',
			'key' => '',
		) );

		$args->key = sanitize_key( $args->key );

		if ( empty( $args->key ) ) {
			return false;
		}

		self::$layouts[ $args->key ] = $args;

		return true;

	}

	/**
	 * Deregister a layout.
	 *
	 * @return bool
	 * @since 1.2
	 */
	public static function deregister( $key ) {

		$key = sanitize_key( $key );

		if ( empty( $key ) ) {
			return false;
		}

		unset( self::$layouts[ $key ] );

		return true;

	}

}