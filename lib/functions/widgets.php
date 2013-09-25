<?php
/**
 * Sets up the core framework's widgets and unregisters some of the default WordPress widgets if the
 * theme supports this feature.  The framework's widgets are meant to extend the default WordPress
 * widgets by giving users highly-customizable widget settings.  A theme must register support for the
 * 'exmachina-core-widgets' feature to use the framework widgets.
 *
 * @package    ExMachinaCore
 * @subpackage Functions
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2013, Justin Tadlock
 * @link       http://themeexmachina.com/exmachina-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Unregister WP widgets. */
add_action( 'widgets_init', 'exmachina_unregister_widgets' );

/* Register ExMachina widgets. */
add_action( 'widgets_init', 'exmachina_register_widgets' );

/**
 * Registers the core frameworks widgets.  These widgets typically overwrite the equivalent default WordPress
 * widget by extending the available options of the widget.
 *
 * @since 0.6.0
 * @access public
 * @uses register_widget() Registers individual widgets with WordPress
 * @link http://codex.wordpress.org/Function_Reference/register_widget
 * @return void
 */
function exmachina_register_widgets() {

	/* Load the archives widget class. */
	require_once( trailingslashit( EXMACHINA_WIDGETS ) . 'widget-archives.php' );

	/* Load the authors widget class. */
	require_once( trailingslashit( EXMACHINA_WIDGETS ) . 'widget-authors.php' );

	/* Load the bookmarks widget class. */
	if ( get_option( 'link_manager_enabled' ) )
		require_once( trailingslashit( EXMACHINA_WIDGETS ) . 'widget-bookmarks.php' );

	/* Load the calendar widget class. */
	require_once( trailingslashit( EXMACHINA_WIDGETS ) . 'widget-calendar.php' );

	/* Load the categories widget class. */
	require_once( trailingslashit( EXMACHINA_WIDGETS ) . 'widget-categories.php' );

	/* Load the nav menu widget class. */
	require_once( trailingslashit( EXMACHINA_WIDGETS ) . 'widget-nav-menu.php' );

	/* Load the pages widget class. */
	require_once( trailingslashit( EXMACHINA_WIDGETS ) . 'widget-pages.php' );

	/* Load the search widget class. */
	require_once( trailingslashit( EXMACHINA_WIDGETS ) . 'widget-search.php' );

	/* Load the tags widget class. */
	require_once( trailingslashit( EXMACHINA_WIDGETS ) . 'widget-tags.php' );

	/* Register the archives widget. */
	register_widget( 'ExMachina_Widget_Archives' );

	/* Register the authors widget. */
	register_widget( 'ExMachina_Widget_Authors' );

	/* Register the bookmarks widget. */
	if ( get_option( 'link_manager_enabled' ) )
		register_widget( 'ExMachina_Widget_Bookmarks' );

	/* Register the calendar widget. */
	register_widget( 'ExMachina_Widget_Calendar' );

	/* Register the categories widget. */
	register_widget( 'ExMachina_Widget_Categories' );

	/* Register the nav menu widget. */
	register_widget( 'ExMachina_Widget_Nav_Menu' );

	/* Register the pages widget. */
	register_widget( 'ExMachina_Widget_Pages' );

	/* Register the search widget. */
	register_widget( 'ExMachina_Widget_Search' );

	/* Register the tags widget. */
	register_widget( 'ExMachina_Widget_Tags' );
}

/**
 * Unregister default WordPress widgets that are replaced by the framework's widgets.  Widgets that
 * aren't replaced by the framework widgets are not unregistered.
 *
 * @since 0.3.2
 * @access public
 * @uses unregister_widget() Unregisters a registered widget.
 * @link http://codex.wordpress.org/Function_Reference/unregister_widget
 * @return void
 */
function exmachina_unregister_widgets() {

	/* Unregister the default WordPress widgets. */
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Nav_Menu_Widget' );
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
}

?>