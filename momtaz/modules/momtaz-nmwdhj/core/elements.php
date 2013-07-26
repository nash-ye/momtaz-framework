<?php
/**
 * The element interface.
 *
 * @since 1.0
 */
 interface Momtaz_Nmwdhj_Element {
 } // end Interface Momtaz_Nmwdhj_Element

/**
 * The Momtaz Nmwdhj Elements class.
 *
 * @since 1.0
 */
class Momtaz_Nmwdhj_Elements {

    /*** Properties ***********************************************************/

    /**
     * Elements list.
     *
     * @since 1.0
     * @var array
     */
    protected static $elements = array();


    /*** Methods **************************************************************/

    // Getters

    /**
     * Get an element by key.
     *
     * @since 1.0
     * @return array
     */
    public static function get_by_key( $key ) {

        $key = sanitize_key( $key );

        if ( ! empty( $key ) ) {

            $elements = self::get();

            if ( isset( $elements[ $key ] ) )
                return $elements[ $key ];

            foreach ( $elements as $element ) {

                if ( in_array( $key, (array) $element['aliases'] ) ) {

                    return $element;

                } // end if

            } // end foreach

        } // end if

        return array();

    } // end get_by_key()

    /**
     * Retrieve a list of registered elements.
     *
     * @since 1.0
     * @return array
     */
    public static function get( array $args = null, $operator = 'AND' ) {
        return wp_list_filter( self::$elements, $args, $operator );
    } // end get()

    // Register/Deregister

    /**
     * Register an element.
     *
     * @since 1.0
     * @return boolean
     */
    public static function register( $key, array $args ) {

        $args['key'] = sanitize_key( $key );

        if ( empty( $args['key'] ) )
            return false;

        $args = wp_parse_args( $args, array(
            'aliases' => array(),
            'class_name' => '',
            'class_path' => '',
        ) );

        if ( empty( $args['class_name'] ) )
            return false;

        if ( ! file_exists( $args['class_path'] ) )
            return false;

        $args['aliases'] = (array) $args['aliases'];
        array_walk( $args['aliases'], 'sanitize_key' );

        // Register the element.
        self::$elements[ $args['key'] ] = $args;

        return true;

    } // end register()

    /**
     * Register the default elements.
     *
     * @since 1.0
     * @return void
     */
    public static function register_defaults() {

        self::register( 'button', array(
            'class_name' => 'Momtaz_Nmwdhj_Element_Button',
            'class_path' => Momtaz_Nmwdhj::get_path( 'elements/Button.php' ),
            'aliases' => array( 'button_submit', 'button_reset' ),
        ) );

        self::register( 'select', array(
            'class_name' => 'Momtaz_Nmwdhj_Element_Select',
            'class_path' => Momtaz_Nmwdhj::get_path( 'elements/Select.php' ),
        ) );

        self::register( 'textarea', array(
            'class_name' => 'Momtaz_Nmwdhj_Element_Textarea',
            'class_path' => Momtaz_Nmwdhj::get_path( 'elements/Textarea.php' ),
        ) );

        self::register( 'wp_editor', array(
            'class_name' => 'Momtaz_Nmwdhj_Element_WP_Editor',
            'class_path' => Momtaz_Nmwdhj::get_path( 'elements/WP_Editor.php' ),
        ) );

        self::register( 'checkbox', array(
                'class_name' => 'Momtaz_Nmwdhj_Element_Checkbox',
                'class_path' => Momtaz_Nmwdhj::get_path( 'elements/Checkbox.php' ),
                'aliases' => array( 'input_checkbox' ),
            )
        );

        self::register( 'checkboxes', array(
                'class_name' => 'Momtaz_Nmwdhj_Element_Checkboxes',
                'class_path' => Momtaz_Nmwdhj::get_path( 'elements/Checkboxes.php' ),
                'aliases' => array( 'multi_checkbox' ),
            )
        );

        self::register( 'input', array(
                'class_name' => 'Momtaz_Nmwdhj_Element_Input',
                'class_path' => Momtaz_Nmwdhj::get_path( 'elements/Input.php' ),
                'aliases' => array(
                    'input_text', 'input_url', 'input_email', 'input_range', 'input_search', 'input_date', 'input_file',
                    'input_hidden', 'input_number', 'input_password', 'input_color', 'input_submit', 'input_week',
                    'input_time', 'input_radio', 'input_month', 'input_image',
                ),
            )
        );

    } // end register_defaults()

    /**
     * Remove a registered element.
     *
     * @since 1.0
     * @return boolean
     */
    public static function deregister( $key ) {

        $key = sanitize_key( $key );

        if ( empty( $key ) )
            return false;

        if ( ! isset( self::$elements[ $key ] ) ) {

            foreach ( self::$elements as &$element ) {

                $element['aliases'] = array_diff( (array) $element['aliases'], array( $key ) );

            } // end foreach

        } else {

            unset( self::$elements[ $key ] );

        } // end if

        return true;

    } // end deregister()

    // Checks

    /**
     * Check element class.
     *
     * @since 1.0
     * @return boolean
     */
    public static function check_class( $class_name, $autoload = true ) {

        if ( empty( $class_name ) )
            return false;

        if ( ! class_exists( $class_name, $autoload ) )
            return false;

        if ( ! is_subclass_of( $class_name, 'Momtaz_Nmwdhj_Element' ) )
            return false;

        return true;

    } // end check_class()

    // Loaders

    /**
     * Load element class file.
     *
     * @since 1.0
     * @return boolean
     */
    public static function load_class( $class_name ) {

        if ( empty( $class_name ) )
            return false;

        if ( ( $element = self::get( array( 'class_name' => $class_name ), 'OR' ) ) ) {

            $element = reset( $element );

            if ( ! empty( $element['class_path'] ) && file_exists( $element['class_path'] ) ) {

                require $element['class_path'];

                return true;

            } // end if

        } // end if

        return false;

    } // end load_class()

} // end Class Momtaz_Nmwdhj_Elements

/**
 * The abstract simple element class.
 *
 * @since 1.0
 */
abstract class Momtaz_Nmwdhj_SimpleElement implements Momtaz_Nmwdhj_Element {

    /*** Properties ***********************************************************/

    /**
     * Element key.
     *
     * @since 1.0
     * @var string
     */
    protected $key;

    /**
     * Element value.
     *
     * @since 1.0
     * @var mixed
     */
    protected $value;

    /**
     * Element view key.
     *
     * @since 1.0
     * @var string
     */
    protected $view_key;

    /**
     * Element attributes object.
     *
     * @since 1.0
     * @var Momtaz_Nmwdhj_Attributes
     */
    protected $attributes;

    /**
     * Element value callback.
     *
     * @since 1.0
     * @var array
     */
    protected $value_callback;

    /**
     * Element options.
     *
     * @since 1.0
     * @var array
     */
    protected $options = array();


    /*** Magic Methods ********************************************************/

    /**
     * The default element constructor.
     *
     * @since 1.0
     */
    public function __construct( $key, array $properties = null ) {

        $this->set_key( $key );

        if ( ! is_null( $properties ) ) {

            foreach ( $properties as $property => $value ) {

                switch( strtolower( $property ) ) {

                    case 'id':
                        $this->set_ID( $value );
                        break;

                    case 'nid':
                        $this->set_NID( $value );
                        break;

                    case 'name':
                        $this->set_name( $value );
                        break;

                    case 'atts':
                        $this->set_atts( $value );
                        break;

                    case 'value':
                        $this->set_value( $value );
                        break;

                    case 'options':
                        $this->set_options( $value );
                        break;

                    case 'view_key':
                        $this->set_view_key( $value );
                        break;

                } // end Switch

            } // end foreach

        } // end if

    } // end __construct()


    /*** Methods **************************************************************/

    // Key

    /**
     * Get the element key.
     *
     * @since 1.0
     * @return string
     */
    public function get_key() {
        return $this->key;
    } // end get_key()

    /**
     * Set the element key.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    protected function set_key( $key ) {

        if ( ! empty( $key ) )
            $this->key = $key;

        return $this;

    } // end set_key()

    // Value

    /**
     * Get the element value.
     *
     * @since 1.0
     * @return mixed
     */
    public function get_value() {

        if ( is_null( $this->value ) ) {

            $callback = $this->get_value_callback();

            if ( is_array( $callback ) && ! empty( $callback ) )
                $this->set_value( call_user_func_array( $callback['name'], $callback['args'] ) );

        } // end if

        return $this->value;

    } // end get_value()

    /**
     * Set the element value.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function set_value( $value ) {
        $this->value = $value;
        return $this;
    } // end set_value()

    /**
     * Get the element value callback.
     *
     * @since 1.0
     * @return array
     */
    public function get_value_callback() {
        return $this->value_callback;
    } // end get_value_callback()

    /**
     * Set a value callback.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function set_value_callback( $callback ) {

        $params = array_slice( func_get_args(), 1 );
        $this->set_value_callback_array( $callback, $params );

        return $this;

    } // end set_value_callback()

    /**
     * Set a value callback with an array of parameters.
     *
     * @since 1.1
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function set_value_callback_array( $callback, array $param ) {

        if ( is_callable( $callback ) ) {

            $this->value_callback = array(
                'name' => $callback,
                'args' => $param,
            );

        } // end if

        return $this;

    } // end set_value_callback_array()

    // The Special Attributes

    /**
     * Set the element 'id' and 'name' attributes.
     *
     * @since 1.0
     * @return string
     */
    public function set_NID( $value ) {
        $this->set_name( $value );
        $this->set_ID( $value );
        return $this;
    } // end set_NID()

    /**
     * Get the element ID attribute.
     *
     * @since 1.0
     * @return string
     */
    public function get_ID( $def = '' ) {
        return $this->get_attr( 'id', $def );
    } // end get_ID()

    /**
     * Set the element ID attribute.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function set_ID( $value ) {
        $this->set_attr( 'id', $value );
        return $this;
    } // end set_ID()

    /**
     * Get the element name attribute.
     *
     * @since 1.0
     * @return string
     */
    public function get_name( $def = '' ) {
        return $this->get_attr( 'name', $def );
    } // end get_name()

    /**
     * Set the element name attribute.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function set_name( $value ) {
        $this->set_attr( 'name', $value );
        return $this;
    } // end set_name()

    // View Key

    /**
     * Get the element view key.
     *
     * @since 1.0
     * @return string
     */
    public function get_view_key() {
        return $this->view_key;
    } // end get_view_key()

    /**
     * Set the element view key.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function set_view_key( $key ) {
        $this->view_key = $key;
        return $this;
    } // end set_view_key()

    // Output

    /**
     * Get the element output.
     *
     * @since 1.0
     * @return string
     */
    public function get_output() {
        return Momtaz_Nmwdhj::view_element( $this );
    } // end get_output()

    /**
     * Display the element output.
     *
     * @since 1.0
     */
    public function output() {
        echo $this->get_output();
    } // end output()

    // Attributes

    /**
     *  Get all the attributes array.
     *
     * @since 1.0
     * @return array
     */
    public function get_atts() {
        return $this->get_atts_obj()->get_atts();
    } // end get_atts()

    /**
     * Get an attribute value.
     *
     * @since 1.0
     * @return string
     */
    public function get_attr( $key, $def = '' ) {
        return $this->get_atts_obj()->get_attr( $key, $def );
    } // end get_attr()

    /**
     * Get an attribute object.
     *
     * @since 1.0
     * @return string
     */
    public function get_attr_obj( $key ) {
        return $this->get_atts_obj()->get_attr_obj( $key );
    } // end get_attr_obj()

    /**
     * Check for an attribute existence.
     *
     * @since 1.0
     * @return boolean
     */
    public function has_attr( $key ) {
        return $this->get_atts_obj()->has_attr( $key );
    } // end has_attr()

    /**
     * Set many attributes at once.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function set_atts( array $atts ) {

        foreach( $atts as $key => $value )
            $this->set_attr( $key, $value );

        return $this;

    } // end set_atts()

    /**
     * Set an attribute value.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function set_attr( $key, $value ) {
        $this->get_atts_obj()->set_attr( $key, $value );
        return $this;
    } // end set_attr()

    /**
     * Remove many attributes at once.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function remove_atts( array $keys ) {

        foreach( $keys as $key )
            $this->remove_attr( $key );

        return $this;

    } // end remove_atts()

    /**
     * Remove an attribute.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function remove_attr( $key ) {
        $this->get_atts_obj()->remove_attr( $key );
        return $this;
    } // end remove_attr()

    /**
     * Convert the attributes list to string.
     *
     * @since 1.0
     * @return string
     */
    public function get_atts_string( array $args = null ) {
        return $this->get_atts_obj()->to_string( $args );
    } // end get_atts_string()

    /**
     * Get the attributes object.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Attributes
     */
    protected function get_atts_obj() {

        if ( is_null( $this->attributes ) )
            $this->attributes = new Momtaz_Nmwdhj_Attributes();

        return $this->attributes;

    } // end get_atts_obj()

    // Options

    /**
     * Get the defined options.
     *
     * @since 1.0
     * @return array
     */
    public function get_options() {
        return $this->options;
    } // end get_options()

    /**
     * Get a specified option.
     *
     * @since 1.0
     * @return mixed
     */
    public function get_option( $option, $def = '' ) {

        if ( ! empty( $option ) ) {

            $options = $this->get_options();

            if ( isset( $options[$option] ) )
                return $options[$option];

        } // end if

        return $def;

    } // end get_option()

    /**
     * Set the element options.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function set_options( $options ) {

        if ( is_array( $options ) )
            $this->options = $options;

        return $this;

    } // end set_options()

    /**
     * Set a specified option.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function set_option( $option, $value ) {

        if ( ! empty( $option ) ) {

            $this->options[$option] = $value;

        } // end if

        return $this;

    } // end set_option()

    /**
     * Remove all/specified options.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function remove_options( $options = '' ) {

        if ( is_array( $options ) && ! empty( $options ) ) {

            foreach( $options as $option )
                $this->remove_option( $option );

        } else {

            $this->set_options( array() );

        } // end if

        return $this;

    } // end remove_options()

    /**
     * Remove a specified option.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_SimpleElement
     */
    public function remove_option( $option ) {

        if ( ! empty( $option ) ) {

            unset( $this->options[$option] );

        } // end if

        return $this;

    } // end remove_option()

} // end Class Momtaz_Nmwdhj_Element

/**
 * Get the element output.
 *
 * @return string
 * @since 1.0
 */
function momtaz_nmwdhj_get_element_output( $element ) {

    if ( $element instanceof Momtaz_Nmwdhj_Element )
        return $element->get_output();

    return $element;

} // end momtaz_nmwdhj_get_element_output()