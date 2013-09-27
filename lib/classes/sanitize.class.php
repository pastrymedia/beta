<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Sanitization Class
 *
 * sanitize.class.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * This class sanitizes settings before being saved to the database. The filter
 * methods should cover most sanitization needs, however, developers may add
 * additional filters using the add_filter() method.
 *
 * @package     ExMachina
 * @subpackage  Classes
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright (c) 2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com
 */
###############################################################################
# Begin class
###############################################################################

/**
 * Settings Sanitiation Class
 *
 * Ensures that saved values are of the expected type.
 *
 * @since 1.5.1
 */
class ExMachina_Settings_Sanitizer {

  /**
   * Static Instance
   *
   * Holds an instance of self methods that can be accessed staticaly.
   *
   * @since 1.5.1
   * @var ExMachina_Settings_Sanitizer
   */
  static $instance;

  /**
   * Options Variable
   *
   * Holds a list of all the options as an array.
   *
   * @since 1.5.1
   * @var array
   */
  var $options = array();

  /**
   * Sanitizer Class Constructor
   *
   * Calls an instance of self and annouces that the sanitizer is ready.
   *
   * @link http://codex.wordpress.org/Function_Reference/do_action_ref_array
   *
   * @since 1.5.1
   */
  function __construct() {

    self::$instance =& $this;

    /* Announce that the sanitizer is ready, and pass the object (for advanced use). */
    do_action_ref_array( 'exmachina_settings_sanitizer_init', array( &$this ) );

  } // end function __construct()

  /**
   * Add Sanitization Filters
   *
   * Adds sanitization filters to the options. Associates a sanitiation filter
   * to each option (or sub-options if defined) before adding a reference to
   * run the option through that particular sanitizer at the right time.
   *
   * @uses ExMachina_Settings_Sanitizer::sanitize() The sanitizer value callback.
   *
   * @since 1.5.1
   * @access public
   *
   * @param  string         $filter     Sanitization filter type.
   * @param  string         $option     Option key.
   * @param  array|string   $suboption  Optional. Sub-option key.
   * @return boolean                    Returns true when complete.
   */
  function add_filter( $filter, $option, $suboption = null ) {

    /* Loop thorugh the option to find any available sub-options (if they exist). */
    if ( is_array( $suboption ) ) {
      foreach ( $suboption as $so ) {
        $this->options[$option][$so] = $filter;
      }
    } elseif ( is_null( $suboption ) ) {
      $this->options[$option] = $filter;
    } else {
      $this->options[$option][$suboption] = $filter;
    }

    /* Adds the 'sanitize_option_$option' filter. */
    add_filter( 'sanitize_option_' . $option, array( $this, 'sanitize' ), 10, 2 );

    /* Returns true when complete. */
    return true;

  } // end function add_filter()

  /**
   * Do Sanitization Filter
   *
   * Checks that the particular sanitization filter exists, and if so, passes a
   * value through it.
   *
   * @uses ExMachina_Settings_Sanitizer::get_available_filters() Array of known sanitization filter types.
   *
   * @since 1.5.1
   *
   * @param  string $filter    Sanitization filter type.
   * @param  string $new_value New value.
   * @param  string $old_value Old value.
   * @return mixed             Returns filtered value.
   */
  function do_filter( $filter, $new_value, $old_value ) {

    /* Defines the $available_filters variable aganinst the array of available filters. */
    $available_filters = $this->get_available_filters();

    /* Returns the submitted value if the available filter doesn't exist. */
    if ( ! in_array( $filter, array_keys( $available_filters ) ) )
      return $new_value;

    /* Returns the filtered value. */
    return call_user_func( $available_filters[$filter], $new_value, $old_value );

  } // end function do_filter()

  /**
   * Get Available Sanitization Filters
   *
   * Returns an array of known sanitization filter types. The array can be
   * filtered via the 'exmachina_available_sanitizer_filters' to let child
   * themes and plugins add their own sanitization filters.
   *
   * @uses one_zero()                 Returns a 1 or 0.
   * @uses absint()                   Returns a positive integer.
   * @uses url()                      Returns a safe URL value.
   * @uses no_html()                  Removes all HTML tags.
   * @uses safe_html()                Removes all unsafe HTML tags.
   * @uses requires_unfiltered_html() Returns unfiltered HTML (if allowed).
   *
   * @since 1.5.1
   *
   * @return array Array of sanitization types and corresponding callback functions.
   */
  function get_available_filters() {

    /* Define the default sanitization filter types. */
    $default_filters = array(
      'one_zero'                 => array( $this, 'one_zero'                 ),
      'absint'                   => array( $this, 'absint'                   ),
      'url'                      => array( $this, 'url'                      ),
      'no_html'                  => array( $this, 'no_html'                  ),
      'safe_html'                => array( $this, 'safe_html'                ),
      'requires_unfiltered_html' => array( $this, 'requires_unfiltered_html' ),
    );

    /* Allow additional sanitizer types to be added via filter. */
    return apply_filters( 'exmachina_available_sanitizer_filters', $default_filters );

  } // end function get_available_filters()

  /**
   * Sanitizer
   *
   * Sanitize a value via the sanitization filter type associated with the
   * option.
   *
   * @link http://codex.wordpress.org/Function_Reference/get_option
   *
   * @uses ExMachina_Settings_Sanitizer::do_filter() Passes the value through the sanitization filter.
   *
   * @since 1.5.1
   *
   * @param  mixed  $new_value New value.
   * @param  string $option    Option name.
   * @return mixed             Filtered or unfiltered value.
   */
  function sanitize( $new_value, $option ) {

    if ( !isset( $this->options[$option] ) ) {
      /* We are not filtering this option at all. */
      return $new_value;
    } elseif ( is_string( $this->options[$option] ) ) {
      /* Single option value. */
      return $this->do_filter( $this->options[$option], $new_value, get_option( $option ) );
    } elseif ( is_array( $this->options[$option] ) ) {
      /* Array of suboption values to loop through. */
      $old_value = get_option( $option );
      foreach ( $this->options[$option] as $suboption => $filter ) {
        $old_value[$suboption] = isset( $old_value[$suboption] ) ? $old_value[$suboption] : '';
        $new_value[$suboption] = isset( $new_value[$suboption] ) ? $new_value[$suboption] : '';
        $new_value[$suboption] = $this->do_filter( $filter, $new_value[$suboption], $old_value[$suboption] );
      }
      return $new_value;
    } else {
      /* Should never hit this, but:. */
      return $new_value;
    }

  } // end function sanitize()

  /*-------------------------------------------------------------------------*/
  /* Begin the sanitization filter methods. */
  /*-------------------------------------------------------------------------*/

  /**
   * One Zero Filter
   *
   * Returns a 1 or a 0 for checkboxes and all truthy/falsey values. Uses
   * double casting to cast to a boolean, then to an integer.
   *
   * @since 1.5.1
   *
   * @param  mixed   $new_value New value.
   * @return integer            Returns a 1 or 0.
   */
  function one_zero( $new_value ) {

    return (int) (bool) $new_value;

  } // end function one_zero()

  /**
   * Absolute Integer Filter
   *
   * Returns a positive integer value via the absint() filter.
   *
   * @link http://codex.wordpress.org/Function_Reference/absint
   *
   * @since 1.5.1
   *
   * @param  mixed    $new_value  New value.
   * @return integer              Returns a positive integer.
   */
  function absint( $new_value ) {

    return absint( $new_value );

  } // end function absint()

  /**
   * Safe URL Filter
   *
   * Returns a safe URL value via the esc_url_raw() filter.
   *
   * @link http://codex.wordpress.org/Function_Reference/esc_url_raw
   *
   * @since 1.5.1
   *
   * @param  string $new_value New value.
   * @return string            Returns a safe URL string.
   */
  function url( $new_value ) {

    return esc_url_raw( $new_value );

  } // end function url()

  /**
   * No HTML Filter
   *
   * Removes HTML tags from a string via the strip_tags() function.
   *
   * @since 1.5.1
   *
   * @param  string $new_value New value.
   * @return string            Returns a string stripped of HTML tags.
   */
  function no_html( $new_value ) {

    return strip_tags( $new_value );

  } // end function no_html()

  /**
   * Safe HTML Filter
   *
   * @link http://codex.wordpress.org/Function_Reference/wp_kses_post
   *
   * Removes unsafe HTML tags via the wp_kses_post() filter.
   *
   * @since 1.5.1
   *
   * @param  string $new_value New value.
   * @return string            Returns a string stripped of unsafe HTML tags.
   */
  function safe_html( $new_value ) {

    return wp_kses_post( $new_value );

  } // end function safe_html()

  /**
   * Unfiltered HTML Filter
   *
   * Returns unfiltered HTML, but only if the user updating the value has
   * the 'unfiltered_html' capability. Otherwise, the value goes unchanged.
   *
   * @link http://codex.wordpress.org/Function_Reference/current_user_can
   *
   * @since 1.5.1
   *
   * @param  string $new_value New value.
   * @param  string $old_value Old value.
   * @return string            Returns a string of either the new value or the old value.
   */
  function requires_unfiltered_html( $new_value, $old_value ) {

    if ( current_user_can( 'unfiltered_html' ) )
      return $new_value;
    else
      return $old_value;

  } // end function requires_unfiltered_html()

} // end class ExMachina_Settings_Sanitizer

/**
 * Add Option Filter
 *
 * Registers an option sanitization filter. If the option is an array option type
 * with suboptions, the third parameter is needed to specify the suboption or
 * suboptions to apply the filter to. Use the 'exmachina_settings_sanitier_init'
 * action to be notified when this function is ready to use.
 *
 * @uses ExMachina_Settings_Sanitizer::add_filter() Adds the sanitization filter to an option.
 *
 * @since 1.5.1
 *
 * @param  string       $filter     The filter type to use.
 * @param  string       $option     The option name to filter.
 * @param  string|array $suboption  Optional. The suboptions to filter.
 * @return boolean                  Returns true when complete.
 */
function exmachina_add_option_filter( $filter, $option, $suboption = null ) {

  return ExMachina_Settings_Sanitizer::$instance->add_filter( $filter, $option, $suboption );

} // end function exmachina_add_option_filter()

add_action( 'admin_init', 'exmachina_settings_sanitizer_init' );
/**
 * Settings Sanitizer Init
 *
 * Initializes the settings sanitization class.
 *
 * @since 1.5.1
 */
function exmachina_settings_sanitizer_init() {

  new ExMachina_Settings_Sanitizer;

} // end function exmachina_settings_sanitizer_init()



