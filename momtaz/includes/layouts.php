<?php

add_action( 'momtaz_init', 'momtaz_register_core_layouts', 10 );

/**
 * Registers the the framework's default layouts.
 *
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

add_action( 'momtaz_init', 'momtaz_init_current_layout', 15 );

/**
 * @access private
 * @return void
 * @since 1.2
 */
function momtaz_init_current_layout() {

	if ( ! Momtaz_Layouts::get_current_layout() ) {

		if ( is_rtl() ) {
			Momtaz_Layouts::set_current_layout( '2c-l-fixed' );
		} else {
			Momtaz_Layouts::set_current_layout( '2c-r-fixed' );
		}

	}

}

add_action( 'momtaz_init', 'momtaz_init_content_width', 20 );

/**
 * @access private
 * @return void
 * @since 1.2
 */
function momtaz_init_content_width() {

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
 * Function for setting the content width of a theme.  This does not check if a content width has been set; it
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
 * Function for getting the theme's content width.
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
	 * @return object
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
		), $layout, array( 'id' => $id ) );

		self::$layouts[ $id ] = $layout;

		return $layout;

	}

	/**
	 * Deregister a layout from Momtaz.
	 *
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
	 * Get the current layout.
	 *
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
	 * @return bool
	 * @since 1.2
	 */
	public static function is_exists( $id ) {
		return isset( self::$layouts[ $id ] );
	}

	/**
	 * Get all registered Momtaz layouts.
	 *
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