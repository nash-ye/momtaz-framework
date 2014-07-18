<?php
namespace Nmwdhj;

/*** Management Classes *******************************************************/

/**
 * The Nmwdhj Manager class.
 *
 * @since 1.3
 */
final class Manager {

	/*** Properties ***********************************************************/

	/**
	 * Elements list.
	 *
	 * @access private
	 * @var array
	 * @since 1.3
	 */
	private static $elements = array();


	/** Methods ***************************************************************/

	// Getters

	/**
	 * Get an element by name.
	 *
	 * @return object|NULL
	 * @since 1.3
	 */
	public static function get_element( $key ) {

		if ( isset( self::$elements[ $key ] ) ) {
			return self::$elements[ $key ];

		} else {

			foreach ( self::$elements as $element ) {

				if ( in_array( $key, (array) $element->aliases, true ) ) {
					return $element;
				}

			}

		}

	}

	/**
	 * Retrieve a list of registered elements.
	 *
	 * @return array
	 * @since 1.3
	 */
	public static function get_elements( array $args = NULL, $operator = 'AND' ) {
		return wp_list_filter( self::$elements, $args, $operator );
	}

	// Register/Deregister

	/**
	 * Registers a new element.
	 *
	 * @return object|bool
	 * @since 1.3
	 */
	public static function register_element( $name, array $args ) {

		if ( ! $name || isset( self::$elements[ $name ] ) ) {
			return false;
		}

		$args = (object) array_merge( array(
			'aliases'	=> array(),
		), $args );

		$args->name = $name; // Set the name.

		self::$elements[ $name ] =  $args;
		return $args;

	}

	/**
	 * Deregister an element
	 *
	 * @return bool
	 * @since 1.3
	 */
	public static function deregister_element( $name ) {

		if ( ! $name || ! isset( self::$elements[ $name ] ) ) {
			return false;
		}

		unset( self::$elements[ $name ] );
		return true;

	}

	/**
	 * Register the defaults.
	 *
	 * @return void
	 * @since 1.3
	 */
	public static function register_defaults() {

		self::register_element( 'Nmwdhj\Elements\Form', array(
			'aliases'	=> array(
				'form'
			),
		) );

		self::register_element( 'Nmwdhj\Elements\Select', array(
			'aliases'	=> array(
				'select'
			),
		) );

		self::register_element( 'Nmwdhj\Elements\Textarea', array(
			'aliases'	=> array(
				'textarea'
			),
		) );

		self::register_element( 'Nmwdhj\Elements\WP_Editor', array(
			'aliases'	=> array(
				'wp_editor'
			),
		) );

		self::register_element( 'Nmwdhj\Elements\Checkbox', array(
			'aliases'	=> array(
				'checkbox', 'input_checkbox'
			),
		) );

		self::register_element( 'Nmwdhj\Elements\Checkboxes', array(
			'aliases'	=> array(
				'checkboxes', 'multi_checkbox'
			),
		) );

		self::register_element( 'Nmwdhj\Elements\Button', array(
			'aliases'	=> array(
				'button', 'button_submit', 'button_reset'
			),
		) );

		self::register_element( 'Nmwdhj\Elements\Input', array(
			'aliases'	=> array(
				'input_text', 'input_url', 'input_email', 'input_range', 'input_search', 'input_date', 'input_file',
				'input_hidden', 'input_number', 'input_password', 'input_color', 'input_submit', 'input_week',
				'input_time', 'input_radio', 'input_month', 'input_image'
			),
		) );

	}

}

/*** Helper Classes ***********************************************************/

/**
 * Event manager
 *
 * @since 1.3
 */
class EventManager {

	/*** Properties ***********************************************************/

	/**
	 * Registered events list.
	 *
	 * @var array
	 * @since 1.3
	 */
	protected $events = array();


	/** Methods ***************************************************************/

	/**
	 * Trigger all listeners for a given event
	 *
	 * @return void
	 * @since 1.3
	 */
	public function trigger( $event, $arg = '' ) {

		if ( is_array( $event ) ) {

			foreach( $event as $name ) {
				$this->trigger( $name );
			}

		} elseif ( isset( $this->events[ $event ] ) ) {

			$args = array();

			for ( $i = 1; $i < func_num_args(); $i++ ) {
				$args[] = func_get_arg( $i );
			}

			foreach( $this->events[ $event ] as $listener ) {
				call_user_func_array( $listener, $args );
			}

		}

	}

	/**
	 * Attach a listener to an event
	 *
	 * @return void
	 * @since 1.3
	 */
	public function attach( $event, $listener, $priority = 10 ) {

		if ( NULL === $listener ) {
			throw new Exception( 'The provided listener isn\'t a valid callback.' );
		}

		if ( is_array( $event ) ) {

			foreach( $event as $name ) {
				$this->attach( $name, $listener, $priority );
			}

		} else {

			if ( ! isset( $this->events[ $event ] ) ) {
				$this->events[ $event ] = new PriorityArray();
			}

			$this->events[ $event ]->offsetSet( $this->build_listener_id( $listener ), $listener, $priority );

		}

	}

	/**
	 * Unsubscribe a listener from an event
	 *
	 * @return void
	 * @since 1.3
	 */
	public function detach( $event, $listener ) {

		if ( NULL === $listener ) {
			throw new Exception( 'The provided listener isn\'t a valid callback.' );
		}

		if ( is_array( $event ) ) {

			foreach( $event as $name ) {
				$this->detach( $name, $listener );
			}

		} elseif ( isset( $this->events[ $event ] ) ) {

			$this->events[ $event ]->offsetUnset( $this->build_listener_id( $listener ) );

		}

	}

	/**
	 * Build Unique ID for listeners callbacks.
	 *
	 * @access private
	 * @return string
	 * @since 1.3
	 */
	protected function build_listener_id( $listener ) {

		if ( is_string( $listener ) ) {
			return $listener;
		}

		return spl_object_hash( (object) $listener );

	}

	/**
	 * Clear all listeners for a given event
	 *
	 * @return void
	 * @since 1.3
	 */
	public function clear_listeners( $event ) {
		unset( $this->events[ $event ] );
	}

	/**
	 * Gel all registered events.
	 *
	 * @return array
	 * @since 1.3
	 */
	public function get_events() {
		return array_keys( $this->events );
	}

}

/**
 * Priority Array
 *
 * @since 1.3
 */
class PriorityArray implements \IteratorAggregate, \ArrayAccess, \Serializable, \Countable {

	/*** Properties ***********************************************************/

	/**
	 * Elements List
	 *
	 * @var array
	 * @since 1.3
	 */
	protected $e6s = array();

	/**
	 * Priorities List.
	 *
	 * @var array
	 * @since 1.3
	 */
	protected $p8s = array();

	/**
	 * Is Sorted? (Flag)
	 *
	 * @var bool
	 * @since 1.3
	 */
	private $is_sorted = true;


	/*** Methods **************************************************************/

	/**
	 * @return void
	 * @since 1.3
	 */
	public function offsetSet( $index, $value, $priority = 10 ) {

		$this->p8s[ $index ] = (int) $priority;
		$this->e6s[ $index ] = $value;
		$this->is_sorted = false;

	}

	/**
	 * @return bool
	 * @since 1.3
	 */
	public function offsetExists( $index ) {
		return isset( $this->e6s[ $index ] );
	}

	/**
	 * @return void
	 * @since 1.3
	 */
	public function offsetUnset( $index ) {

		unset( $this->p8s[ $index ] );
		unset( $this->e6s[ $index ] );

	}

	/**
	 * @return mixed
	 * @since 1.3
	 */
	public function offsetGet( $index ) {

		if ( $this->offsetExists( $index ) ) {
			return $this->e6s[ $index ];
		}

	}

	/**
	 * @return ArrayIterator
	 * @since 1.3
	 */
	public function getIterator() {

		$this->maybeSort(); // Sort the array.
		return new \ArrayIterator( $this->e6s );

	}

	/**
	 * @return void
	 * @since 1.3
	 */
	public function unserialize( $data ) {
		$this->e6s = unserialize( $data );
	}

	/**
	 * @return string
	 * @since 1.3
	 */
	public function serialize() {
		return serialize( $this->e6s );
	}

	/**
	 * @return bool
	 * @since 1.3
	 */
	public function maybeSort() {

		if ( ! $this->is_sorted ) {

			$p8s = (array) $this->p8s;

			$this->is_sorted = uksort( $this->e6s,

				function( $a, $b ) use ( &$p8s ) {

					$p1 = (int) $p8s[ $a ];
					$p2 = (int) $p8s[ $b ];

					return ( $p1 <= $p2 ) ? +1 : -1;

				}

			);

		}

		return $this->is_sorted;

	}

	/**
	 * @return int
	 * @since 1.3
	 */
	public function count() {
		return count( $this->e6s );
	}

}

/*** Exceptions Classes *******************************************************/

/**
 * The Nmwdhj exception class.
 *
 * @since 1.2
 */
class Exception extends \Exception {}