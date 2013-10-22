<?php
namespace Nmwdhj\Views;
use Nmwdhj\Elements\Element;

/**
 * The View abstract class.
 *
 * @since 1.0
 */
abstract class View {

	/*** Abstract Methods *****************************************************/

	/**
	 * Check the element.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public function check( Element $element ){
		return true;
	} // end check()

	/**
	 * Prepare the element.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function prepare( Element $element ){
	} // end prepare()

	/**
	 * Render the element view, and return the output.
	 *
	 * @since 1.0
	 * @return string
	 */
	abstract public function render( Element $element );


	/*** Magic Methods ********************************************************/

	/**
	 * Invoke helper as functor.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function __invoke( Element $element ){

		if ( $this->check( $element ) ) {

			$this->prepare( $element );

			return $this->render( $element );

		} // end if

	} // end __invoke()

} // end Class View