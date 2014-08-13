<?php
namespace Nmwdhj\Elements;

/**
 * The Input element class.
 *
 * @since 1.0
 */
class Input extends Element {

	// Configurations

	/**
	 * Configure the element
	 *
	 * @return Nmwdhj\Elements\Input
	 * @since 1.3
	 */
	public function configure( $args ) {

		if ( is_string( $args ) ) {
			$args = array( 'type' => $args );
		}

		if ( ! empty( $args['type'] ) ) {

			switch( $args['type'] ) {

				case 'input_url';
					$this->set_attr( 'type', 'url' );
					break;

				case 'input_file';
					$this->set_attr( 'type', 'file' );
					break;

				case 'input_date';
					$this->set_attr( 'type', 'date' );
					break;

				case 'input_time';
					$this->set_attr( 'type', 'time' );
					break;

				case 'input_week';
					$this->set_attr( 'type', 'week' );
					break;

				case 'input_color';
					$this->set_attr( 'type', 'color' );
					break;

				case 'input_radio';
					$this->set_attr( 'type', 'radio' );
					break;

				case 'input_month';
					$this->set_attr( 'type', 'month' );
					break;

				case 'input_email';
					$this->set_attr( 'type', 'email' );
					break;

				case 'input_range';
					$this->set_attr( 'type', 'range' );
					break;

				case 'input_image';
					$this->set_attr( 'type', 'image' );
					break;

				case 'input_submit';
					$this->set_attr( 'type', 'submit' );
					break;

				case 'input_search';
					$this->set_attr( 'type', 'search' );
					break;

				case 'input_hidden';
					$this->set_attr( 'type', 'hidden' );
					break;

				case 'input_number';
					$this->set_attr( 'type', 'number' );
					break;

				case 'input_password';
					$this->set_attr( 'type', 'password' );
					break;

				case 'input_text';
					$this->set_attr( 'type', 'text' );
					break;

			}

		}

		parent::configure( $args );

	}

	// Value

	/**
	 * Get the element value.
	 *
	 * @return mixed
	 * @since 1.0
	 */
	public function get_value() {

		if ( ! $this->has_attr( 'value' ) ) {

			$callback = $this->get_option( 'value_cb', array() );

			if ( is_array( $callback ) && ! empty( $callback ) ) {
				$this->set_value( call_user_func_array( $callback['name'], $callback['args'] ) );
			}

		}

		return $this->get_attr( 'value' );

	}

	/**
	 * Set the element value.
	 *
	 * @return Nmwdhj\Elements\Input
	 * @since 1.3
	 */
	public function set_value( $value ) {
		$this->set_attr( 'value', $value );
		return $this;
	}

	/**
	 * Get the element output.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function get_output() {
		$view = new \Nmwdhj\Views\Input();
		return $view( $this );
	}

}