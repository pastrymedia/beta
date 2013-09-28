<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Beta WordPress Theme
 * Main Theme Functions
 *
 * functions.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Please do all modifications in the form
 * of a child theme.
 *
 * The functions file is used to initialize everything in the theme. It controls
 * how the theme is loaded and sets up the supported features, default actions,
 * and default filters. If making customizations, users should create a child
 * theme and make changes to its functions.php file (not this one).
 *
 * Child themes should do their setup on the 'exmachina_init' action hook with
 * a priority of 11 if they want to override parent theme features. Use a priority
 * of 9 or lower if wanted to run before the parent theme.
 *
 * @package     Beta
 * @subpackage  Functions
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright(c) 2012-2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com/
 */
###############################################################################
# begin functions
###############################################################################

/* Load the core theme framework. */
require ( trailingslashit( get_template_directory() ) . 'lib/engine.php' );
new ExMachina();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'beta_theme_setup' );

if ( !function_exists( 'beta_theme_setup' ) ) {} // end if (!function_exists('beta_theme_setup'))

/**
 * Theme Setup Function
 *
 * This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 0.1.0
 * @access public
 * @return void
 */
function beta_theme_setup() {

  /* Get action/filter hook prefix. */
  $prefix = exmachina_get_prefix();

  /* Add theme support for core framework features. */
  add_theme_support( 'exmachina-core-menus', array( 'primary') );
  add_theme_support( 'exmachina-core-sidebars', array( 'primary' ) );
  add_theme_support( 'exmachina-core-scripts', array( 'comment-reply' ) );
  add_theme_support( 'exmachina-core-styles', array( '25px', 'gallery', 'parent', 'style' ) );
  add_theme_support( 'exmachina-core-theme-settings', array( 'updates', 'feeds', 'breadcrumbs', 'archives', 'comments', 'scripts', 'footer', 'about', 'help' ) );
  add_theme_support( 'exmachina-core-shortcodes' );
  add_theme_support( 'exmachina-core-template-hierarchy' );
  add_theme_support( 'exmachina-core-deprecated' );

  /* Enable theme layouts (need to add stylesheet support). */
  add_theme_support(
    'theme-layouts',
    array( '1c', '2c-l', '2c-r' ),
    array( 'default' => '2c-l', 'customizer' => true )
  );

  /* Add theme support for framework extensions. */
  add_theme_support( 'get-the-image' );
  add_theme_support( 'loop-pagination' );
  add_theme_support( 'cleaner-caption' );
  add_theme_support( 'footer-widgets', 3 );
  add_theme_support( 'structural-wraps' );
  add_theme_support( 'custom-css' );
  //add_theme_support( 'custom-footer' );
  add_theme_support( 'custom-logo' );
  add_theme_support( 'responsive' );

  /* Add theme support for WordPress features. */
  add_theme_support( 'automatic-feed-links' );
  add_editor_style();

  /* Set content width. */
  exmachina_set_content_width( 640 );

} // end function beta_theme_setup()
