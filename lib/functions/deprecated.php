<?php
/**
 * Deprecated functions that should be avoided in favor of newer functions. Also handles removed 
 * functions to avoid errors. Developers should not use these functions in their parent themes and users 
 * should not use these functions in their child themes.  The functions below will all be removed at some 
 * point in a future release.  If your theme is using one of these, you should use the listed alternative or 
 * remove it from your theme if necessary.
 *
 * @package    ExMachinaCore
 * @subpackage Functions
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2013, Justin Tadlock
 * @link       http://themeexmachina.com/exmachina-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * @since 0.2.0
 * @deprecated 0.7.0
 */
function exmachina_after_single() {
	_deprecated_function( __FUNCTION__, '0.7', "do_atomic( 'after_singular' )" );
	exmachina_after_singular();
}

/**
 * @since 0.2.0
 * @deprecated 0.7.0
 */
function exmachina_after_page() {
	_deprecated_function( __FUNCTION__, '0.7', "do_atomic( 'after_singular' )" );
	exmachina_after_singular();
}

/**
 * @since 0.2.2
 * @deprecated 0.8.0
 */
function exmachina_comment_author() {
	_deprecated_function( __FUNCTION__, '0.8', 'exmachina_comment_author_shortcode()' );
	return exmachina_comment_author_shortcode();
}

/**
 * @since 0.4.0
 * @deprecated 1.0.0
 */
function exmachina_theme_settings() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'exmachina_get_default_theme_settings()' );
	return apply_filters( exmachina_get_prefix() . '_settings_args', exmachina_get_default_theme_settings() );
}

/**
 * @since 0.4.0
 * @deprecated 1.0.0
 */
function exmachina_doctype() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
	if ( !preg_match( "/MSIE 6.0/", esc_attr( $_SERVER['HTTP_USER_AGENT'] ) ) )
		$doctype = '<' . '?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '"?>' . "\n";

	$doctype .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
	echo apply_atomic( 'doctype', $doctype );
}

/**
 * @since 0.4.0
 * @deprecated 1.0.0
 */
function exmachina_meta_content_type() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
	$content_type = '<meta http-equiv="Content-Type" content="' . get_bloginfo( 'html_type' ) . '; charset=' . get_bloginfo( 'charset' ) . '" />' . "\n";
	echo apply_atomic( 'meta_content_type', $content_type );
}

/**
 * @since 0.4.0
 * @deprecated 1.0.0
 */
function exmachina_head_pingback() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
	$pingback = '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . "\n";
	echo apply_atomic( 'head_pingback', $pingback );
}

/**
 * @since 0.6.0
 * @deprecated 1.0.0
 */
function exmachina_profile_uri() {
	_deprecated_function( __FUNCTION__, '1.0.0', '' );
	echo apply_atomic( 'profile_uri', 'http://gmpg.org/xfn/11' );
}

/**
 * @since 0.3.2
 * @deprecated 1.0.0
 */
function exmachina_before_html() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_html' )" );
	do_atomic( 'before_html' );
}

/**
 * @since 0.3.2
 * @deprecated 1.0.0
 */
function exmachina_after_html() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_html' )" );
	do_atomic( 'after_html' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_head() {
	_deprecated_function( __FUNCTION__, '1.0.0', 'wp_head' );
	do_atomic( 'head' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_before_header() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_header' )" );
	do_atomic( 'before_header' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_header() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'header' )" );
	do_atomic( 'header' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_after_header() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_header' )" );
	do_atomic( 'after_header' );
}

/**
 * @since 0.8.0
 * @deprecated 1.0.0
 */
function exmachina_before_primary_menu() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_primary_menu' )" );
	do_atomic( 'before_primary_menu' );
}

/**
 * @since 0.8.0
 * @deprecated 1.0.0
 */
function exmachina_after_primary_menu() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_primary_menu' )" );
	do_atomic( 'after_primary_menu' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_before_container() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_container' )" );
	do_atomic( 'before_container' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_before_content() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_content' )" );
	do_atomic( 'before_content' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_after_content() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_content' )" );
	do_atomic( 'after_content' );
}

/**
 * @since 0.5.0
 * @deprecated 1.0.0
 */
function exmachina_before_entry() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_entry' )" );
	do_atomic( 'before_entry' );
}

/**
 * @since 0.5.0
 * @deprecated 1.0.0
 */
function exmachina_after_entry() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_entry' )" );
	do_atomic( 'after_entry' );
}

/**
 * @since 0.7.0
 * @deprecated 1.0.0
 */
function exmachina_after_singular() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_singular' )" );
	do_atomic( 'after_singular' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_before_primary() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_primary' )" );
	do_atomic( 'before_primary' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_after_primary() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_primary' )" );
	do_atomic( 'after_primary' );
}

/**
 * @since 0.2.0
 * @deprecated 1.0.0
 */
function exmachina_before_secondary() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_secondary' )" );
	do_atomic( 'before_secondary' );
}

/**
 * @since 0.2.0
 * @deprecated 1.0.0
 */
function exmachina_after_secondary() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_secondary' )" );
	do_atomic( 'after_secondary' );
}

/**
 * @since 0.3.1
 * @deprecated 1.0.0
 */
function exmachina_before_subsidiary() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_subsidiary' )" );
	do_atomic( 'before_subsidiary' );
}

/**
 * @since 0.3.1
 * @deprecated 1.0.0
 */
function exmachina_after_subsidiary() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_subsidiary' )" );
	do_atomic( 'after_subsidiary' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_after_container() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_container' )" );
	do_atomic( 'after_container' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_before_footer() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_footer' )" );
	do_atomic( 'before_footer' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_footer() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'footer' )" );
	do_atomic( 'footer' );
}

/**
 * @since 0.1.0
 * @deprecated 1.0.0
 */
function exmachina_after_footer() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_footer' )" );
	do_atomic( 'after_footer' );
}

/**
 * @since 0.5.0
 * @deprecated 1.0.0
 */
function exmachina_before_comment() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_comment' )" );
	do_atomic( 'before_comment' );
}

/**
 * @since 0.5.0
 * @deprecated 1.0.0
 */
function exmachina_after_comment() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_comment' )" );
	do_atomic( 'after_comment' );
}

/**
 * @since 0.6.0
 * @deprecated 1.0.0
 */
function exmachina_before_comment_list() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'before_comment_list' )" );
	do_atomic( 'before_comment_list' );
}

/**
 * @since 0.6.0
 * @deprecated 1.0.0
 */
function exmachina_after_comment_list() {
	_deprecated_function( __FUNCTION__, '1.0.0', "do_atomic( 'after_comment_list' )" );
	do_atomic( 'after_comment_list' );
}

/* @deprecated 1.0.0. Backwards compatibility with old theme settings. */
add_action( 'check_admin_referer', 'exmachina_back_compat_update_settings' );

/**
 * Backwards compatibility function for updating child theme settings.  Do not use this function or the 
 * available hook in development.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 */
function exmachina_back_compat_update_settings( $action ) {
	//_deprecated_function( __FUNCTION__, '1.0.0' );

	$prefix = exmachina_get_prefix();

	if ( "{$prefix}_theme_settings-options" == $action )
		do_action( "{$prefix}_update_settings_page" );
}

/**
 * @since 0.1.0
 * @deprecated 1.2.0
 */
function exmachina_enqueue_script() {
	_deprecated_function( __FUNCTION__, '1.2.0', 'exmachina_enqueue_scripts' );
	return;
}

/**
 * @since 1.0.0
 * @deprecated 1.2.0
 */
function exmachina_admin_enqueue_style() {
	_deprecated_function( __FUNCTION__, '1.2.0', 'exmachina_admin_enqueue_styles' );
	return;
}

/**
 * @since 0.7.0
 * @deprecated 1.2.0
 */
function exmachina_settings_page_enqueue_style() {
	_deprecated_function( __FUNCTION__, '1.2.0', 'exmachina_settings_page_enqueue_styles' );
	return;
}

/**
 * @since 0.7.0
 * @deprecated 1.2.0
 */
function exmachina_settings_page_enqueue_script() {
	_deprecated_function( __FUNCTION__, '1.2.0', 'exmachina_settings_page_enqueue_scripts' );
	return;
}

/**
 * @since 0.7.0
 * @deprecated 1.3.0
 */
function exmachina_admin_init() {
	_deprecated_function( __FUNCTION__, '1.3.0', 'exmachina_admin_setup' );
	return;
}

/**
 * @since 1.2.0
 * @deprecated 1.3.0
 */
function exmachina_settings_page_contextual_help() {
	_deprecated_function( __FUNCTION__, '1.3.0', 'exmachina_settings_page_help' );
	return;
}

/**
 * @since 0.9.0
 * @deprecated 1.3.0
 */
function exmachina_load_textdomain( $mofile, $domain ) {
	_deprecated_function( __FUNCTION__, '1.3.0', 'exmachina_load_textdomain_mofile' );
	return exmachina_load_textdomain_mofile( $mofile, $domain );
}

/**
 * @since 0.9.0
 * @deprecated 1.5.0
 */
function exmachina_debug_stylesheet( $stylesheet_uri, $stylesheet_dir_uri ) {
	_deprecated_function( __FUNCTION__, '1.5.0', 'exmachina_min_stylesheet_uri' );
	return exmachina_min_stylesheet_uri( $stylesheet_uri, $stylesheet_dir_uri );
}

/**
 * @since 1.5.0
 * @deprecated 1.6.0
 */
function post_format_tools_post_has_content( $id = 0 ) {
	_deprecated_function( __FUNCTION__, '1.6.0', 'exmachina_post_has_content()' );
	exmachina_post_has_content( $id );
}

/**
 * @since 1.5.0
 * @deprecated 1.6.0
 */
function post_format_tools_url_grabber() {
	_deprecated_function( __FUNCTION__, '1.6.0', 'exmachina_get_the_post_format_url()' );
	exmachina_get_the_post_format_url();
}

/**
 * @since 1.5.0
 * @deprecated 1.6.0
 */
function post_format_tools_get_image_attachment_count() {
	_deprecated_function( __FUNCTION__, '1.6.0', 'exmachina_get_gallery_image_count()' );
	exmachina_get_gallery_image_count();
}

/**
 * @since 1.5.0
 * @deprecated 1.6.0
 */
function post_format_tools_get_video( $deprecated = '' ) {
	_deprecated_function( __FUNCTION__, '1.6.0', 'exmachina_media_grabber()' );
	exmachina_media_grabber();
}

/**
 * @since 0.8.0
 * @deprecated 1.6.0
 */
function get_atomic_template( $template ) {
	_deprecated_function( __FUNCTION__, '1.6.0', '' );

	$templates = array();

	$theme_dir = trailingslashit( THEME_DIR ) . $template;
	$child_dir = trailingslashit( CHILD_THEME_DIR ) . $template;

	if ( is_dir( $child_dir ) || is_dir( $theme_dir ) ) {
		$dir = true;
		$templates[] = "{$template}/index.php";
	}
	else {
		$dir = false;
		$templates[] = "{$template}.php";
	}

	foreach ( exmachina_get_context() as $context )
		$templates[] = ( ( $dir ) ? "{$template}/{$context}.php" : "{$template}-{$context}.php" );

	return locate_template( array_reverse( $templates ), true, false );
}

/* === Removed Functions === */

/* Functions removed in the 0.8 branch. */

function exmachina_content_wrapper() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_handle_attachment() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_widget_class() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_before_ping_list() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_after_ping_list() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_pings_callback() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_pings_end_callback() {
	exmachina_function_removed( __FUNCTION__ );
}

/* Functions removed in the 1.2 branch. */

function exmachina_get_comment_form() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_before_comment_form() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_after_comment_form() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_get_utility_after_single() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_get_utility_after_page() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_create_post_meta_box() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_post_meta_box_args() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_post_meta_box() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_post_meta_box_text() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_post_meta_box_select() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_post_meta_box_textarea() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_post_meta_box_radio() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_save_post_meta_box() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_create_settings_meta_boxes() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_footer_settings_meta_box() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_about_theme_meta_box() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_load_settings_page() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_page_nav() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_cat_nav() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_category_menu() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_search_form() {
	exmachina_function_removed( __FUNCTION__ );
}

function is_sidebar_active() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_enqueue_style() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_add_theme_support() {
	exmachina_function_removed( __FUNCTION__ );
}

function exmachina_post_stylesheets() {
	exmachina_function_removed( __FUNCTION__ );
}

/* Functions removed in the 1.5 branch. */

function exmachina_get_theme_data() {
	exmachina_function_removed( __FUNCTION__ );
}

/* Functions removed in the 1.6 branch. */

function post_format_tools_single_term_title() {
	exmachina_function_removed( __FUNCTION__ );
}

function post_format_tools_aside_infinity() {
	exmachina_function_removed( __FUNCTION__ );
}

function post_format_tools_quote_content() {
	exmachina_function_removed( __FUNCTION__ );
}

function post_format_tools_link_content() {
	exmachina_function_removed( __FUNCTION__ );
}

function post_format_tools_chat_content() {
	exmachina_function_removed( __FUNCTION__ );
}

function post_format_tools_chat_row_id() {
	exmachina_function_removed( __FUNCTION__ );
}

function post_format_tools_get_plural_string() {
	exmachina_function_removed( __FUNCTION__ );
}

function post_format_tools_get_plural_strings() {
	exmachina_function_removed( __FUNCTION__ );
}

function post_format_tools_clean_post_format_slug() {
	exmachina_function_removed( __FUNCTION__ );
}

/**
 * Message to display for removed functions.
 * @since 0.5.0
 */
function exmachina_function_removed( $func = '' ) {
	die( sprintf( __( '<code>%1$s</code> &mdash; This function has been removed or replaced by another function.', 'exmachina-core' ), $func ) );
}

?>