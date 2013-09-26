<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * JavaScript Loader
 *
 * scripts.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Functions for handling JavaScript in the framework. Themes can add support
 * for the 'exmachina-core-scripts' feature to allow the framework to handle
 * loading the stylesheets into the theme header or footer at an appropriate
 * time.
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

/* Register ExMachina Core scripts. */
add_action( 'wp_enqueue_scripts', 'exmachina_register_scripts', 1 );

/* Load ExMachina Core scripts. */
add_action( 'wp_enqueue_scripts', 'exmachina_enqueue_scripts' );

/**
 * Register JavaScripts
 *
 * Registers JavaScript files for the framework. This function merely registers
 * scripts with WordPress using the wp_register_script() function. It does not
 * load any script files on the site. If a theme wants to register its own
 * custom scripts, it should do so on the 'wp_enqueue_scripts' hook.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_register_script
 * @link http://codex.wordpress.org/Function_Reference/get_theme_support
 * @link http://codex.wordpress.org/Function_Reference/esc_url
 *
 * @uses apply_atomic() Gets the contextual filter hook.
 *
 * @since 1.1.1
 * @access private
 *
 * @return void
 */
function exmachina_register_scripts() {

  /* Supported JavaScript. */
  $supports = get_theme_support( 'exmachina-core-scripts' );

  /* Use the .min script if SCRIPT_DEBUG is turned off. */
  $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

  /* Register the 'drop-downs' script if the current theme supports 'drop-downs'. */
  if ( isset( $supports[0] ) && in_array( 'drop-downs', $supports[0] ) )
    wp_register_script( 'drop-downs', esc_url( apply_atomic( 'drop_downs_script', trailingslashit( EXMACHINA_JS ) . "drop-downs{$suffix}.js" ) ), array( 'jquery' ), EXMACHINA_VERSION, true );

  /* Register the 'nav-bar' script if the current theme supports 'nav-bar'. */
  if ( isset( $supports[0] ) && in_array( 'nav-bar', $supports[0] ) )
    wp_register_script( 'nav-bar', esc_url( apply_atomic( 'nav_bar_script', trailingslashit( EXMACHINA_JS ) . "nav-bar{$suffix}.js" ) ), array( 'jquery' ), EXMACHINA_VERSION, true );

  /* Register the 'mobile-toggle' script if the current theme supports 'mobile-toggle'. */
  if ( isset( $supports[0] ) && in_array( 'mobile-toggle', $supports[0] ) )
    wp_register_script( 'mobile-toggle', esc_url( trailingslashit( EXMACHINA_JS ) . "mobile-toggle{$suffix}.js" ), array( 'jquery' ), EXMACHINA_VERSION, true );

  /* Register the 'main' script if the current theme supports 'main'. */
  if ( isset( $supports[0] ) && in_array( 'main', $supports[0] ) )
    wp_register_script( 'main', esc_url( apply_atomic( 'main_script', trailingslashit( EXMACHINA_JS ) . "main{$suffix}.js" ) ), array( 'jquery' ), EXMACHINA_VERSION, true );

  /* Register the 'plugins' script if the current theme supports 'plugins'. */
  if ( isset( $supports[0] ) && in_array( 'plugins', $supports[0] ) )
    wp_register_script( 'plugins', esc_url( apply_atomic( 'plugins', trailingslashit( EXMACHINA_JS ) . "plugins{$suffix}.js" ) ), array( 'jquery' ), EXMACHINA_VERSION, true );

} // end function exmachina_register_scripts()

/**
 * Enqueue JavaScripts
 *
 * Tells WordPress to load the scripts needed for the framework using the
 * wp_enqueue_script() function.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_script
 * @link http://codex.wordpress.org/Function_Reference/get_theme_support
 * @link http://codex.wordpress.org/Function_Reference/get_option
 * @link http://codex.wordpress.org/Function_Reference/is_singular
 * @link http://codex.wordpress.org/Function_Reference/comments_open
 *
 * @since 1.1.1
 * @access private
 *
 * @return void
 */
function exmachina_enqueue_scripts() {

  /* Supported JavaScript. */
  $supports = get_theme_support( 'exmachina-core-scripts' );

  /* Load the comment reply script on singular posts with open comments if threaded comments are supported. */
  if ( is_singular() && get_option( 'thread_comments' ) && comments_open() )
    wp_enqueue_script( 'comment-reply' );

  /* Load the 'drop-downs' script if the current theme supports 'drop-downs'. */
  if ( isset( $supports[0] ) && in_array( 'drop-downs', $supports[0] ) )
    wp_enqueue_script( 'drop-downs' );

  /* Load the 'nav-bar' script if the current theme supports 'nav-bar'. */
  if ( isset( $supports[0] ) && in_array( 'nav-bar', $supports[0] ) )
    wp_enqueue_script( 'nav-bar' );

  /* Load the 'mobile-toggle' script if the current theme supports 'mobile-toggle'. */
  if ( isset( $supports[0] ) && in_array( 'mobile-toggle', $supports[0] ) )
    wp_enqueue_script( 'mobile-toggle' );

  /* Load the 'main' script if the current theme supports 'main'. */
  if ( isset( $supports[0] ) && in_array( 'main', $supports[0] ) )
    wp_enqueue_script( 'main' );

  /* Load the 'plugins' script if the current theme supports 'plugins'. */
  if ( isset( $supports[0] ) && in_array( 'plugins', $supports[0] ) )
    wp_enqueue_script( 'plugins' );

} // end function exmachina_enqueue_scripts()