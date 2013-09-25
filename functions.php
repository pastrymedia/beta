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

if ( ! function_exists( 'beta_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function beta_theme_setup() {

  /* Get the theme prefix. */
  $prefix = exmachina_get_prefix();

  /* The best thumbnail/image script ever. */
  add_theme_support( 'get-the-image' );

  /* Register menus. */
  add_theme_support(
    'exmachina-core-menus',
    array( 'primary')
  );

  /* Register sidebars. */
  add_theme_support(
    'exmachina-core-sidebars',
    array( 'primary' )
  );

  /* Load scripts. */
  add_theme_support(
    'exmachina-core-scripts',
    array( 'comment-reply' )
  );

  /* Load styles. */
  add_theme_support(
    'exmachina-core-styles',
    array( '25px', 'gallery', 'parent', 'style' )
  );

  /* Load shortcodes. */
  add_theme_support( 'exmachina-core-shortcodes' );

  add_theme_support( 'exmachina-core-theme-settings', array( 'about' ) );

  /* Enable custom template hierarchy. */
  add_theme_support( 'exmachina-core-template-hierarchy' );


  /* Enable theme layouts (need to add stylesheet support). */
  add_theme_support(
    'theme-layouts',
    array( '1c', '2c-l', '2c-r' ),
    array( 'default' => '2c-l', 'customizer' => true )
  );

  /* implement editor styling, so as to make the editor content match the resulting post output in the theme. */
  add_editor_style();

  /* Support pagination instead of prev/next links. */
  add_theme_support( 'loop-pagination' );

  /* Better captions for themes to style. */
  add_theme_support( 'cleaner-caption' );

  /* Add default posts and comments RSS feed links to <head>.  */
  add_theme_support( 'automatic-feed-links' );

  /* Enable footer widgets. */
  add_theme_support( 'footer-widgets', 3 );

  /* Enable wraps */
  add_theme_support( 'structural-wraps' );

  /* Enable custom css */
  add_theme_support( 'custom-css' );

  /* Enable custom footer */
  add_theme_support( 'custom-footer' );

  /* Enable custom logo */
  add_theme_support( 'custom-logo' );

  /* Enable responsive support */
  add_theme_support( 'responsive' );

  /* Handle content width for embeds and images. */
  exmachina_set_content_width( 640 );


}
endif; // beta_theme_setup

add_action( 'after_setup_theme', 'beta_theme_setup' );


