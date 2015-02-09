<?php
namespace Nmwdhj\Elements;

use Nmwdhj\Exception;

/**
 * The Fieldset element class
 *
 * @since 1.3
 */
class Fieldset extends Element {

	/**
	 * Fieldset elements
	 *
	 * @var Nmwdhj\Elements\Element[]
	 * @since 1.3
	 */
	protected $elements;


	/*** Magic Methods ********************************************************/

	/**
	 * The Fieldset element constructor
	 *
	 * @since 1.3
	 */
	public function __construct( $config = NULL ) {

		// Initialize the elements priority array.
		$this->elements = new \Nmwdhj\PriorityArray();

		// Call the parent class constructor.
		parent::__construct( $config );

	}


	/*** Methods **************************************************************/

	/**
	 * Configure the element
	 *
	 * @return Nmwdhj\Elements\Fieldset
	 * @since 1.3
	 */
	public function configure( $args ) {

		if ( is_array( $args ) && isset( $args['elements'] ) ) {

			if ( is_array( $args['elements'] ) ) {

				foreach( $args['elements'] as $key => $element ) {
					$this->add( $element, array( 'key' => $key ) );
				}

			}

		}

		parent::configure( $args );

	}

	// Elements List

	/**
	 * Add an element
	 *
	 * @return Nmwdhj\Elements\Fieldset
	 * @throws Nmwdhj\Exception
	 * @since 1.3
	 */
	public function add( $element, array $args = array() ) {

		if ( ! $element instanceof Element ) {
			$element = \Nmwdhj\create_element( $element );
		}

		$args = array_merge( array(
			'key'       => $element->get_name(),
			'priority'  => 10,
		), (array) $args );

		if ( empty( $args['key'] ) ) {
			throw new Exception( 'Cannot add nameless element to form' );
		}

		if ( $this->has( $args['key'] ) ) {
			throw new Exception( 'An element with the same name is found' );
		}

		$this->elements->offsetSet( $args['key'], $element, $args['priority'] );
		return $this;

	}

	/**
	 * Set/change the priority of an element
	 *
	 * @return Nmwdhj\Elements\Fieldset
	 * @throws Nmwdhj\Exception
	 * @since 1.3
	 */
	public function set_priority( $key, $priority ) {

		if ( ! $this->has( $key ) ) {
			throw new Exception( 'No element with the given name is found' );
		}

		$this->elements->offsetSet( $key, $this->elements[ $key ], $priority );
		return $this;

	}

	/**
	 * Remove a named element
	 *
	 * @return Nmwdhj\Elements\Fieldset
	 * @throws Nmwdhj\Exception
	 * @since 1.3
	 */
	public function remove( $key ) {

		if ( ! $this->has( $key ) ) {
			throw new Exception( 'No element with the given name is found' );
		}

		unset( $this->elements[ $key ] );
		return $this;

	}

	/**
	 * Retrieve a named element
	 *
	 * @return Nmwdhj\Elements\Element
	 * @throws Nmwdhj\Exception
	 * @since 1.3
	 */
	public function get( $key ) {

		if ( ! $this->has( $key ) ) {
			throw new Exception( 'No element with the given name is found' );
		}

		return $this->elements[ $key ];

	}

	/**
	 * Does the Fieldset have an element by the given name?
	 *
	 * @return bool
	 * @since 1.3
	 */
	public function has( $key ) {
		return isset( $this->elements[ $key ] );
	}

	/**
	 * Retrieve all attached elements
	 *
	 * @return Nmwdhj\Elements\Element[]
	 * @since 1.3
	 */
	public function get_elements() {
		return $this->elements;
	}

	// Output

	/**
	 * Get the element output
	 *
	 * @return string
	 * @since 1.3
	 */
	public function get_output() {
		$view = new \Nmwdhj\Views\Fieldset();
		return $view( $this );
	}

}