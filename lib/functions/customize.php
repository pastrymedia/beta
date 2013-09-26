<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Customize Functions
 *
 * customize.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Functions for registering and setting theme settings that tie into the
 * WordPress theme customizer. This file loads additional classes and adds
 * settings to the customizer for the built-in ExMachina Core settings.
 *
 * @package     ExMachina
 * @subpackage  Functions
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright (c) 2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com
 */
###############################################################################
# Begin functions
###############################################################################

/* Load custom control classes. */
add_action( 'customize_register', 'exmachina_load_customize_controls', 1 );

/* Register custom sections, settings, and controls. */
add_action( 'customize_register', 'exmachina_customize_register' );
add_action( 'customize_register', 'exmachina_branding_customize_register' );

/* Add the footer content Ajax to the correct hooks. */
add_action( 'wp_ajax_exmachina_customize_footer_content', 'exmachina_customize_footer_content_ajax' );
add_action( 'wp_ajax_nopriv_exmachina_customize_footer_content', 'exmachina_customize_footer_content_ajax' );

/* Add customize preview script. */
add_action( 'customize_preview_init', 'exmachina_customize_preview_js' );

/**
 * Load Customize Controls
 *
 * Loads framework-specific customize control classes. Customize control classes
 * extend the WordPress WP_Customize_Control class to create unique classes that
 * can be used within the framework.
 *
 * @since 1.0.7
 * @access private
 *
 * @return void
 */
function exmachina_load_customize_controls() {

  /* Loads the textarea customize control class. */
  require_once( trailingslashit( EXMACHINA_CLASSES ) . 'customize-control-textarea.php' );

} // end function exmachina_load_customize_controls()

/**
 * Branding Customize Register
 *
 * Add postMessage support for site title and description for the Theme
 * Customizer.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @link http://codex.wordpress.org/Class_Reference/WP_Customize_Manager
 * @link http://codex.wordpress.org/Class_Reference/WP_Customize_Manager/get_setting
 *
 * @since 1.0.7
 * @access private
 *
 * @param  object $wp_customize Theme Customizer object.
 * @return void
 */
function exmachina_branding_customize_register( $wp_customize ) {

  $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
  $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
  $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

} // end function exmachina_branding_customize_register()

/**
 * Customize Register
 *
 * Registers custom sections, settings, and controls for the $wp_customize
 * instance.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @link http://codex.wordpress.org/Class_Reference/WP_Customize_Manager
 * @link http://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_section
 * @link http://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
 * @link http://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
 * @link http://codex.wordpress.org/Function_Reference/get_theme_support
 * @link http://codex.wordpress.org/Function_Reference/esc_html
 * @link http://codex.wordpress.org/Function_Reference/is_preview
 * @link http://codex.wordpress.org/Function_Reference/is_admin
 *
 * @uses exmachina_get_prefix()                 Gets the theme prefix.
 * @uses exmachina_get_default_theme_settings() Gets default theme settings.
 *
 * @since 1.0.7
 * @access private
 *
 * @param  object $wp_customize Theme Customizer object.
 * @return void
 */
function exmachina_customize_register( $wp_customize ) {

  /* Get supported theme settings. */
  $supports = get_theme_support( 'exmachina-core-theme-settings' );

  /* Get the theme prefix. */
  $prefix = exmachina_get_prefix();

  /* Get the default theme settings. */
  $default_settings = exmachina_get_default_theme_settings();

  /* Add the footer section, setting, and control if theme supports the 'footer' setting. */
  if ( is_array( $supports[0] ) && in_array( 'footer', $supports[0] ) ) {

    /* Add the footer section. */
    $wp_customize->add_section(
      'exmachina-core-footer',
      array(
        'title'      => esc_html__( 'Footer', 'exmachina-core' ),
        'priority'   => 200,
        'capability' => 'edit_theme_options'
      )
    );

    /* Add the 'footer_insert' setting. */
    $wp_customize->add_setting(
      "{$prefix}_theme_settings[footer_insert]",
      array(
        'default'              => $default_settings['footer_insert'],
        'type'                 => 'option',
        'capability'           => 'edit_theme_options',
        'sanitize_callback'    => 'exmachina_customize_sanitize',
        'sanitize_js_callback' => 'exmachina_customize_sanitize',
        'transport'            => 'postMessage',
      )
    );

    /* Add the textarea control for the 'footer_insert' setting. */
    $wp_customize->add_control(
      new ExMachina_Customize_Control_Textarea(
        $wp_customize,
        'exmachina-core-footer',
        array(
          'label'    => esc_html__( 'Footer', 'exmachina-core' ),
          'section'  => 'exmachina-core-footer',
          'settings' => "{$prefix}_theme_settings[footer_insert]",
        )
      )
    );

    /* If viewing the customize preview screen, add a script to show a live preview. */
    if ( $wp_customize->is_preview() && !is_admin() )
      add_action( 'wp_footer', 'exmachina_customize_preview_script', 21 );
  }

} // end function exmachina_customize_register()

/**
 * Customize Sanitize
 *
 * Sanitizes the footer content on the customize screen. Users with the
 * 'unfiltered_html' cap can post anything. For other users, wp_filter_post_kses()
 * is ran over the setting.
 *
 * @link http://codex.wordpress.org/Function_Reference/current_user_can
 * @link http://codex.wordpress.org/Function_Reference/wp_filter_post_kses
 *
 * @uses exmachina_get_prefix() Gets the theme prefix.
 *
 * @since 1.0.7
 * @access public
 *
 * @param  mixed  $setting The current setting passed to sanitize.
 * @param  object $object  The setting object passed via WP_Customize_Setting.
 * @return mixed           The sanitized setting.
 */
function exmachina_customize_sanitize( $setting, $object ) {

  /* Get the theme prefix. */
  $prefix = exmachina_get_prefix();

  /* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
  if ( "{$prefix}_theme_settings[footer_insert]" == $object->id && !current_user_can( 'unfiltered_html' )  )
    $setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );

  /* Return the sanitized setting and apply filters. */
  return apply_filters( "{$prefix}_customize_sanitize", $setting, $object );

} // end function exmachina_customize_sanitize()

/**
 * Customize Footer Content AJAX
 *
 * Runs the footer content posted via Ajax through the do_shortcode() function.
 * This makes sure the shortcodes are output correctly in the live preview.
 *
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer
 * @link http://codex.wordpress.org/Function_Reference/do_shortcode
 * @link http://codex.wordpress.org/Function_Reference/wp_kses_stripslashes
 *
 * @since 1.0.7
 * @access private
 *
 * @return void
 */
function exmachina_customize_footer_content_ajax() {

  /* Check the AJAX nonce to make sure this is a valid request. */
  check_ajax_referer( 'exmachina_customize_footer_content_nonce' );

  /* If footer content has been posted, run it through the do_shortcode() function. */
  if ( isset( $_POST['footer_content'] ) )
    echo do_shortcode( wp_kses_stripslashes( $_POST['footer_content'] ) );

  /* Always die() when handling Ajax. */
  die();

} // end function exmachina_customize_footer_content_ajax()

/**
 * Customize Preview Script
 *
 * Handles changing settings for the live preview of the theme.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_create_nonce
 *
 * @since 1.0.7
 * @access private
 *
 * @return void
 */
function exmachina_customize_preview_script() {

  /* Create a nonce for the Ajax. */
  $nonce = wp_create_nonce( 'exmachina_customize_footer_content_nonce' );

  ?>
  <script type="text/javascript">
  wp.customize(
    '<?php echo exmachina_get_prefix(); ?>_theme_settings[footer_insert]',
    function( value ) {
      value.bind(
        function( to ) {
          jQuery.post(
            '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            {
              action: 'exmachina_customize_footer_content',
              _ajax_nonce: '<?php echo $nonce; ?>',
              footer_content: to
            },
            function( response ) {
              jQuery( '.footer-content' ).html( response );
            }
          );
        }
      );
    }
  );
  </script>
  <?php

} // end function exmachina_customize_preview_script()

/**
 * Customize Preview JavaScript
 *
 * Binds JS handlers to make Theme Customizer preview reload changes
 * asynchronously.
 *
 * @since 1.0.7
 * @access private
 *
 * @return void
 */
function exmachina_customize_preview_js() {

  wp_enqueue_script( 'exmachina-customizer', esc_url( trailingslashit( EXMACHINA_JS ) . 'customizer.js'), array( 'customize-preview' ), '20130508', true );

} // end function exmachina_customize_preview_js()