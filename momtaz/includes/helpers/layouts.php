<?php

/**
 * Registers the the framework's default layouts.
 *
 * Register the default layouts of Momtaz framework. The layouts are supplied as an associative
 * array 'ID => args' then, the function uses Momtaz_Layouts::register() to register each of them.
 * The default list contains the possible layouts needed, but still can be hooked into using 'momtaz_core_layouts'
 * filter hook.
 *
 * @uses Momtaz_Layouts::register() Register a new layout in Momtaz.
 * @access private
 * @return void
 * @since 1.2
 */
function momtaz_register_core_layouts() {

	// Get the available core layouts.
	$core_layouts = array(
		'1c-fixed' => array(
			'name' => __( 'One-column, Fixed', 'momtaz' ),
			'content_width' => 940,
			'type' => 'fixed',
		),
		'1c-fluid' => array(
			'name' => __( 'One-column, Fluid', 'momtaz' ),
			'type' => 'fluid',
		),
		'2c-l-fixed' => array(
			'name' => __( 'Two-column, Left, Fixed', 'momtaz' ),
			'content_width' => 620,
			'type' => 'fixed',
		),
		'2c-l-fluid' => array(
			'name' => __( 'Two-column, Left, Fluid', 'momtaz' ),
			'type' => 'fluid',
		),
		'2c-r-fixed' => array(
			'name' => __( 'Two-column, Right, Fixed', 'momtaz' ),
			'content_width' => 620,
			'type' => 'fixed',
		),
		'2c-r-fluid' => array(
			'name' => __( 'Two-column, Right, Fluid', 'momtaz' ),
			'type' => 'fluid',
		),
		'3c-l-fixed' => array(
			'name' => __( 'Three-column, Left, Fixed', 'momtaz' ),
			'content_width' => 540,
			'type' => 'fixed',
		),
		'3c-l-fluid' => array(
			'name' => __( 'Three-column, Left, Fluid', 'momtaz' ),
			'type' => 'fluid',
		),
		'3c-r-fixed' => array(
			'name' => __( 'Three-column, Right, Fixed', 'momtaz' ),
			'content_width' => 540,
			'type' => 'fixed',
		),
		'3c-r-fluid' => array(
			'name '=> __( 'Three-column, Right, Fluid', 'momtaz' ),
			'type' => 'fluid',
		),
	);

	$core_layouts = apply_filters( 'momtaz_core_layouts', $core_layouts );

	foreach( $core_layouts as $id => $layout ) {

		// Register the layout.
		Momtaz_Layouts::register( $id, $layout );

	}

}

/**
 * Ajust the current theme layout.
 *
 * This function sets the proper theme-layout and content-width, if not set.
 * The '2c-l-fixed' layout is set as the default for RTL
 * and '2c-r-fixed' layout for LTR.
 *
 * @uses Momtaz_Layouts::set_current_layout() Set the current layout.
 * @uses momtaz_set_content_width() Set the content width.
 * @access private
 * @return void
 * @since 1.3
 */
function momtaz_adjust_current_layout() {

	if ( ! Momtaz_Layouts::get_current_layout() ) {

		if ( is_rtl() ) {
			Momtaz_Layouts::set_current_layout( '2c-l-fixed' );
		} else {
			Momtaz_Layouts::set_current_layout( '2c-r-fixed' );
		}

	}

	if ( ! momtaz_get_content_width() ) {

		// Get the current layout.
		$layout = Momtaz_Layouts::get_current_layout();

		// Set the WordPress content width.
		if ( $layout && ! empty( $layout->content_width ) ) {
			momtaz_set_content_width( $layout->content_width );
		}

	}

}

/**
 * Setting the content width of a theme.
 *
 * This function sets the content width of a theme without checking if it has been set, it
 * simply overwrites whatever the content width is.
 *
 * @since 1.1
 * @access public
 * @global int $content_width The width for the theme's content area.
 * @param int $width Numeric value of the width to set.
 */
function momtaz_set_content_width( $width ) {
	global $content_width;
	$content_width = absint( $width );
}

/**
 * Getting the theme's content width.
 *
 * @since 1.1
 * @access public
 * @global int $content_width The width for the theme's content area.
 * @return int $content_width
 */
function momtaz_get_content_width() {
	global $content_width;
	return $content_width;
}

/**
 * The Momtaz Layouts manager class.
 *
 * @since 1.2
 */
final class Momtaz_Layouts {

	/*** Properties ***********************************************************/

	/**
	 * The layouts list.
	 *
	 * @var array
	 * @since 1.2
	 */
	private static $layouts = array();

	/*** Methods **************************************************************/

	/**
	 * Register a new layout in Momtaz.
	 *
	 * Register the layout by adding it to the registered layouts array. The layout data
	 * is added as an object.
	 *
	 * @param string $id The ID of the layout.
	 * @param array $layout An array of the layout arguments.
	 * @return object|bool
	 * @since 1.2
	 */
	public static function register( $id, array $layout ) {

		if ( ! $id || self::is_exists( $id ) ) {
			return false;
		}

		$layout = (object) array_merge( array(
			'content_width' => 0,
			'name'			=> '',
			'type'			=> '',
		), $layout );

		$layout->id = $id; // Store the ID.

		self::$layouts[ $id ] = $layout;

		return $layout;

	}

	/**
	 * Deregister a layout from Momtaz.
	 *
	 * @param string $id The ID of the layout.
	 * @return bool
	 * @since 1.2
	 */
	public static function deregister( $id ) {

		if ( ! $id || ! self::is_exists( $id ) ) {
			return false;
		}

		unset( self::$layouts[ $id ] );

		return true;

	}

	/**
	 * Check if the given $id match the current layout ID.
	 *
	 * @param string $id The ID of the layout.
	 * @uses self::get_current_layout() Get the current layout.
	 * @return bool
	 * @since 1.2
	 */
	public static function is_current_layout( $id ) {

		$layout = self::get_current_layout();

		if ( empty( $layout ) ) {
			return false;
		}

		return ( $layout->id === $id );

	}

	/**
	 * Set the current layout, specified by the ID.
	 *
	 * @param string $id The ID of the layout.
	 * @uses self::get_layouts() Get all the registered layouts.
	 * @uses self::is_exists() Check if a layout exists.
	 * @return bool
	 * @since 1.2
	 */
	public static function set_current_layout( $id ) {

		if ( ! $id || ! self::is_exists( $id ) ) {
			return false;
		}

		foreach( self::get_layouts() as $layout ) {
			unset( $layout->current );
		}

		self::$layouts[ $id ]->current = true;

		return true;

	}

	/**
	 * Get the current layout data object.
	 *
	 * @uses self::get_layouts() Get all registered Momtaz layouts.
	 * @return object|null
	 * @since 1.2
	 */
	public static function get_current_layout() {

		foreach( self::get_layouts() as $layout ) {

			if ( isset( $layout->current ) && $layout->current ) {
				return $layout;
			}

		}

	}

	/**
	 * Get a layout data object, specified by the ID.
	 *
	 * @param string $id The ID of the layout to check.
	 * @uses self::$layouts The array of all registered layouts.
	 * @uses self::is_exists() Check if a layout exists.
	 * @return object|null
	 * @since 1.2
	 */
	public static function get_layout( $id ) {

		if ( ! $id || ! self::is_exists( $id ) ) {
			return;
		}

		return self::$layouts[ $id ];

	}

	/**
	 * Check if a layout exists.
	 *
	 * @param string $id The ID of the layout to check.
	 * @return bool
	 * @since 1.2
	 */
	public static function is_exists( $id ) {
		return isset( self::$layouts[ $id ] );
	}

	/**
	 * Get all registered Momtaz layouts. It returns an array of objects for all registered layouts.
	 *
	 * @uses self::$layouts The array of registered layouts.
	 * @return array
	 * @since 1.2
	 */
	public static function get_layouts() {
		return self::$layouts;
	}

	/**
	 * A dummy constructor.
	 *
	 * @return void
	 * @since 1.2
	 */
	private function __construct() {}

}
