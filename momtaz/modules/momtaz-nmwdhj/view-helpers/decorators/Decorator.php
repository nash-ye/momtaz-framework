<?php
namespace Nmwdhj\Decorators;
use Nmwdhj\Elements\Element;
use Nmwdhj\Exception;

/**
 * The Decorator abstract class.
 *
 * @since 1.0
 */
abstract class Decorator implements Element {

	/*** Properties ***********************************************************/

	/**
	 * The element object.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Element
	 */
	protected $element;


	/*** Magic Methods ********************************************************/

	/**
	 * A magic method to redirect the methods calls to the element object.
	 *
	 * @throws Nmwdhj\Exception
	 * @since 1.0
	 */
	public function __call( $method, $args ) {

		if ( is_callable( array( $this->get_element(), $method ) ) )
			return call_user_func_array( array( $this->get_element(), $method ), $args );

		throw new Exception( 'Undefined method - ' . get_class( $this->get_element() ) . '->' . $method );

	} // end __call()

	/**
	 * The default Decorator constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( Element $element ) {
		$this->set_element( $element );
	} // end __construct()


	/*** Methods **************************************************************/

	/**
	 * Set the element.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Decorators\Decorator
	 */
	protected function set_element( Element $element ){
		$this->element = $element;
		return $this;
	} // end set_element()

	/**
	 * Get the original, undecorated element.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Element
	 */
	public function get_original_element(){

		$element = $this->get_element();

		while( $element instanceof Decorator )
			$element = $element->get_element();

		return $element;

	} // end get_original_element()

	/**
	 * Get the element.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Element
	 */
	public function get_element(){
		return $this->element;
	} // end get_element()

} // end Class Decorator