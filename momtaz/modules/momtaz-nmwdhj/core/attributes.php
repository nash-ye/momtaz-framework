<?php
/**
 * Get a Momtaz_Nmwdhj_Attributes class instance.
 *
 * @return Momtaz_Nmwdhj_Attributes
 * @since 1.0
 */
function momtaz_nmwdhj_atts( $atts ) {

    if ( $atts instanceof Momtaz_Nmwdhj_Attributes )
        return $atts;

    return new Momtaz_Nmwdhj_Attributes( $atts );

} // end momtaz_nmwdhj_atts()

/**
 * Get a Momtaz_Nmwdhj_Attribute class instance.
 *
 * @return Momtaz_Nmwdhj_Attribute
 * @since 1.1
 */
function momtaz_nmwdhj_attr( $key, $value ) {

    if ( $value instanceof Momtaz_Nmwdhj_Attribute ) {

        if ( strcasecmp( $value->get_key(), $key ) !== 0 )
            $value = momtaz_nmwdhj_attr( $key, $value->get_value() );

        return $value;

    } else {

        switch( strtolower( $key ) ) {

            case 'class':
                return new Momtaz_Nmwdhj_ClassAttribute( $key, $value );
                break;

            default:
                return new Momtaz_Nmwdhj_SimpleAttribute( $key, $value );
                break;

        } // end Switch

    } // end if

} // end momtaz_nmwdhj_attr()

/**
 * The attribute interface.
 *
 * @since 1.1
 */
interface Momtaz_Nmwdhj_Attribute {

    /**
     * Get the attribute key.
     *
     * @since 1.1
     * @return string
     */
    public function get_key();

    /**
     * Get the attribute value.
     *
     * @since 1.1
     * @return string
     */
    public function get_value();

    /**
     * Get the attribute output.
     *
     * @since 1.1
     * @return string
     */
    public function __toString();

} // end Interface Momtaz_Nmwdhj_Attribute

/**
 * The attributes class.
 *
 * @since 1.0
 */
class Momtaz_Nmwdhj_Attributes {

    /*** Properties ***********************************************************/

    /**
     * Attributes list.
     *
     * @since 1.0
     * @var Momtaz_Nmwdhj_Attribute[]
     */
    protected $attributes;


    /*** Magic Methods ********************************************************/

    /**
     * The Attributes class constructor.
     *
     * @since 1.0
     */
    public function __construct( $atts = null ) {

        // Reset the attributes.
        $this->reset_atts();

        // Set the attributes.
        $this->set_atts( $atts );

    } // end __construct()


    /*** Methods **************************************************************/

    // Getters

    /**
     * Get all the attributes array.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Attribute[]
     */
    public function get_atts() {
        return $this->attributes;
    } // end get_atts()

    /**
     * Get an attribute object.
     *
     * @since 1.1
     * @return Momtaz_Nmwdhj_Attribute
     */
    public function get_attr_obj( $key ) {

        $key = strtolower( $key );

        if ( isset( $this->attributes[ $key ] ) )
            return $this->attributes[ $key ];

    } // end get_attr_obj()

    /**
     * Get an attribute value.
     *
     * @since 1.0
     * @return string
     */
    public function get_attr( $key, $def = '' ) {

        $obj = $this->get_attr_obj( $key );

        if ( ! $obj && is_scalar( $def ) )
            return (string) $def;

        return $obj->get_value();

    } // end get_attr()

    // Checks

    /**
     * Check for an attribute existence.
     *
     * @since 1.0
     * @return boolean
     */
    public function has_attr( $key ) {

        if ( $key instanceof Momtaz_Nmwdhj_Attribute )
            $key = $key->get_key();

        if ( ! $this->get_attr_obj( $key ) )
            return false;

        return true;

    } // end has_attr()

    // Setters

    /**
     * Set many attributes at once.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Attributes
     */
    public function set_atts( $atts, $override = true ) {

        if ( $atts instanceof Momtaz_Nmwdhj_Attributes )
            $atts = $atts->get_atts();

        if ( is_array( $atts ) ) {

            foreach( $atts as $key => $value )
                $this->set_attr( $key, $value, $override );

        } // end if

        return $this;

    } // end set_atts()

    /**
     * Set an attribute value.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Attributes
     */
    public function set_attr( $key, $value, $override = true ) {

        $key = strtolower( $key );

        if ( $override || ! $this->has_attr( $key ) )
            $this->attributes[$key] = momtaz_nmwdhj_attr( $key, $value );

        return $this;

    } // end set_attr()

    // Remove

    /**
     * Remove many attributes at once.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Attributes
     */
    public function remove_atts( $keys ) {

        if ( $keys instanceof Momtaz_Nmwdhj_Attributes )
            $keys = array_keys( $keys->get_atts() );

        if ( is_array( $keys ) ) {

            foreach( $keys as $key )
                $this->remove_attr( $key );

        } // end if

        return $this;

    } // end remove_atts()

    /**
     * Remove an attribute.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Attributes
     */
    public function remove_attr( $key ) {

        $key = strtolower( $key );

        if ( is_array( $this->attributes ) )
            unset( $this->attributes[ $key ] );

        return $this;

    } // end remove_attr()

    // Reset

    /**
     * Reset the attributes array.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Attributes
     */
    public function reset_atts() {
        $this->attributes = array();
        return $this;
    } // end reset_atts()

    // Converters

    /**
     * Convert the attributes array to string.
     *
     * @since 1.0
     * @return string
     */
    public function to_string( array $args = null ) {

        $output = '';
        $atts = $this->get_atts();

        if ( count( $atts ) === 0 )
            return $output;

        $args = wp_parse_args( $args, array(
            'before' => ' ',
            'after' => '',
        ) );

        $atts = array_map( 'strval', $atts );
        $output = trim( implode( ' ', $atts ) );

        if ( empty( $output ) )
            return $output;

        return $args['before'] . $output . $args['after'];

    } // end to_string()

    public function __toString() {
        return $this->to_string();
    } // end __toString()

} // end Momtaz_Nmwdhj_Attributes

/**
 * The simple attribute class.
 *
 * @since 1.1
 */
class Momtaz_Nmwdhj_SimpleAttribute implements Momtaz_Nmwdhj_Attribute {

    /*** Properties ***********************************************************/

    /**
     * The attribute key.
     *
     * @since 1.1
     * @var string
     */
    protected $key;

    /**
     * The attribute value.
     *
     * @since 1.1
     * @var mixed
     */
    protected $value;


    /*** Magic Methods ********************************************************/

    /**
     * The Attribute class constructor.
     *
     * @since 1.1
     */
    public function __construct( $key, $value ) {

        // Set the attribute key.
        $this->set_key( $key );

        // Set the attribute value.
        $this->set_value( $value );

    } // end __construct()


    /*** Methods **************************************************************/

    // Getters

    /**
     * Get the attribute key.
     *
     * @since 1.1
     * @return string
     */
    public function get_key() {
        return $this->key;
    } // end get_key()

    /**
     * Get the attribute value.
     *
     * @since 1.1
     * @return string
     */
    public function get_value() {
        return $this->value;
    } // end get_value()

    // Setters

    /**
     * Set the attribute key.
     *
     * @since 1.1
     * @return Momtaz_Nmwdhj_SimpleAttribute
     */
    protected function set_key( $key ) {
        $this->key = $key;
        return $this;
    } // end set_key()

    /**
     * Set the attribute value.
     *
     * @since 1.1
     * @return Momtaz_Nmwdhj_SimpleAttribute
     */
    public function set_value( $value ) {
        $this->value = $value;
        return $this;
    } // end set_value()

    /**
     * Get the attribute output.
     *
     * @since 1.1
     * @return string
     */
    public function __toString(){

        $output = '';

        // Get the attribute key.
        $key = $this->get_key();

        // Get the attribute value.
        $value = $this->get_value();

        if ( ! empty( $key ) && $value !== false ) {

            if ( $value === true )
                $value = $key;

             $output = $key . '="' . esc_attr( $value ) . '"';

        } // end if

        return $output;

    } // end __toString()

} // end Class Momtaz_Nmwdhj_SimpleAttribute

/**
 * The CSS classes attribute.
 *
 * @since 1.1
 */
class Momtaz_Nmwdhj_ClassAttribute extends Momtaz_Nmwdhj_SimpleAttribute {

    // Getters

    /**
     * Get the classes list.
     *
     * @since 1.1
     * @return string|array
     */
    public function get_value( $type = 'string' ) {

        switch( strtolower( $type ) ) {

            case 'array':

                // Convert the classes list to an array.
                $this->value = $this->explode_classes( $this->value );

                break;

            default:
            case 'string':

                // Convert the classes list to a string.
                $this->value = $this->implode_classes( $this->value );

                break;

        } // end switch

        // Return the classes list.
        return $this->value;

    } // end get_value()

    // Checks

    /**
     *
     *
     * @since 1.1
     * @return boolean
     */
    public function has_classes( $classes ) {

        $classes = $this->explode_classes( $classes );

        if ( $classes ) {

            if ( in_array( $classes, $this->get_value( 'array' ) ) )
                return true;

        } // end if

        return false;

    } // end has_classes()

    // Setters

    /**
     * Adds many classes at once.
     *
     * @since 1.1
     * @return Momtaz_Nmwdhj_ClassAttribute
     */
    public function add_classes( $classes ) {

        $classes = $this->explode_classes( $classes );

        if ( $classes ) {

            $classes = array_merge( $this->get_value( 'array' ), $classes );
            $this->set_value( array_unique( $classes ) );

        } // end if

        return $this;

    } // end add_classes()

    /**
     * Removes many classes at once.
     *
     * @since 1.1
     * @return Momtaz_Nmwdhj_ClassAttribute
     */
    public function remove_classes( $classes ) {

        $classes = $this->explode_classes( $classes );

        if ( $classes ) {

            $classes = array_diff( $this->get_value( 'array' ), $classes );
            $this->set_value( $classes );

        } // end if

        return $this;

    } // end remove_classes()

    // Helpers

    /**
     * Convert the classes list to an array.
     *
     * @since 1.1
     * @return array
     */
    protected function explode_classes( $value ) {

        if ( $value instanceof Momtaz_Nmwdhj_ClassAttribute ){
            $value = $value->get_value( 'array' );

        } elseif ( is_string( $value ) ) {
            $value = explode( ' ', $value );

        } elseif ( ! is_array( $value ) ) {
            $value = array();

        } // end if

        return array_map( 'strtolower', $value );

    } // end explode_classes()

    /**
     * Convert the classes list to a string.
     *
     * @since 1.1
     * @return string
     */
    protected function implode_classes( $value ) {

        if ( $value instanceof Momtaz_Nmwdhj_ClassAttribute ){
            $value = $value->get_value( 'string' );

        } elseif ( is_array( $value ) ) {
            $value = implode( ' ', $value );

        } else {
            $value = (string) $value;

        } // end if

        return strtolower( $value );

    } // end implode_classes()

} // end Class Momtaz_Nmwdhj_ClassAttribute