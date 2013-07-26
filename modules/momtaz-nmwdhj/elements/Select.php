<?php
/**
 * The Select element class.
 *
 * @since 1.0
 */
class Momtaz_Nmwdhj_Element_Select extends Momtaz_Nmwdhj_SimpleElement {

    /*** Properties ***********************************************************/

    /**
     * Default element key.
     *
     * @since 1.0
     * @var string
     */
    protected $key = 'select';

    /**
     * Default element view key.
     *
     * @since 1.0
     * @var string
     */
    protected $view_key = 'select';

    /**
     * Default value options.
     *
     * @since 1.0
     * @var array
     */
    protected $value_options = array();


    /*** Methods **************************************************************/

    // Value Options

    /**
     * Get the values and labels for the value options.
     *
     * @since 1.0
     * @return array
     */
    public function get_value_options() {
        return $this->value_options;
    } // end get_value_options()

    /**
     * Ser the values and labels for the value options.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Element_Select
     */
    public function set_value_options( $options, $append = false ) {

        if ( is_array( $options ) ) {

            if ( $append ) {

                $this->value_options += $options;

            } else {

                $this->value_options = $options;

            } // end if

        } // end if

        return $this;

    } // end set_value_options()

    /**
     * Remove all/specified value options.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Element_Select
     */
    public function remove_value_options( $options = '' ) {

        if ( is_array( $options ) && ! empty( $options ) ) {

            foreach( $options as $option ) {
                $this->remove_value_option( $option );
            } // end foreach

        } else {

            $this->value_options = array();

        } // end if

        return $this;

    } // end remove_value_options()

    /**
     * Remove a specified value option.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Element_Select
     */
    public function remove_value_option( $option ) {
        unset( $this->value_options[$option] );
        return $this;
    } // end remove_value_option()

} // end Class Momtaz_Nmwdhj_Element_Select