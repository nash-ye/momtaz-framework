<?php
/**
 * The Button element class.
 *
 * @since 1.0
 */
class Momtaz_Nmwdhj_Element_Button extends Momtaz_Nmwdhj_SimpleElement {

    /**
     * Button content.
     *
     * @since 1.0
     * @var string
     */
    protected $content;

    /**
     * Default element key.
     *
     * @since 1.0
     * @var string
     */
    protected $key = 'button';

    /**
     * Default element view key.
     *
     * @since 1.0
     * @var string
     */
    protected $view_key = 'button';


    /*** Magic Methods ********************************************************/

    /**
     * The Input element constructor.
     *
     * @since 1.0
     */
    public function __construct( $key, array $properties = null ) {

        if ( ! $this->has_attr( 'type' ) ) {

            switch( strtolower( $key ) ) {

                case 'button';
                    $this->set_attr( 'type', 'button' );
                    break;

                case 'button_submit';
                    $this->set_attr( 'type', 'submit' );
                    break;

                case 'button_reset';
                    $this->set_attr( 'type', 'reset' );
                    break;

            } // end switch

        } // end if

        parent::__construct( $key, $properties );

    } // end __construct()


    /*** Methods **************************************************************/

    /**
     * Get the button content.
     *
     * @since 1.0
     * @return string
     */
    public function get_content() {
        return $this->content;
    } // end get_content()

    /**
     * Set the button content.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Element_Button
     */
    public function set_content( $content ) {
        $this->content = strval( $content );
        return $this;
    } // end set_content()

    /**
     * Set an attribute value.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Element_Button
     */
    public function set_attr( $key, $value ) {

        if ( strcasecmp( $key, 'type' ) === 0 ) {

            if ( strcasecmp( $this->get_key(), 'button' ) !== 0 ) {

                if ( $this->has_attr( 'type' ) )
                    return $this;

            } // end if

        } // end if

        return parent::set_attr( $key, $value );

    } // end set_attr()

} // end Class Momtaz_Nmwdhj_Element_Button