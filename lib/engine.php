<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * ExMachina Engine
 *
 * engine.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * The engine that powers the ExMachina framework. This file controls the load
 * order, theme features, plugins, and extensions. ExMachina is a modular system,
 * which means that features, plugins, and extensions are only loaded if they are
 * specifically included within the theme.
 *
 * @package     ExMachina
 * @version     1.0.0
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright (c) 2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com
 */
###############################################################################
# Start your engines...
###############################################################################

/**
 * ExMachina Class
 *
 * The ExMachina class launches the framework. This class should be loaded and
 * initialized before anything else within the theme is called.
 *
 * @since 0.8.1
 */
class ExMachina {

  /**
   * ExMachina Constructor
   *
   * Constructor method for the ExMachina class. This method adds other methods
   * of the class to specific hooks within WordPress. It controls the load order
   * of the required files for running the framework.
   *
   * @since 0.8.1
   */
  function __construct() {
    global $exmachina;

    /* Setup an empty class for the global $exmachina object. */
    $exmachina = new stdClass;

    /* Trigger the 'exmachina_pre' action hook. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_pre_hook' ), 1 );

    /* Define framework, parent theme, and child theme constants. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_constants' ), 2 );

    /* Load the core functions required by the rest of the framework. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_load_core' ), 3 );

    /* Define the settings fields constants (for database storage). */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_settings_fields' ), 4 );

    /* Initialize the framework's default actions and filters. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_default_filters' ), 5 );

    /* Language functions and translations setup. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_i18n' ), 6 );

    /* Theme setup fires here. */

    /* Trigger the 'exmachina_init' action hook. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_init_hook' ), 12 );

    /* Handle theme supported features. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_theme_support' ), 13 );

    /* Handle post type supported features. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_post_type_support' ), 14 );

    /* Load the framework classes. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_load_classes' ), 15 );

    /* Load the framework functions. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_load_framework' ), 16 );

    /* Load the structure functions. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_load_structure' ), 17 );

    /* Load the framework extensions. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_load_extensions' ), 18 );

    /* Load the admin files. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_load_admin' ), 19 );

    /* Trigger the 'exmachina_setup' action hook. */
    add_action( 'after_setup_theme', array( &$this, 'exmachina_setup_hook' ), 20 );

  } // end function __construct()

  /**
   * ExMachina Pre Action Hook
   *
   * This action hook is triggered before any of the core framework is loaded.
   *
   * @since 0.8.1
   */
  function exmachina_pre_hook() {

    /* Trigger the 'exmachina_pre' action hook. */
    do_action( 'exmachina_pre' );

  } // end function exmachina_pre_hook()

  /**
   * Framework Constants
   *
   * Defines the constant paths for use within the core framework, parent theme,
   * and child theme. Constants prefixed with 'EXMACHINA_' are for use only within
   * the core framework and don't reference other areas of the parent or child theme.
   *
   * @since 0.8.1
   */
  function exmachina_constants() {

    /* Sets the framework version numbers. */
    define( 'EXMACHINA_VERSION', '1.6.0' );
    define( 'EXMACHINA_DB_VERSION', '1600' );
    define( 'EXMACHINA_RELEASE_DATE', date_i18n( 'F j, Y', '1377061200' ) );

    /* Sets the parent theme location constants. */
    define( 'THEME_DIR', get_template_directory() );
    define( 'THEME_URI', get_template_directory_uri() );

    /* Sets the child theme location constants. */
    define( 'CHILD_THEME_DIR', get_stylesheet_directory() );
    define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );

    /* Sets the core framework location constants. */
    define( 'EXMACHINA_DIR', trailingslashit( THEME_DIR ) . basename( dirname( __FILE__ ) ) );
    define( 'EXMACHINA_URI', trailingslashit( THEME_URI ) . basename( dirname( __FILE__ ) ) );

    /* Define the framework directory location constants. */
    define( 'EXMACHINA_ADMIN', trailingslashit( EXMACHINA_DIR ) . 'admin' );
    define( 'EXMACHINA_ASSETS', trailingslashit( EXMACHINA_DIR ) . 'assets' );
    define( 'EXMACHINA_CLASSES', trailingslashit( EXMACHINA_DIR ) . 'classes' );
    define( 'EXMACHINA_CONNECT', trailingslashit( EXMACHINA_DIR ) . 'connect' );
    define( 'EXMACHINA_EXTENSIONS', trailingslashit( EXMACHINA_DIR ) . 'extensions' );
    define( 'EXMACHINA_FRAMEWORK', trailingslashit( EXMACHINA_DIR ) . 'framework' );
    define( 'EXMACHINA_FUNCTIONS', trailingslashit( EXMACHINA_DIR ) . 'functions' );
    define( 'EXMACHINA_LANGUAGES', trailingslashit( EXMACHINA_DIR ) . 'languages' );
    define( 'EXMACHINA_PLUGINS', trailingslashit( EXMACHINA_DIR ) . 'plugins' );
    define( 'EXMACHINA_STRUCTURE', trailingslashit( EXMACHINA_DIR ) . 'structure' );
    define( 'EXMACHINA_WIDGETS', trailingslashit( EXMACHINA_DIR ) . 'widgets' );

    /* Define the framework url location constants. */
    define( 'EXMACHINA_ADMIN_URL', trailingslashit( EXMACHINA_URI ) . 'admin' );
    define( 'EXMACHINA_ASSETS_URL', trailingslashit( EXMACHINA_URI ) . 'assets' );
    define( 'EXMACHINA_CLASSES_URL', trailingslashit( EXMACHINA_URI ) . 'classes' );
    define( 'EXMACHINA_CONNECT_URL', trailingslashit( EXMACHINA_URI ) . 'connect' );
    define( 'EXMACHINA_EXTENSIONS_URL', trailingslashit( EXMACHINA_URI ) . 'extensions' );
    define( 'EXMACHINA_FRAMEWORK_URL', trailingslashit( EXMACHINA_URI ) . 'framework' );
    define( 'EXMACHINA_FUNCTIONS_URL', trailingslashit( EXMACHINA_URI ) . 'functions' );
    define( 'EXMACHINA_LANGUAGES_URL', trailingslashit( EXMACHINA_URI ) . 'languages' );
    define( 'EXMACHINA_PLUGINS_URL', trailingslashit( EXMACHINA_URI ) . 'plugins' );
    define( 'EXMACHINA_STRUCTURE_URL', trailingslashit( EXMACHINA_URI ) . 'structure' );
    define( 'EXMACHINA_WIDGETS_URL', trailingslashit( EXMACHINA_URI ) . 'widgets' );

    /* Sets the assets directory location URL constants. */
    define( 'EXMACHINA_IMAGES', trailingslashit( EXMACHINA_ASSETS_URL ) . 'images' );
    define( 'EXMACHINA_CSS', trailingslashit( EXMACHINA_ASSETS_URL ) . 'css' );
    define( 'EXMACHINA_JS', trailingslashit( EXMACHINA_ASSETS_URL ) . 'js' );
    define( 'EXMACHINA_VENDOR', trailingslashit( EXMACHINA_ASSETS_URL ) . 'vendor' );

    /* Define the admin directory location constants. */
    define( 'EXMACHINA_ADMIN_ASSETS', trailingslashit( EXMACHINA_ADMIN_URL ) . 'assets' );
    define( 'EXMACHINA_ADMIN_FUNCTIONS', trailingslashit( EXMACHINA_ADMIN ) . 'functions' );
    define( 'EXMACHINA_ADMIN_OPTIONS', trailingslashit( EXMACHINA_ADMIN ) . 'options' );

    /* Sets the admin assets directory location URL constants. */
    define( 'EXMACHINA_ADMIN_IMAGES', trailingslashit( EXMACHINA_ADMIN_ASSETS ) . 'images' );
    define( 'EXMACHINA_ADMIN_CSS', trailingslashit( EXMACHINA_ADMIN_ASSETS ) . 'css' );
    define( 'EXMACHINA_ADMIN_JS', trailingslashit( EXMACHINA_ADMIN_ASSETS ) . 'js' );
    define( 'EXMACHINA_ADMIN_VENDOR', trailingslashit( EXMACHINA_ADMIN_ASSETS ) . 'vendor' );

  } // end function exmachina_constants()

  /**
   * Load Core Functions
   *
   * Loads the core framework functions. These files are needed before loading
   * anything else in the framework.
   *
   * @since 0.8.1
   */
  function exmachina_load_core() {

    /* Load the core framework functions. */
    require_once( trailingslashit( EXMACHINA_FRAMEWORK ) . 'core.php' );

    /* Load the context-based functions. */
    require_once( trailingslashit( EXMACHINA_FRAMEWORK ) . 'context.php' );

    /* Load the core framework internationalization functions. */
    require_once( trailingslashit( EXMACHINA_FRAMEWORK ) . 'i18n.php' );

  } // end function exmachina_load_core()

  /**
   * Framework Settings Fields
   *
   * Defines the settings fields for options table storage within the database.
   *
   * @uses exmachina_get_prefix() Defines the theme prefix.
   *
   * @since 0.8.1
   */
  function exmachina_settings_fields() {
    global $exmachina;

    /* Get the theme prefix. */
    $prefix = exmachina_get_prefix();

    /* Define Settings Field Constants (for DB storage). */
    define( 'EXMACHINA_SETTINGS_FIELD', apply_filters( "{$prefix}_theme_settings_field", "{$prefix}-theme-settings" ) );
    define( 'EXMACHINA_SEO_SETTINGS_FIELD', apply_filters( "{$prefix}_seo_settings_field", "{$prefix}-seo-settings" ) );
    define( 'EXMACHINA_CPT_SETTINGS_FIELD', apply_filters( "{$prefix}_cpt_settings_field", "{$prefix}-cpt-settings" ) );
    define( 'EXMACHINA_HOOK_SETTINGS_FIELD', apply_filters( "{$prefix}_hook_settings_field", "{$prefix}-hook-settings" ) );
    define( 'EXMACHINA_DESIGN_SETTINGS_FIELD', apply_filters( "{$prefix}_design_settings_field", "{$prefix}-design-settings" ) );
    define( 'EXMACHINA_CONTENT_SETTINGS_FIELD', apply_filters( "{$prefix}_content_settings_field", "{$prefix}-content-settings" ) );

  } // end function exmachina_settings_fields()

  /**
   * Default Filters
   *
   * Adds the defaults framework actions and filters.
   *
   * @since 0.8.1
   */
  function exmachina_default_filters() {

    /* Remove bbPress theme compatibility if current theme supports bbPress. */
    if ( current_theme_supports( 'bbpress' ) )
      remove_action( 'bbp_init', 'bbp_setup_theme_compat', 8 );

    /* Move the WordPress generator to a better priority. */
    remove_action( 'wp_head', 'wp_generator' );
    add_action( 'wp_head', 'wp_generator', 1 );

    /* Add the theme info to the header (lets theme developers give better support). */
    //add_action( 'wp_head', 'exmachina_meta_template', 1 );

    /* Make text widgets and term descriptions shortcode aware. */
    add_filter( 'widget_text', 'do_shortcode' );
    add_filter( 'term_description', 'do_shortcode' );

  } // end function exmachina_default_filters()

  /**
   * Load Translation Files
   *
   * Loads both the parent and theme translation files. If a locale-based
   * functions file exists in either the parent or child theme (child overrides
   * parent), it will also be loaded.
   *
   * @link http://codex.wordpress.org/WordPress_in_Your_Language
   * @link http://codex.wordpress.org/Function_Reference/load_theme_textdomain
   * @link http://codex.wordpress.org/Function_Reference/is_child_theme
   * @link http://codex.wordpress.org/Function_Reference/load_child_theme_textdomain
   * @link http://codex.wordpress.org/Function_Reference/get_locale
   * @link http://codex.wordpress.org/Function_Reference/locate_template
   *
   * @uses exmachina_get_parent_textdomain()      Gets the parent textdomain.
   * @uses exmachina_get_child_textdomain()       Gets the child textdomain.
   * @uses exmachina_load_framework_textdomain()  Loads the framework textdomain.
   *
   * @global object $exmachina  The global ExMachina object.
   *
   * @since 0.8.1
   */
  function exmachina_i18n() {
    global $exmachina;

    /* Get parent and child theme textdomains. */
    $parent_textdomain = exmachina_get_parent_textdomain();
    $child_textdomain = exmachina_get_child_textdomain();

    /* Load the framework textdomain. */
    $exmachina->textdomain_loaded['exmachina-core'] = exmachina_load_framework_textdomain( 'exmachina-core' );

    /* Load theme textdomain. */
    $exmachina->textdomain_loaded[$parent_textdomain] = load_theme_textdomain( $parent_textdomain );

    /* Load child theme textdomain. */
    $exmachina->textdomain_loaded[$child_textdomain] = is_child_theme() ? load_child_theme_textdomain( $child_textdomain ) : false;

    /* Get the user's locale. */
    $locale = get_locale();

    /* Locate a locale-specific functions file. */
    $locale_functions = locate_template( array( "languages/{$locale}.php", "{$locale}.php" ) );

    /* If the locale file exists and is readable, load it. */
    if ( !empty( $locale_functions ) && is_readable( $locale_functions ) )
      require_once( $locale_functions );

  } // end function exmachina_i18n()

  /**
   * ExMachina Init Action Hook
   *
   * This action hook is triggered after the core framework is loaded, but
   * before any of the framework theme support, functions, extensions, or
   * admin files are loaded.
   *
   * @since 0.8.1
   */
  function exmachina_init_hook() {

    /* Trigger the 'exmachina_init' action hook. */
    do_action( 'exmachina_init' );

  } // end function exmachina_init_hook()

  /**
   * Framework Theme Support
   *
   * Activates default theme features and removes theme supported features in
   * the case that the user has a plugin installed that handles the functionality.
   *
   * @link http://codex.wordpress.org/Theme_Features
   * @link http://codex.wordpress.org/Function_Reference/add_theme_support
   * @link http://codex.wordpress.org/Function_Reference/remove_theme_support
   * @link http://codex.wordpress.org/Function_Reference/current_theme_supports
   *
   * @since 0.8.1
   */
  function exmachina_theme_support() {

    /* Add semantic markup HTML5 feature. */
    add_theme_support( 'html5' );

    /* Remove support for the core SEO component if the WP SEO plugin is installed. */
    if ( defined( 'WPSEO_VERSION' ) )
      remove_theme_support( 'exmachina-core-seo' );

    /* Remove support for the the Breadcrumb Trail extension if the plugin is installed. */
    if ( function_exists( 'breadcrumb_trail' ) )
      remove_theme_support( 'breadcrumb-trail' );

    /* Remove support for the the Cleaner Gallery extension if the plugin is installed. */
    if ( function_exists( 'cleaner_gallery' ) )
      remove_theme_support( 'cleaner-gallery' );

    /* Remove support for the the Get the Image extension if the plugin is installed. */
    if ( function_exists( 'get_the_image' ) )
      remove_theme_support( 'get-the-image' );

    /* Remove support for the Featured Header extension if the class exists. */
    if ( class_exists( 'Featured_Header' ) )
      remove_theme_support( 'featured-header' );

    /* Remove support for the Random Custom Background extension if the class exists. */
    if ( class_exists( 'Random_Custom_Background' ) )
      remove_theme_support( 'random-custom-background' );

  } // end function exmachina_theme_support()

  /**
   * Framework Post Type Support
   *
   * Initializes post type support for various post type features.
   *
   * @link http://codex.wordpress.org/Function_Reference/add_post_type_support
   * @link http://codex.wordpress.org/Function_Reference/remove_post_type_support
   * @link http://codex.wordpress.org/Function_Reference/post_type_supports
   *
   * @since 0.8.1
   */
  function exmachina_post_type_support() {} // end function exmachina_post_type_support()

  /**
   * Load Classes
   *
   * Loads all the class files and features. The exmachina_pre_classes
   * action hook is called before any of the files are required.
   *
   * If a parent or child theme defines EXMACHINA_LOAD_CLASSES as false before
   * requiring this engine.php file, then this function will abort before any
   * other files are loaded.
   *
   * @since 0.8.1
   */
  function exmachina_load_classes() {

    /* Triggers the 'exmachina_pre_classes' action hook. */
    do_action( 'exmachina_pre_classes' );

    /* Short circuits the framework if 'EXMACHINA_LOAD_CLASSES' is defined as false. */
    if ( defined( 'EXMACHINA_LOAD_CLASSES' ) && EXMACHINA_LOAD_CLASSES === false )
      return;

    /* Load the admin builder class. */
    require_once( trailingslashit( EXMACHINA_CLASSES ) . 'admin.class.php' );

    /* Load the settings sanitization class. */
    require_once( trailingslashit( EXMACHINA_CLASSES ) . 'sanitize.class.php' );

  } // end function exmachina_load_classes()

  /**
   * Load Framework
   *
   * Loads all the framework files and features. The 'exmachina_pre_framework'
   * action hook is called before any of the files are required. Many of these
   * functions are needed to properly run the framework. Some components are
   * only loaded if the theme supports them.
   *
   * If a parent or child theme defines 'EXMACHINA_LOAD_FRAMEWORK' as false
   * before requring this engine.php file, then this function will abort before
   * any other files are loaded.
   *
   * @since 0.8.1
   */
  function exmachina_load_framework() {

    /* Triggers the 'exmachina_pre_framework' action hook. */
    do_action( 'exmachina_pre_framework' );

    /* Short circuits the framework if 'EXMACHINA_LOAD_FRAMEWORK' is defined as false. */
    if ( defined( 'EXMACHINA_LOAD_FRAMEWORK' ) && EXMACHINA_LOAD_FRAMEWORK === false )
      return;

    /* Load the comments functions. */
    require_once( trailingslashit( EXMACHINA_FUNCTIONS ) . 'comments.php' );

    /* Load media-related functions. */
    require_once( trailingslashit( EXMACHINA_FUNCTIONS ) . 'media.php' );

    /* Load the metadata functions. */
    require_once( trailingslashit( EXMACHINA_FUNCTIONS ) . 'meta.php' );

    /* Load the template functions. */
    require_once( trailingslashit( EXMACHINA_FUNCTIONS ) . 'template.php' );

    /* Load the utility functions. */
    require_once( trailingslashit( EXMACHINA_FUNCTIONS ) . 'utility.php' );

    /* Load the theme settings functions if supported. */
    require_if_theme_supports( 'exmachina-core-theme-settings', trailingslashit( EXMACHINA_FUNCTIONS ) . 'settings.php' );

    /* Load the customizer functions if theme settings are supported. */
    require_if_theme_supports( 'exmachina-core-theme-settings', trailingslashit( EXMACHINA_FUNCTIONS ) . 'customize.php' );

    /* Load the menus functions if supported. */
    require_if_theme_supports( 'exmachina-core-menus', trailingslashit( EXMACHINA_FUNCTIONS ) . 'menus.php' );

    /* Load the core SEO component if supported. */
    require_if_theme_supports( 'exmachina-core-seo', trailingslashit( EXMACHINA_FUNCTIONS ) . 'core-seo.php' );

    /* Load the shortcodes if supported. */
    require_if_theme_supports( 'exmachina-core-shortcodes', trailingslashit( EXMACHINA_FUNCTIONS ) . 'shortcodes.php' );

    /* Load the sidebars if supported. */
    require_if_theme_supports( 'exmachina-core-sidebars', trailingslashit( EXMACHINA_FUNCTIONS ) . 'sidebars.php' );

    /* Load the widgets if supported. */
    require_if_theme_supports( 'exmachina-core-widgets', trailingslashit( EXMACHINA_FUNCTIONS ) . 'widgets.php' );

    /* Load the template hierarchy if supported. */
    require_if_theme_supports( 'exmachina-core-template-hierarchy', trailingslashit( EXMACHINA_FUNCTIONS ) . 'template-hierarchy.php' );

    /* Load the styles if supported. */
    require_if_theme_supports( 'exmachina-core-styles', trailingslashit( EXMACHINA_FUNCTIONS ) . 'styles.php' );

    /* Load the scripts if supported. */
    require_if_theme_supports( 'exmachina-core-scripts', trailingslashit( EXMACHINA_FUNCTIONS ) . 'scripts.php' );

    /* Load the media grabber script if supported. */
    require_if_theme_supports( 'exmachina-core-media-grabber', trailingslashit( EXMACHINA_CLASSES ) . 'media-grabber.php' );

    /* Load the post format functionality if post formats are supported. */
    require_if_theme_supports( 'post-formats', trailingslashit( EXMACHINA_FUNCTIONS ) . 'post-formats.php' );

    /* Load the deprecated functions if supported. */
    require_if_theme_supports( 'exmachina-core-deprecated', trailingslashit( EXMACHINA_FUNCTIONS ) . 'deprecated.php' );

  } // end function exmachina_load_framework()

  /**
   * Load Structure
   *
   * Loads the framework markup structure. The 'exmachina_pre_structure' action
   * hook is called before any of the structure files are required.
   *
   * If a parent or child theme defines 'EXMACHINA_LOAD_STRUCTURE' as false
   * before requring this engine.php file, then this function will abort before
   * any structure files are loaded.
   *
   * @since 0.8.1
   */
  function exmachina_load_structure() {

    /* Triggers the 'exmachina_pre_structure' action hook. */
    do_action( 'exmachina_pre_structure' );

    /* Short circuits the framework if 'EXMACHINA_LOAD_FRAMEWORK' is defined as false. */
    if ( defined( 'EXMACHINA_LOAD_FRAMEWORK' ) && EXMACHINA_LOAD_FRAMEWORK === false )
      return;

    /* Short circuits the framework if 'EXMACHINA_LOAD_STRUCTURE' is defined as false. */
    if ( defined( 'EXMACHINA_LOAD_STRUCTURE' ) && EXMACHINA_LOAD_STRUCTURE === false )
      return;

    /* Load the archive structure template. */
    require_once( trailingslashit( EXMACHINA_STRUCTURE ) . 'archive.php' );

    /* Load the comments structure template. */
    require_once( trailingslashit( EXMACHINA_STRUCTURE ) . 'comments.php' );

    /* Load the footer structure template. */
    require_once( trailingslashit( EXMACHINA_STRUCTURE ) . 'footer.php' );

    /* Load the header structure template. */
    require_once( trailingslashit( EXMACHINA_STRUCTURE ) . 'header.php' );

    /* Load the layout structure template. */
    require_once( trailingslashit( EXMACHINA_STRUCTURE ) . 'layout.php' );

    /* Load the loops structure template. */
    require_once( trailingslashit( EXMACHINA_STRUCTURE ) . 'loops.php' );

    /* Load the menu structure template. */
    require_once( trailingslashit( EXMACHINA_STRUCTURE ) . 'menu.php' );

    /* Load the post structure template. */
    require_once( trailingslashit( EXMACHINA_STRUCTURE ) . 'post.php' );

    /* Load the search structure template. */
    require_once( trailingslashit( EXMACHINA_STRUCTURE ) . 'search.php' );

    /* Load the sidebar structure template. */
    require_once( trailingslashit( EXMACHINA_STRUCTURE ) . 'sidebar.php' );

  } // end function exmachina_load_structure()

  /**
   * Load Extensions
   *
   * Loads the framework extensions. The 'exmachina_pre_extensions' action hook
   * is called before any of the extension files are required.
   *
   * Extensions are projects that are included within the framework but are not
   * neccessarily a part of it. They are external projects developed outside of
   * the framework. Themes must use 'add_theme_support( $extension )' to use a
   * specific extenstion within the theme.
   *
   * If a parent or child theme defines 'EXMACHINA_LOAD_EXTENSIONS' as false
   * before requring this engine.php file, then this function will abort before
   * any extension files are loaded.
   *
   * @since 0.8.1
   */
  function exmachina_load_extensions() {

    /* Triggers the 'exmachina_pre_extensions' action hook. */
    do_action( 'exmachina_pre_extensions' );

    /* Short circuits the framework if 'EXMACHINA_LOAD_FRAMEWORK' is defined as false. */
    if ( defined( 'EXMACHINA_LOAD_FRAMEWORK' ) && EXMACHINA_LOAD_FRAMEWORK === false )
      return;

    /* Short circuits the framework if 'EXMACHINA_LOAD_EXTENSIONS' is defined as false. */
    if ( defined( 'EXMACHINA_LOAD_EXTENSIONS' ) && EXMACHINA_LOAD_EXTENSIONS === false )
      return;

    /* Load the Breadcrumb Trail extension if supported. */
    require_if_theme_supports( 'breadcrumb-trail', trailingslashit( EXMACHINA_EXTENSIONS ) . 'breadcrumb-trail.php' );

    /* Load the Cleaner Gallery extension if supported. */
    require_if_theme_supports( 'cleaner-gallery', trailingslashit( EXMACHINA_EXTENSIONS ) . 'cleaner-gallery.php' );

    /* Load the Get the Image extension if supported. */
    require_if_theme_supports( 'get-the-image', trailingslashit( EXMACHINA_EXTENSIONS ) . 'get-the-image.php' );

    /* Load the Cleaner Caption extension if supported. */
    require_if_theme_supports( 'cleaner-caption', trailingslashit( EXMACHINA_EXTENSIONS ) . 'cleaner-caption.php' );

    /* Load the Custom Field Series extension if supported. */
    require_if_theme_supports( 'custom-field-series', trailingslashit( EXMACHINA_EXTENSIONS ) . 'custom-field-series.php' );

    /* Load the Loop Pagination extension if supported. */
    require_if_theme_supports( 'loop-pagination', trailingslashit( EXMACHINA_EXTENSIONS ) . 'loop-pagination.php' );

    /* Load the Entry Views extension if supported. */
    require_if_theme_supports( 'entry-views', trailingslashit( EXMACHINA_EXTENSIONS ) . 'entry-views.php' );

    /* Load the Theme Layouts extension if supported. */
    require_if_theme_supports( 'theme-layouts', trailingslashit( EXMACHINA_EXTENSIONS ) . 'theme-layouts.php' );

    /* Load the Post Stylesheets extension if supported. */
    require_if_theme_supports( 'post-stylesheets', trailingslashit( EXMACHINA_EXTENSIONS ) . 'post-stylesheets.php' );

    /* Load the Featured Header extension if supported. */
    require_if_theme_supports( 'featured-header', trailingslashit( EXMACHINA_EXTENSIONS ) . 'featured-header.php' );

    /* Load the Random Custom Background extension if supported. */
    require_if_theme_supports( 'random-custom-background', trailingslashit( EXMACHINA_EXTENSIONS ) . 'random-custom-background.php' );

    /* Load the Color Palette extension if supported. */
    require_if_theme_supports( 'color-palette', trailingslashit( EXMACHINA_EXTENSIONS ) . 'color-palette.php' );

    /* Load the Theme Fonts extension if supported. */
    require_if_theme_supports( 'theme-fonts', trailingslashit( EXMACHINA_EXTENSIONS ) . 'theme-fonts.php' );

    /* Load the Footer Widgets extension if supported. */
    require_if_theme_supports( 'footer-widgets', trailingslashit( EXMACHINA_EXTENSIONS ) . 'footer-widgets.php' );

    /* Load the Structural Wraps extension if supported. */
    require_if_theme_supports( 'structural-wraps', trailingslashit( EXMACHINA_EXTENSIONS ) . 'wraps.php' );

    /* Load the Responsive extension if supported. */
    require_if_theme_supports( 'responsive', trailingslashit( EXMACHINA_EXTENSIONS ) . 'responsive.php' );

    /* Load the Custom CSS extension if supported. */
    require_if_theme_supports( 'custom-css', trailingslashit( EXMACHINA_EXTENSIONS ) . 'custom-css.php' );

    /* Load the Custom Logo extension if supported. */
    require_if_theme_supports( 'custom-logo', trailingslashit( EXMACHINA_EXTENSIONS ) . 'custom-logo.php' );

    /* Load the Custom Footer extension if supported. */
    require_if_theme_supports( 'custom-footer', trailingslashit( EXMACHINA_EXTENSIONS ) . 'custom-footer.php' );

  } // end function exmachina_load_extensions()

  /**
   * Load Admin
   *
   * Loads all the admin files for the framework. The 'exmachina_pre_admin' action
   * hook is called before any of the files are required.
   *
   * If a parent or child theme defines 'EXMACHINA_LOAD_ADMIN' as false before
   * requiring this engine.php file, then this function will abort before any
   * other admin files are loaded.
   *
   * @since 0.1.2
   */
  function exmachina_load_admin() {

    /* Triggers the 'exmachina_pre_admin' action hook. */
    do_action( 'exmachina_pre_admin' );

    /* Short circuits the framework if 'EXMACHINA_LOAD_FRAMEWORK' is defined as false. */
    if ( defined( 'EXMACHINA_LOAD_FRAMEWORK' ) && EXMACHINA_LOAD_FRAMEWORK === false )
      return;

    /* Short circuits the framework if 'EXMACHINA_LOAD_ADMIN' is defined as false. */
    if ( defined( 'EXMACHINA_LOAD_ADMIN' ) && EXMACHINA_LOAD_ADMIN === false )
      return;

    /* Check if in the WordPress admin. */
    if ( is_admin() ) {

      /* Load the main admin file. */
      require_once( trailingslashit( EXMACHINA_ADMIN_FUNCTIONS ) . 'admin.php' );

      /* Load the theme settings feature if supported. */
      require_if_theme_supports( 'exmachina-core-theme-settings', trailingslashit( EXMACHINA_ADMIN_FUNCTIONS ) . 'theme-settings.php' );

    } // end if (is_admin())

  } // end function exmachina_load_admin()

  /**
   * Exmachina Setup Action Hook
   *
   * This action hook is triggered after all of the core framework is loaded.
   *
   * @since 0.1.2
   */
  function exmachina_setup_hook() {

    /* Trigger the 'exmachina_setup' action hook. */
    do_action( 'exmachina_setup' );

  } // end function exmachina_setup_hook()

} // end class ExMachina