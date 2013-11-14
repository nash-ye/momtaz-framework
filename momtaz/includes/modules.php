<?php
/**
 * The Momtaz Modules manager class.
 *
 * @since 1.1
 */
final class Momtaz_Modules {

	/*** Properties ***********************************************************/

	/**
	 * The modules list.
	 *
	 * @var Momtaz_Module[]
	 * @since 1.1
	 */
	private static $modules = array();


	/*** Methods **************************************************************/

	// Getters

	/**
	 * Get a module object by slug.
	 *
	 * @return Momtaz_Module
	 * @since 1.1
	 */
	public static function get_by_slug( $slug ) {

		$module = null;

		if ( ( $slug = sanitize_key( $slug ) ) ) {

			$modules = self::get();

			if ( isset( $modules[ $slug ] ) ) {
				$module = $modules[ $slug ];
			}

		} // end if

		return $module;

	} // end get_by_slug()

	/**
	 * Retrieve a list of registered modules.
	 *
	 * @since 1.1
	 * @return array
	 */
	public static function get( array $args = null, $operator = 'AND' ) {

		if ( empty( $args ) ) {
			 return self::$modules;
		}

		$filtered = array();
		$count = count( $args );

		foreach ( self::$modules as $key => $obj ) {

				$matched = 0;

				foreach ( $args as $m_key => $m_value ) {

					if ( property_exists( $obj, $m_key ) ) {

						$obj_prop = $obj[$m_key];

						if ( is_array( $obj_prop ) && is_array( $m_value ) ) {

							$m_akey = wp_filter_object_list( array( $obj_prop ), $m_value, $operator );

							if ( ! empty( $m_akey ) ) {
								$matched++;
							}

						} elseif ( $obj_prop == $m_value ) {
							$matched++;
						} // end if

					} // end if

				} // end foreach

				$b = false;
				switch( strtoupper( $operator ) ) {

					default:
					case 'AND':
						$b = ( $matched == $count );
						break;

					case 'OR':
						$b = ( $matched > 0 );
						break;

					case 'NOT':
						$b = ( $matched == 0 );
						break;

				} // end Switch

				if ( $b ) {
					$filtered[$key] = $obj;
				}

		} // end foreach

		return $filtered;

	} // end get()

	// Register/Deregister

	/**
	 * Register a module.
	 *
	 * @return boolean
	 * @since 1.1
	 */
	public static function register( $module ) {

		if ( is_array( $module ) ) {
			$module = new Momtaz_Module( $module );
		}

		if ( $module instanceof Momtaz_Module ) {

			// Register the module.
			self::$modules[ $module->get_slug() ] = $module;

			// Return true.
			return true;

		} // end if

		// Return false.
		return false;

	} // end register()

	/**
	 * Remove a registered module.
	 *
	 * @return boolean
	 * @since 1.1
	 */
	public static function deregister( $slug ) {

		$slug = sanitize_key( $slug );

		if ( empty( $slug ) ) {
			return false;
		}

		unset( self::$modules[ $slug ] );
		return true;

	} // end deregister()

	// Loaders

	/**
	 * Load many modules at once.
	 *
	 * @return array
	 * @since 1.1
	 */
	public static function load_modules( array $modules, $force = false ) {

		$loaded_modules = array();

			foreach ( $modules as $module ) {

				if ( $module instanceof Momtaz_Module ) {

					if ( $module->load( $force ) ) {
						$loaded_modules[] = $module->get_path();
					}

				} // end if

			} // end foreach

		return $loaded_modules;

	} // end load_modules()

	/**
	 * Load a module.
	 *
	 * @return boolean
	 * @since 1.1
	 */
	public static function load_module( $module, $force = false ) {

		if ( is_string( $module ) ) {
			$module = self::get_by_slug( $module );
		} elseif ( is_array( $module ) ) {
			$module = self::get( $module, 'OR' );
		}

		if ( $module instanceof Momtaz_Module ) {
			return $module->load( $force );
		}

		return false;

	} // end load_module()

	// Helpers

	/**
	 * locate a file in the modules directory.
	 *
	 * @return array
	 * @since 1.1
	 */
	public static function locate_module( $path ) {

		if ( validate_file( $path ) != 0 ) {
			 return false;
		}

		return locate_template( path_join( 'modules', $path ) );

	} // end locate_module()

} // end Class Momtaz_Modules

/**
 * Momtaz Module.
 *
 * @since 1.1
 */
final class Momtaz_Module implements ArrayAccess {

	/*** Properties ***********************************************************/

	/**
	 * Module slug.
	 *
	 * @var string
	 * @since 1.1
	 */
	private $slug;

	/**
	 * Module name.
	 *
	 * @var string
	 * @since 1.1
	 */
	private $name;

	/**
	 * Module path.
	 *
	 * @var string
	 * @since 1.1
	 */
	private $path;

	/**
	 * Module statuses.
	 *
	 * @var array
	 * @since 1.1
	 */
	private $statuses;

	/**
	 * Module data list.
	 *
	 * @var array
	 * @since 1.1
	 */
	private $metadata = array();

	/**
	 * Module settings list.
	 *
	 * @var array
	 * @since 1.1
	 */
	private $settings = array();


	/*** Magic Methods ********************************************************/

	/**
	 * The Module PHP5 constructor.
	 *
	 * @since 1.1
	 */
	public function __construct( array $module ) {

		// Merge into the defaults.
		$module = wp_parse_args( $module, array(
			'name'	  => '',
			'slug'	  => '',
			'path'	  => '',
			'settings'  => array(),
			'metadata'  => array(),
		) );


		/*** Module Name ******************************************************/

		// Set the module name.
		$this->set_name( $module['name'] );


		/*** Module Slug ******************************************************/

		if ( empty( $module['slug'] ) ) {
			$module['slug'] = $this->get_name();
		}

		// Set the module slug.
		$this->set_slug( $module['slug'] );


		/*** Module Path ******************************************************/

		// Set the module path.
		$this->set_path( $module['path'] );


		/*** Module Settings **************************************************/

		// Set the module settings.
		$this->set_settings( $module['settings'] );


		/*** Module MetaData **************************************************/

		$properties = get_class_vars( __CLASS__ );
		$properties = array_keys( $properties );

		foreach( $module as $property => $value ) {

			if ( array_search( $property, $properties ) === false ) {
				 $module['metadata'][ $property ] = $value;
			}

		} // end foreach

		// Set the module meta-data.
		$this->set_metadata( $module['metadata'] );

	} // end __construct()


	/*** Methods **************************************************************/

	// Getters

	/**
	 * Get the module slug.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function get_slug() {
		return $this->slug;
	} // end get_slug()

	/**
	 * Get the module name.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function get_name() {
		return $this->name;
	} // end get_name()

	/**
	 * Get the module path.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function get_path() {
		return $this->path;
	} // end get_path()

	/**
	 * Get the module metadata.
	 *
	 * @return mixed
	 * @since 1.1
	 */
	public function get_metadata( $key = '' ) {

		if ( empty( $key ) ) {
			return $this->metadata;
		}

		if ( isset( $this->metadata[$key] ) ) {
			return $this->metadata[$key];
		}

	} // end get_metadata()

	/**
	 * Get the module settings.
	 *
	 * @return mixed
	 * @since 1.1
	 */
	public function get_settings( $key = '' ) {

		if ( empty( $key ) ) {
			return $this->settings;
		}

		if ( isset( $this->settings[$key] ) ) {
			return $this->settings[$key];
		}

	} // end get_settings()

	// Setters

	/**
	 * Set the module slug.
	 *
	 * @throws Momtaz_Module_Exception
	 * @return Momtaz_Module
	 * @since 1.1
	 */
	public function set_slug( $slug ) {

		if ( ! ( $slug = sanitize_key( $slug ) ) ) {

			throw new Momtaz_Module_Exception(
					__( 'Invalid module slug.', 'momtaz' ),
					'invalid-module-slug'
				);

		} // end if

		$this->slug = $slug;

		return $this;

	} // end set_slug()

	/**
	 * Set the module name.
	 *
	 * @return Momtaz_Module
	 * @since 1.1
	 */
	public function set_name( $name ) {
		$this->name = wp_kses_data( $name );
		return $this;
	} // end get_name()

	/**
	 * Get the module path.
	 *
	 * @throws Momtaz_Module_Exception
	 * @return Momtaz_Module
	 * @since 1.1
	 */
	public function set_path( $path ) {

		if ( ! Momtaz_Modules::locate_module( $path ) ) {

			throw new Momtaz_Module_Exception(
					__( 'Invalid module path.', 'momtaz' ),
					'invalid-module-path'
				);

		} // end if

		$this->path = $path;

		return $this;

	} // end set_path()

	/**
	 * Set the module metadata.
	 *
	 * @return Momtaz_Module
	 * @since 1.1
	 */
	public function set_metadata( array $data ) {
		$this->metadata = $data;
		return $this;
	} // end set_data()

	/**
	 * Set the module settings.
	 *
	 * @return Momtaz_Module
	 * @since 1.1
	 */
	public function set_settings( array $settings ) {
		$this->settings = $settings;
		return $this;
	} // end set_settings()

	// Checks

	/**
	 * Check if the module is loaded.
	 *
	 * @return boolean
	 * @since 1.1
	 */
	public function is_loaded() {

		if ( ! isset( $this->statuses['loaded'] ) ) {

			$callback = $this->get_settings( 'is_loaded_callback' );

			if ( is_callable( $callback ) ) {
				$this->statuses['loaded'] = (bool) call_user_func( $callback, $this );
			}

		} // end if

		return ( isset( $this->statuses['loaded'] ) && $this->statuses['loaded'] );

	} // end is_loaded()

	// Loaders

	/**
	 * Load the module.
	 *
	 * @return boolean
	 * @since 1.1
	 */
	public function load( $force = false ) {

		if ( ! $force && $this->get_settings( 'once' ) && $this->is_loaded() ) {
			 return true;
		}

		if ( ( $path = Momtaz_Modules::locate_module( $this->get_path() ) ) ) {

			// Load the module main file.
			momtaz_load_template( $path, false );

			// Set the module loader status.
			$this->statuses['loaded'] = true;

			return true;

		} // end if

		return false;

	} // end load()


	/*** ArrayAccess Implements ***********************************************/

	public function offsetGet( $offset ) {

		switch( strtolower( $offset ) ) {

			case 'slug':
				return $this->get_slug();

			case 'path':
				return $this->get_path();

			case 'name':
				return $this->get_name();

			case 'settings':
				return $this->get_settings();

			case 'metadata':
				return $this->get_metadata();

		} // end switch

	} // end offsetGet()

	public function offsetExists( $offset ) {

		$properties = array_keys( get_class_vars( __CLASS__ ) );

		return ( array_search( $offset, $properties ) !== false );

	} // emd offsetExists()

	public function offsetSet( $offset, $value ) {

		switch( strtolower( $offset ) ) {

			case 'slug':
				return $this->set_slug( $value );

			case 'name':
				return $this->set_name( $value );

			case 'path':
				return $this->set_path( $value );

			case 'settings':
				return $this->set_settings( $value );

			case 'metadata':
				return $this->set_metadata( $value );

		} // end switch

	} // end offsetSet()

	public function offsetUnset( $offset ) {
		return false;
	} // end offsetUnset()

} // end Class Momtaz_Module

/**
 * The Momtaz module exception class.
 *
 * @since 1.1
 */
class Momtaz_Module_Exception extends Exception {}