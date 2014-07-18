<?php

add_action( 'momtaz_init', 'momtaz_register_core_stacks', 5 );

/**
 * Registers the the framework's default template stacks.
 *
 * @return void
 * @since 1.3
 */
function momtaz_register_core_stacks() {

	if ( is_child_theme() ) {

		// Register the child theme directory.
		Momtaz_Stacks::register( 'child-theme', array(
			'uri' => get_stylesheet_directory_uri(),
			'path' => get_stylesheet_directory(),
			'priority' => 5,
		) );

	}

	// Register the parent theme directory.
	Momtaz_Stacks::register( 'parent-theme', array(
		'uri' => get_template_directory_uri(),
		'path' => get_template_directory(),
		'priority' => 10,
	) );

}

/**
 * The Momtaz template stacks manager class.
 *
 * @since 1.3
 */
final class Momtaz_Stacks {

	/*** Properties ***********************************************************/

	/**
	 * The template stacks list.
	 *
	 * @access private
	 * @var array
	 * @since 1.3
	 */
	private static $stacks = array();


	/*** Methods **************************************************************/

	/**
	 * Get the template stacks list.
	 *
	 * @return array
	 * @since 1.3
	 */
	public static function get( $id = '' ) {

		$value = array();

		if ( empty( $id ) ) {
			$value = self::$stacks;

		} elseif ( self::is_exists( $id ) ) {
			$value = self::$stacks[ $id ];
		}

		return $value;

	}

	/**
	 * Register a new template stack location.
	 *
	 * @return object|bool
	 * @since 1.3
	 */
	public static function register( $id, array $stack ) {

		if ( ! $id || self::is_exists( $id ) ) {
			return false;
		}

		$stack = (object) array_merge( array(
			'priority'	=> 10,
			'path'		=> '',
			'uri'		=> '',
		), $stack );

		$stack->id = $id; // Store the ID.

		self::$stacks[ $id ] = $stack;

		usort( self::$stacks, function( $a, $b ) {

			$p1 = (int) $a->priority;
			$p2 = (int) $b->priority;

			if ( $p1 === $p2 ) {
				return 0;
			}

			return ( $p1 > $p2 ) ? +1 : -1;

		} );

		return $stack;

	}

	/**
	 * Deregister a template stack.
	 *
	 * @return bool
	 * @since 1.3
	 */
	public static function deregister( $id ) {

		if ( ! $id || ! self::is_exists( $id ) ) {
			return false;
		}

		unset( self::$stacks[ $id ] );
		return true;

	}

	/**
	 * Check if a template stack exists.
	 *
	 * @param string $id The ID of the stack to check.
	 * @return bool
	 * @since 1.3
	 */
	public static function is_exists( $id ) {
		return isset( self::$stacks[ $id ] );
	}

	/**
	 * A dummy constructor.
	 *
	 * @return void
	 * @since 1.3
	 */
	private function __construct() {}

}