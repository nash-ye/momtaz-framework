<?php

/*
 * Template URI Functions.
 -----------------------------------------------------------------------------*/

/**
 * Retrieve the URI of a template file in the current theme.
 *
 * Searches in the child theme directory so themes which inherit from
 * a parent theme can just overload one file.
 *
 * @return string
 * @since 1.0
 */
function momtaz_theme_uri( $name = '' ){

	$name = ltrim( $name, '/' );

	// If the file exists in the stylesheet (child theme) directory.
	if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $name ) ) {
		$uri = momtaz_child_theme_uri( $name );

	// No matter the file exists or not in the template (parent theme) directory.
	} else {
		$uri = momtaz_parent_theme_uri( $name );

	}

	$uri = apply_filters( 'momtaz_theme_uri', $uri, $name );
	return $uri;

}

/**
 * Retrieve the URI of a template file in the child theme.
 *
 * @return string
 * @since 1.0
 */
function momtaz_child_theme_uri( $name = '' ){

	$name = ltrim( $name, '/' );
	$uri = get_stylesheet_directory_uri();

	if ( ! empty( $name ) ) {
		$uri = trailingslashit( $uri ) . $name;
	}

	$uri = apply_filters( 'momtaz_child_theme_uri', $uri, $name );
	return $uri;

}

/**
 * Retrieve the URI of a template file in the parent theme.
 *
 * @return string
 * @since 1.0
 */
function momtaz_parent_theme_uri( $name = '' ){

	$name = ltrim( $name, '/' );
	$uri = get_template_directory_uri();

	if ( ! empty( $name ) ) {
		$uri = trailingslashit( $uri ) . $name;
	}

	$uri = apply_filters( 'momtaz_parent_theme_uri', $uri, $name );
	return $uri;

}

/**
 * Retrieve the URI of the highest priority template file that exists.
 *
 * Searches in the registered template stacks so that locations
 * which have a high priority checked first.
 *
 * @return string
 * @since 1.0
 */
function momtaz_locate_template_uri( $template_names ) {

	$located = '';

	foreach ( (array) $template_names as $template_name ) {

		if ( empty( $template_name ) ) {
			continue;
		}

		// Remove the slash from the beginning of the string.
		$template_name = ltrim( $template_name, '/' );

		// Loop through template stack
		foreach ( Momtaz_Stacks::get() as $template_stack ) {

			if ( empty( $template_stack->path ) ) {
				continue;
			}

			if ( file_exists( trailingslashit( $template_stack->path ) . $template_name ) ){
				$located = trailingslashit( $template_stack->uri ) . $template_name;
				break;
			}

		}

		if ( ! empty( $located ) ) {
			break;
		}

	}

	return $located;

}

/*
 * Template Parts Functions.
 -----------------------------------------------------------------------------*/

/**
 * A replacement for the WordPress `get_header()` function.
 *
 * @see get_header()
 * @return void
 * @since 1.3
 */
function momtaz_template_header( $name = '', $_args = NULL ) {
	do_action( 'get_header', $name ); // Core WordPress hook
	momtaz_template_part( 'header', $name, TRUE, $_args );
}

/**
 * A replacement for the WordPress `get_sidebar()` function.
 *
 * @see get_sidebar()
 * @return void
 * @since 1.3
 */
function momtaz_template_sidebar( $name = '', $_args = NULL ) {
	do_action( 'get_sidebar', $name ); // Core WordPress hook
	momtaz_template_part( 'sidebar', $name, TRUE, $_args );
}

/**
 * A replacement for the WordPress `get_footer()` function.
 *
 * @see get_footer()
 * @return void
 * @since 1.3
 */
function momtaz_template_footer( $name = '', $_args = NULL ) {
	do_action( 'get_footer', $name ); // Core WordPress hook
	momtaz_template_part( 'footer', $name, TRUE, $_args );
}

/**
 * Looks for a template based on the momtaz_get_context() function. The function looks for
 * templates based on the context of the current page being viewed by the user.
 *
 * @since 1.0
 */
function momtaz_context_template( $name, $slug = '', $load = true, $_args = null ) {

	$context = array();

	foreach ( array_reverse( momtaz_get_context() ) as $value ) {

		if ( ! empty( $slug ) ) {
			$context[] = "{$slug}-{$value}";
		} else {
			$context[] = "{$value}";
		}

	}

	if ( ! empty( $slug ) ) {
		$context[] = $slug;
	}

	return momtaz_template_part( $name, $context, $load, $_args );

}

/**
 * Looks for a template based on the momtaz_get_post_context() function. The function looks for
 * templates based on the context of the post data.
 *
 * @since 1.0
 */
function momtaz_post_context_template( $name, $slug = '', $post = 0, $load = true, $_args = null ) {

	$context = array();

	if ( empty( $post ) ) {
		$post = get_the_ID();
	}

	foreach ( array_reverse( momtaz_get_post_context( $post ) ) as $value ) {

		if ( ! empty( $slug ) ) {
			$context[] = "{$slug}-{$value}";
		} else {
			$context[] = "{$value}";
		}

	}

	if ( ! empty( $slug ) ) {
		$context[] = $slug;
	}

	return momtaz_template_part( $name, $context, $load, $_args );

}

/**
 * A more powerfull version of get_template_part() funcation.
 *
 * @see get_template_part()
 * @since 1.0
 */
function momtaz_template_part( $name, $context = '', $load = true, $_args = NULL ) {

	$template_names = array();

	if ( empty( $name ) ) {
		return false;
	}

	$is_dir = is_dir( momtaz_locate_template( $name, false ) );

	do_action( 'momtaz_template_part', $name, $context, $load );

	foreach ( (array) $context as $slug ) {

		$slug = untrailingslashit( $slug );

		if ( $is_dir ) {
			$template_names[] = "{$name}/{$slug}.php";
		} else {
			$template_names[] = "{$name}-{$slug}.php";
		}

	}

	$template_names[] = ($is_dir) ? "{$name}/default.php" : "{$name}.php";

	// Locate the highest priority template file.
	return momtaz_locate_template( $template_names, $load, false, $_args );

}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the registered template stacks so that locations
 * which have a high priority checked first.
 *
 * @since 1.1
 */
function momtaz_locate_template( $template_names, $load = false, $load_once = true, $_args = null ) {

	// No file found yet
	$located = '';

	// Try to find a template file
	foreach ( (array) $template_names as $template_name ) {

		// Continue if template is empty
		if ( empty( $template_name ) ) {
			continue;
		}

		// Trim off any slashes from the template name
		$template_name  = ltrim( $template_name, '/' );

		// Loop through template stack
		foreach ( Momtaz_Stacks::get() as $template_stack ) {

			if ( empty( $template_stack->path ) ) {
				continue;
			}

			if ( file_exists( trailingslashit( $template_stack->path ) . $template_name ) ) {
				$located = trailingslashit( $template_stack->path ) . $template_name;
				break;
			}

		}

		if ( ! empty( $located ) ) {
			break;
		}

	}

	// Maybe load the template if one was located
	if ( $load && ! empty( $located ) ) {
		momtaz_load_template( $located, $load_once, $_args );
	}

	return $located;

}

/**
 * Require the template file with optional arguments.
 *
 * This function doesn't setup the WordPress environment variables,
 * simply you must use the 'global' operator to use the needed
 * variables in your templates.
 *
 * @since 1.1
 */
function momtaz_load_template( $_template, $_load_once = true, $_args = null ) {

	if ( empty( $_template ) ) {
		return;
	}

	$_load_once = (bool) $_load_once;

	( $_load_once ) ? require_once( $_template ) : require( $_template );

}

/*
 * Template Zones Functions.
 -----------------------------------------------------------------------------*/

/**
 * The Momtaz template zones manager class.
 *
 * @since 1.3
 */
final class Momtaz_Zones {

	/*** Properties ***********************************************************/

	/**
	 * The template zones list.
	 *
	 * @access private
	 * @var array
	 * @since 1.3
	 */
	private static $zones = array();


	/*** Methods **************************************************************/

	/**
	 * Get the template zones list.
	 *
	 * @return array
	 * @since 1.3
	 */
	public static function get( $id = '' ) {

		$value = array();

		if ( empty( $id ) ) {
			$value = self::$zones;

		} elseif ( self::is_exists( $id ) ) {
			$value = self::$zones[ $id ];
		}

		return $value;

	}

	/**
	 * @return void
	 * @since 1.3
	 */
	public static function add_callback( $id, $callback, $priority = 10 ) {

		if ( ! $id || ! self::is_exists( $id ) ) {
			return;
		}

		add_action( "momtaz_zone-{$id}", $callback, $priority );

	}

	/**
	 * @return void
	 * @since 1.3
	 */
	public static function remove_callback( $id, $callback, $priority = 10 ) {

		if ( ! $id || ! self::is_exists( $id ) ) {
			return;
		}

		remove_action( "momtaz_zone-{$id}", $callback, $priority );

	}

	/**
	 * Call the functions hooked on the template-zone action hook.
	 *
	 * @return void
	 * @since 1.3
	 */
	public static function call( $id, array $args = array() ) {

		if ( ! $id || ! self::is_exists( $id ) ) {
			return;
		}

		do_action_ref_array( "momtaz_zone-{$id}", $args );

	}

	/**
	 * Register a new template zone.
	 *
	 * @return object|bool
	 * @since 1.3
	 */
	public static function register( $id, array $zone ) {

		if ( ! $id || self::is_exists( $id ) ) {
			return false;
		}

		$zone = (object) array_merge( array(
			'title'			=> '',
			'description'	=> '',
		), $zone );

		$zone->id = $id; // Store the ID.

		self::$zones[ $id ] = $zone;
		return $zone;

	}

	/**
	 * Deregister a template zone.
	 *
	 * @return bool
	 * @since 1.3
	 */
	public static function deregister( $id ) {

		if ( ! $id || ! self::is_exists( $id ) ) {
			return false;
		}

		unset( self::$zones[ $id ] );
		return true;

	}

	/**
	 * Check if a template zone exists.
	 *
	 * @param string $id The ID of the zone to check.
	 * @return bool
	 * @since 1.3
	 */
	public static function is_exists( $id ) {
		return isset( self::$zones[ $id ] );
	}

	/**
	 * A dummy constructor.
	 *
	 * @return void
	 * @since 1.3
	 */
	private function __construct() {}

}

/**
 * Registers the framework's default template zones.
 *
 * @return void
 * @since 1.3
 */
function momtaz_register_core_zones() {

	$zones = array(

		/*** Structure Template Zones *****************************************/

		'wrapper:before'			=> __( 'Before Wrapper', 'momtaz' ),
		'wrapper:after'				=> __( 'After Wrapper', 'momtaz' ),

		'container:before'			=> __( 'Before Container', 'momtaz' ),
		'container:after'			=> __( 'After Container', 'momtaz' ),

		'content'					=> __( 'Inside Content', 'momtaz' ),

		/*** Header Template Zones ********************************************/

		'head'						=> __( 'Inside Head', 'momtaz' ),
		'header:before'				=> __( 'Before Header', 'momtaz' ),
		'header:after'				=> __( 'After Header', 'momtaz' ),

		/*** Loop Template Zones **********************************************/

		'loop:before'				=> __( 'Before Loop', 'momtaz' ),
		'loop:after'				=> __( 'After Loop', 'momtaz' ),

		'entry:before'				=> __( 'Before Entry', 'momtaz' ),
		'entry:after'				=> __( 'After Entry', 'momtaz' ),

		'entry_content:before'		=> __( 'Before Entry Content', 'momtaz' ),
		'entry_content:after'		=> __( 'After Entry Content', 'momtaz' ),

		/*** Comments Template Zones ******************************************/

		'comments:before'			=> __( 'Before Comments', 'momtaz' ),
		'comments:after'			=> __( 'After Comments', 'momtaz' ),

		'comment:before'			=> __( 'Before Comment', 'momtaz' ),
		'comment:after'				=> __( 'After Comment', 'momtaz' ),

		'comments_list:before'		=> __( 'Before Comments List', 'momtaz' ),
		'comments_list:after'		=> __( 'After Comments List', 'momtaz' ),

		'pings_list:before'			=> __( 'Before Pings List', 'momtaz' ),
		'pings_list:after'			=> __( 'After Pings List', 'momtaz' ),

		/*** Menu Template Zones **********************************************/

		'primary_menu:before'		=> __( 'Before Primary Menu', 'momtaz' ),
		'primary_menu:after'		=> __( 'After Primary Menu', 'momtaz' ),

		/*** Sidebar Template Zones *******************************************/

		'primary_sidebar:before'	=> __( 'Before Primary Sidebar', 'momtaz' ),
		'primary_sidebar:after'		=> __( 'After Primary Sidebar', 'momtaz' ),

		/*** Footer Template Zones ********************************************/

		'footer:before'				=> __( 'Before Footer', 'momtaz' ),
		'footer:after'				=> __( 'After Footer', 'momtaz' ),

		/*** Theme Settings Page Zones ****************************************/

		'settings_page:before'		=> __( 'Before Settings Page', 'momtaz' ),
		'settings_page:after'		=> __( 'After Settings Page', 'momtaz' ),

	);

	foreach ( $zones as $id => $args ) {

		if ( is_string( $args ) ) {
			$args = array( 'title' => $args );
		}

		Momtaz_Zones::register( $id, $args );

	}

}

/*
 * Template Stacks Functions.
 -----------------------------------------------------------------------------*/

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
	 * @return array|object
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

		uasort( self::$stacks, function( $a, $b ) {

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

/**
 * Registers the framework's default template stacks.
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
