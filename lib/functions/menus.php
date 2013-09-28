<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Menu Functions
 *
 * menus.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * The menus functions deal with registering nav menus within WordPress for the
 * core framework. Theme developers may use the default menu(s) provided by the
 * framework within their own themes, decide not to use them, or register
 * additional menus.
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

/* Register nav menus. */
add_action( 'init', 'exmachina_register_menus' );

/**
 * Register Menus
 *
 * Registers the the framework's default menus based on the menus the theme has
 * registered support for.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_nav_menu
 * @link http://codex.wordpress.org/Function_Reference/get_theme_support
 *
 * @since 1.3.1
 * @access private
 *
 * @return void
 */
function exmachina_register_menus() {

  /* Get theme-supported menus. */
  $menus = get_theme_support( 'exmachina-core-menus' );

  /* If there is no array of menus IDs, return. */
  if ( !is_array( $menus[0] ) )
    return;

  /* Register the 'primary' menu. */
  if ( in_array( 'primary', $menus[0] ) )
    register_nav_menu( 'primary', _x( 'Primary', 'nav menu location', 'exmachina-core' ) );

  /* Register the 'secondary' menu. */
  if ( in_array( 'secondary', $menus[0] ) )
    register_nav_menu( 'secondary', _x( 'Secondary', 'nav menu location', 'exmachina-core' ) );

  /* Register the 'subsidiary' menu. */
  if ( in_array( 'subsidiary', $menus[0] ) )
    register_nav_menu( 'subsidiary', _x( 'Subsidiary', 'nav menu location', 'exmachina-core' ) );

} // end function exmachina_register_menus()

/**
 * Determine if a child theme supports a particular nav menu.
 *
 * @since 1.6.0
 *
 * @param string $menu Name of the menu to check support for.
 *
 * @return boolean True if menu supported, false otherwise.
 */
function exmachina_nav_menu_supported( $menu ) {

  if ( ! current_theme_supports( 'exmachina-core-menus' ) )
    return false;

  $menus = get_theme_support( 'exmachina-core-menus' );

  if ( array_key_exists( $menu, (array) $menus[0] ) )
    return true;

  return false;

} // end function exmachina_nav_menu_supported()