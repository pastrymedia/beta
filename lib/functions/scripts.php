<?php
/**
 * Functions for handling JavaScript in the framework.  Themes can add support for the 
 * 'exmachina-core-scripts' feature to allow the framework to handle loading the stylesheets into 
 * the theme header or footer at an appropriate time.
 *
 * @package    ExMachinaCore
 * @subpackage Functions
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2013, Justin Tadlock
 * @link       http://themeexmachina.com/exmachina-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Register ExMachina Core scripts. */
add_action( 'wp_enqueue_scripts', 'exmachina_register_scripts', 1 );

/* Load ExMachina Core scripts. */
add_action( 'wp_enqueue_scripts', 'exmachina_enqueue_scripts' );

/**
 * Registers JavaScript files for the framework.  This function merely registers scripts with WordPress using
 * the wp_register_script() function.  It does not load any script files on the site.  If a theme wants to register 
 * its own custom scripts, it should do so on the 'wp_enqueue_scripts' hook.
 *
 * @since 1.2.0
 * @access private
 * @return void
 */
function exmachina_register_scripts() {

	/* Supported JavaScript. */
	$supports = get_theme_support( 'exmachina-core-scripts' );

	/* Use the .min script if SCRIPT_DEBUG is turned off. */
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	/* Register the 'drop-downs' script if the current theme supports 'drop-downs'. */
	if ( isset( $supports[0] ) && in_array( 'drop-downs', $supports[0] ) )
		wp_register_script( 'drop-downs', esc_url( apply_atomic( 'drop_downs_script', trailingslashit( EXMACHINA_JS ) . "drop-downs{$suffix}.js" ) ), array( 'jquery' ), '20130805', true );

	/* Register the 'nav-bar' script if the current theme supports 'nav-bar'. */
	if ( isset( $supports[0] ) && in_array( 'nav-bar', $supports[0] ) )
		wp_register_script( 'nav-bar', esc_url( apply_atomic( 'nav_bar_script', trailingslashit( EXMACHINA_JS ) . "nav-bar{$suffix}.js" ) ), array( 'jquery' ), '20130805', true );

	/* Register the 'mobile-toggle' script if the current theme supports 'mobile-toggle'. */
	if ( isset( $supports[0] ) && in_array( 'mobile-toggle', $supports[0] ) )
		wp_register_script( 'mobile-toggle', esc_url( trailingslashit( EXMACHINA_JS ) . "mobile-toggle{$suffix}.js" ), array( 'jquery' ), '20130528', true );
}

/**
 * Tells WordPress to load the scripts needed for the framework using the wp_enqueue_script() function.
 *
 * @since 1.2.0
 * @access private
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
}

?>