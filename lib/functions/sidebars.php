<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Sidebar Functions
 *
 * sidebars.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Sets up the default framework sidebars if the theme supports them. By default,
 * the framework registers seven sidebars. Themes may choose to use one or more
 * of these sidebars. A theme must register support for 'exmachina-core-sidebars'
 * to use them and register each sidebar ID within an array for the second
 * parameter of add_theme_support().
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

/* Register widget areas. */
add_action( 'widgets_init', 'exmachina_register_sidebars' );

/**
 * Register Sidebar
 *
 * Expedites the widget area registration process by taking common things,
 * before/after_widget, before/after_title, and doing them automatically.
 *
 * See the WP function 'register_sidebar()'' for the list of supports $args
 * keys. A typical usage is:
 *
 * ~~~
 * exmachina_register_sidebar(
 *     array(
 *         'id'          => 'my-sidebar',
 *         'name'        => __( 'My Sidebar', 'my-theme-text-domain' ),
 *         'description' => __( 'A description of the intended purpose or location', 'my-theme-text-domain' ),
 *     )
 * );
 * ~~~
 *
 * @since 1.6.0
 * @access public
 *
 * @param  string|array $args Name, ID, description and other widget area arguments.
 * @return string             The sidebar ID that was added.
 */
function exmachina_register_sidebar( $args ) {

  $defaults = (array) apply_filters(
    exmachina_get_prefix() . '_register_sidebar_defaults',
    array(
      'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-wrap">',
      'after_widget'  => '</div></section>' . "\n",
      'before_title'  => '<h4 class="widget-title widgettitle">',
      'after_title'   => "</h4>\n",
    ),
    $args
  );

  $args = wp_parse_args( $args, $defaults );

  return register_sidebar( $args );

} // end function exmachina_register_sidebar()

/**
 * Register Sidebars
 *
 * Registers the default framework dynamic sidebars based on the sidebars the
 * theme has added support for using add_theme_support().
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 * @link http://codex.wordpress.org/Function_Reference/get_theme_support
 * @link http://codex.wordpress.org/Function_Reference/wp_parse_args
 * @link http://codex.wordpress.org/Function_Reference/sanitize_key
 *
 * @uses exmachina_get_sidebars() Gets the array of available sidebars.
 * @uses exmachina_get_prefix()   Gets the theme prefix.
 *
 * @since 1.4.1
 * @access public
 *
 * @return void
 */
function exmachina_register_sidebars() {

  /* Get the theme-supported sidebars. */
  $supported_sidebars = get_theme_support( 'exmachina-core-sidebars' );

  /* If the theme doesn't add support for any sidebars, return. */
  if ( !is_array( $supported_sidebars[0] ) )
    return;

  /* Get the available core framework sidebars. */
  $core_sidebars = exmachina_get_sidebars();

  /* Loop through the supported sidebars. */
  foreach ( $supported_sidebars[0] as $sidebar ) {

    /* Make sure the given sidebar is one of the core sidebars. */
    if ( isset( $core_sidebars[ $sidebar ] ) ) {

      /* Set up some default sidebar arguments. */
      $defaults = array(
        'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap">',
        'after_widget'  => '</div></section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
      );

      /* Allow developers to filter the default sidebar arguments. */
      $defaults = apply_filters( exmachina_get_prefix() . '_sidebar_defaults', $defaults, $sidebar );

      /* Parse the sidebar arguments and defaults. */
      $args = wp_parse_args( $core_sidebars[ $sidebar ], $defaults );

      /* If no 'id' was given, use the $sidebar variable and sanitize it. */
      $args['id'] = ( isset( $args['id'] ) ? sanitize_key( $args['id'] ) : sanitize_key( $sidebar ) );

      /* Allow developers to filter the sidebar arguments. */
      $args = apply_filters( exmachina_get_prefix() . '_sidebar_args', $args, $sidebar );

      /* Register the sidebar. */
      register_sidebar( $args );
    }
  }

} // end function exmachina_register_sidebars()

/**
 * Get Sidebars
 *
 * Returns an array of the core framework's available sidebars for use in
 * themes. We'll just set the ID (array keys), name, and description of each
 * sidebar. The other sidebar arguments will be set when the sidebar is
 * registered.
 *
 * @since 1.4.1
 * @access public
 *
 * @return array $sidebars The available framework sidebars.
 */
function exmachina_get_sidebars() {

  /* Set up an array of sidebars. */
  $sidebars = array(
    'primary' => array(
      'name'        => _x( 'Primary', 'sidebar', 'exmachina-core' ),
      'description' => __( 'The main (primary) widget area, most often used as a sidebar.', 'exmachina-core' )
    ),
    'secondary' => array(
      'name'        => _x( 'Secondary', 'sidebar', 'exmachina-core' ),
      'description' => __( 'The second most important widget area, most often used as a secondary sidebar.', 'exmachina-core' ),
    ),
    'subsidiary' => array(
      'name'        => _x( 'Subsidiary', 'sidebar', 'exmachina-core' ),
      'description' => __( 'A widget area loaded in the footer of the site.', 'exmachina-core' ),
    ),
    'header' => array(
      'name'        => _x( 'Header', 'sidebar', 'exmachina-core' ),
      'description' => __( "Displayed within the site's header area.", 'exmachina-core' ),
    ),
    'before-content' => array(
      'name'        => _x( 'Before Content', 'sidebar', 'exmachina-core' ),
      'description' => __( "Loaded before the page's main content area.", 'exmachina-core' ),
    ),
    'after-content' => array(
      'name'        => _x( 'After Content', 'sidebar', 'exmachina-core' ),
      'description' => __( "Loaded after the page's main content area.", 'exmachina-core' ),
    ),
    'after-singular' => array(
      'name'        => _x( 'After Singular', 'sidebar', 'exmachina-core' ),
      'description' => __( 'Loaded on singular post (page, attachment, etc.) views before the comments area.', 'exmachina-core' ),
    )
  );

  /* Return the sidebars. */
  return $sidebars;

} // end function exmachina_get_sidebars()

/**
 * Conditionally display a sidebar, wrapped in a div by default.
 *
 * The $args array accepts the following keys:
 *
 *  - `before` (markup to be displayed before the widget area output),
 *  - `after` (markup to be displayed after the widget area output),
 *  - `default` (fallback text if the sidebar is not found, or has no widgets, default is an empty string),
 *  - `show_inactive` (flag to show inactive sidebars, default is false),
 *  - `before_sidebar_hook` (hook that fires before the widget area output),
 *  - `after_sidebar_hook` (hook that fires after the widget area output).
 *
 * Return false early if the sidebar is not active and the `show_inactive` argument is false.
 *
 * @since 1.6.0
 *
 * @param string $id   Sidebar ID, as per when it was registered
 * @param array  $args Arguments.
 *
 * @return boolean False if $args['show_inactive'] set to false and sidebar is not currently being used. True otherwise.
 */
function exmachina_widget_area( $id, $args = array() ) {

  if ( ! $id )
    return false;

  $args = wp_parse_args(
    $args,
    array(
      'before'              => '<aside class="widget-area">',
      'after'               => '</aside>',
      'default'             => '',
      'show_inactive'       => 0,
      'before_sidebar_hook' => exmachina_get_prefix() . '_before_' . $id . '_widget_area',
      'after_sidebar_hook'  => exmachina_get_prefix() . '_after_' . $id . '_widget_area',
    )
  );

  if ( ! is_active_sidebar( $id ) && ! $args['show_inactive'] )
    return false;

  //* Opening markup
  echo $args['before'];

  //* Before hook
  if ( $args['before_sidebar_hook'] )
      do_action( $args['before_sidebar_hook'] );

  if ( ! dynamic_sidebar( $id ) )
    echo $args['default'];

  //* After hook
  if( $args['after_sidebar_hook'] )
      do_action( $args['after_sidebar_hook'] );

  //* Closing markup
  echo $args['after'];

  return true;

}