<?php
/**
 * Functions for dealing with theme settings on both the front end of the site and the admin.  This allows us 
 * to set some default settings and make it easy for theme developers to quickly grab theme settings from 
 * the database.  This file is only loaded if the theme adds support for the 'exmachina-core-theme-settings' 
 * feature.
 *
 * @package    ExMachinaCore
 * @subpackage Functions
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2013, Justin Tadlock
 * @link       http://themeexmachina.com/exmachina-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Loads the ExMachina theme settings once and allows the input of the specific field the user would 
 * like to show.  ExMachina theme settings are added with 'autoload' set to 'yes', so the settings are 
 * only loaded once on each page load.
 *
 * @since 0.7.0
 * @access public
 * @uses get_option() Gets an option from the database.
 * @uses exmachina_get_prefix() Gets the prefix of the theme.
 * @global object $exmachina The global ExMachina object.
 * @param string $option The specific theme setting the user wants.
 * @return mixed $settings[$option] Specific setting asked for.
 */
function exmachina_get_setting( $option = '' ) {
	global $exmachina;

	/* If no specific option was requested, return false. */
	if ( !$option )
		return false;

	/* Get the default settings. */
	$defaults = exmachina_get_default_theme_settings();

	/* If the settings array hasn't been set, call get_option() to get an array of theme settings. */
	if ( !isset( $exmachina->settings ) || !is_array( $exmachina->settings ) )
		$exmachina->settings = get_option( exmachina_get_prefix() . '_theme_settings', $defaults );

	/* If the option isn't set but the default is, set the option to the default. */
	if ( !isset( $exmachina->settings[ $option ] ) && isset( $defaults[ $option ] ) )
		$exmachina->settings[ $option ] = $defaults[ $option ];

	/* If no option is found at this point, return false. */
	if ( !isset( $exmachina->settings[ $option ] ) )
		return false;

	/* If the specific option is an array, return it. */
	if ( is_array( $exmachina->settings[ $option ] ) )
		return $exmachina->settings[ $option ];

	/* Strip slashes from the setting and return. */
	else
		return wp_kses_stripslashes( $exmachina->settings[ $option ] );
}

/**
 * Sets up a default array of theme settings for use with the theme.  Theme developers should filter the 
 * "{$prefix}_default_theme_settings" hook to define any default theme settings.  WordPress does not 
 * provide a hook for default settings at this time.
 *
 * @since 1.0.0
 * @access public
 * @return array $settings The default theme settings.
 */
function exmachina_get_default_theme_settings() {

	/* Set up some default variables. */
	$settings = array();
	$prefix = exmachina_get_prefix();

	/* Get theme-supported meta boxes for the settings page. */
	$supports = get_theme_support( 'exmachina-core-theme-settings' );

	/* If the current theme supports the footer meta box and shortcodes, add default footer settings. */
	if ( is_array( $supports[0] ) && in_array( 'footer', $supports[0] ) && current_theme_supports( 'exmachina-core-shortcodes' ) ) {

		/* If there is a child theme active, add the [child-link] shortcode to the $footer_insert. */
		if ( is_child_theme() )
			$settings['footer_insert'] = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'exmachina-core' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link], [theme-link], and [child-link].', 'exmachina-core' ) . '</p>';

		/* If no child theme is active, leave out the [child-link] shortcode. */
		else
			$settings['footer_insert'] = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'exmachina-core' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link] and [theme-link].', 'exmachina-core' ) . '</p>';
	}

	/* Return the $settings array and provide a hook for overwriting the default settings. */
	return apply_filters( "{$prefix}_default_theme_settings", $settings );
}

?>