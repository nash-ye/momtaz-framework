<?php
namespace Nmwdhj\Elements;

/**
 * The Form element class
 *
 * @since 1.3
 */
class Form extends Fieldset {

	/*** Magic Methods ********************************************************/

	/**
	 * The Form element constructor
	 *
	 * @since 1.3
	 */
	public function __construct( $config = NULL ) {

		// Call the parent class constructor.
		parent::__construct( $config );

	}


	/*** Methods **************************************************************/

	// Output

	/**
	 * Get the element output
	 *
	 * @return string
	 * @since 1.3
	 */
	public function get_output() {
		$view = new \Nmwdhj\Views\Form();
		return $view( $this );
	}

}