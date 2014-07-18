<?php
namespace Nmwdhj\Elements;

/**
 * The Button element class.
 *
 * @since 1.0
 */
class Button extends Element {

	/*** Properties ***********************************************************/

	/**
	 * Button content.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $content;


	/*** Magic Methods ********************************************************/

	/**
	 * The Button element constructor.
	 *
	 * @since 1.3
	 */
	public function __construct( $config = NULL ) {

		parent::__construct( $config );

		// Set the default attributes.
		$this->set_atts( array(
			'type' => 'button',
		), false );

	}


	/*** Methods **************************************************************/

	/**
	 * Configure the element
	 *
	 * @return Nmwdhj\Elements\Button
	 * @since 1.3
	 */
	public function configure( $args ) {

		if ( is_string( $args ) ) {
			$args = array( 'type' => $args );
		}

		if ( ! empty( $args['type'] ) ) {

			switch( $args['type'] ) {

				case 'button_submit';
					$this->set_attr( 'type', 'submit' );
					break;

				case 'button_reset';
					$this->set_attr( 'type', 'reset' );
					break;

			}

		}

		parent::configure( $args );

	}

	/**
	 * Set the button content.
	 *
	 * @return Nmwdhj\Elements\Button
	 * @since 1.0
	 */
	public function set_content( $content ) {
		$this->content = $content;
		return $this;
	}

	/**
	 * Get the button content.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_content() {
		return $this->content;
	}

	/**
	 * Get the element output.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function get_output() {
		$view = new \Nmwdhj\Views\Button();
		return $view( $this );
	}

}