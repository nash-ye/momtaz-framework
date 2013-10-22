<?php
namespace Nmwdhj\Elements;

/**
 * The Input element class.
 *
 * @since 1.0
 */
class Input extends Base {

	/*** Properties ***********************************************************/

	/**
	 * Default element key.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $key = 'input';


	/*** Magic Methods ********************************************************/

	/**
	 * The Input element constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $key = '', array $properties = null ) {

		if ( ! $this->has_attr( 'type' ) ) {

			switch( strtolower( $key ) ) {

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

			} // end switch

		} // end if

		parent::__construct( $key, $properties );

	} // end __construct()


	/*** Methods **************************************************************/

	/**
	 * Set an attribute value.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Input
	 */
	public function set_attr( $key, $value ) {

		if ( strcasecmp( $key, 'type' ) === 0 ) {

			if ( strcasecmp( $this->get_key(), 'input' ) !== 0 ) {

				if ( $this->has_attr( 'type' ) )
					return $this;

			} // end if

		} // end if

		return parent::set_attr( $key, $value );

	} // end set_attr()

} // end Class Input