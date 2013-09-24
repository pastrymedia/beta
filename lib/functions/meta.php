<?php
/**
 * Metadata functions used in the core framework.  This file registers meta keys for use in WordPress 
 * in a safe manner by setting up a custom sanitize callback.
 *
 * @package    ExMachinaCore
 * @subpackage Functions
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2013, Justin Tadlock
 * @link       http://themeexmachina.com/exmachina-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Register meta on the 'init' hook. */
add_action( 'init', 'exmachina_register_meta' );

/**
 * Registers the framework's custom metadata keys and sets up the sanitize callback function.
 *
 * @since 1.3.0
 * @return void
 */
function exmachina_register_meta() {

	/* Register meta if the theme supports the 'exmachina-core-seo' feature. */
	if ( current_theme_supports( 'exmachina-core-seo' ) ) {

		/* Register 'Title', 'Description', and 'Keywords' meta for posts. */
		register_meta( 'post', 'Title', 'exmachina_sanitize_meta' );
		register_meta( 'post', 'Description', 'exmachina_sanitize_meta' );
		register_meta( 'post', 'Keywords', 'exmachina_sanitize_meta' );

		/* Register 'Title', 'Description', and 'Keywords' meta for users. */
		register_meta( 'user', 'Title', 'exmachina_sanitize_meta' );
		register_meta( 'user', 'Description', 'exmachina_sanitize_meta' );
		register_meta( 'user', 'Keywords', 'exmachina_sanitize_meta' );
	}

	/* Register meta if the theme supports the 'exmachina-core-template-hierarchy' feature. */
	if ( current_theme_supports( 'exmachina-core-template-hierarchy' ) ) {

		$post_types = get_post_types( array( 'public' => true ) );

		foreach ( $post_types as $post_type ) {
			if ( 'page' !== $post_type )
				register_meta( 'post', "_wp_{$post_type}_template", 'exmachina_sanitize_meta' );
		}
	}
}

/**
 * Callback function for sanitizing meta when add_metadata() or update_metadata() is called by WordPress. 
 * If a developer wants to set up a custom method for sanitizing the data, they should use the 
 * "sanitize_{$meta_type}_meta_{$meta_key}" filter hook to do so.
 *
 * @since 1.3.0
 * @param mixed $meta_value The value of the data to sanitize.
 * @param string $meta_key The meta key name.
 * @param string $meta_type The type of metadata (post, comment, user, etc.)
 * @return mixed $meta_value
 */
function exmachina_sanitize_meta( $meta_value, $meta_key, $meta_type ) {
	return strip_tags( $meta_value );
}

?>