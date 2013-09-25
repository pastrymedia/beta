<?php
/**
 * Creates a meta box for the theme settings page, which holds textareas for custom scripts within
 * the theme.
 *
 */

add_action( 'admin_menu', 'exmachina_theme_admin_archives' );

function exmachina_theme_admin_archives() {

	global $theme_settings_page;

	/* Get the theme prefix. */
	$prefix = exmachina_get_prefix();

	/* Create a settings meta box only on the theme settings page. */
	add_action( 'load-appearance_page_theme-settings', 'exmachina_theme_settings_archives' );

	/* Sanitize the scripts settings before adding them to the database. */
	add_filter( "sanitize_option_{$prefix}_theme_settings", 'exmachina_theme_validate_archives' );

	/* Adds my_help_tab when my_admin_page loads */
    add_action('load-'.$theme_settings_page, 'exmachina_theme_settings_archives_help');
}

/**
 * Adds Content Archives meta box to the theme settings page in the admin.
 *
 * @since 0.3.0
 * @return void
 */
function exmachina_theme_settings_archives() {

	add_meta_box(
		'exmachina-theme-archives',
		__( 'Content Archives', 'exmachina-core' ),
		'exmachina_meta_box_theme_display_archives',
		'appearance_page_theme-settings', 'normal', 'high' );

}

/**
 * Callback for Theme Settings Post Archives meta box.
 */
function exmachina_meta_box_theme_display_archives() {
?>
	<p class="collapsed">
		<label for="<?php echo exmachina_settings_field_id( 'content_archive' ); ?>"><?php _e( 'Select one of the following:', 'exmachina-core' ); ?></label>
		<select name="<?php echo exmachina_settings_field_name( 'content_archive' ); ?>" id="<?php echo exmachina_settings_field_id( 'content_archive' ); ?>">
		<?php
		$archive_display = apply_filters(
			'exmachina_archive_display_options',
			array(
				'full'     => __( 'Display full post', 'exmachina-core' ),
				'excerpts' => __( 'Display post excerpts', 'exmachina-core' ),
			)
		);
		foreach ( (array) $archive_display as $value => $name )
			echo '<option value="' . esc_attr( $value ) . '"' . selected( exmachina_get_setting( 'content_archive' ), esc_attr( $value ), false ) . '>' . esc_html( $name ) . '</option>' . "\n";
		?>
		</select>
	</p>

	<div id="content_limit_setting" <?php if ( 'full' == exmachina_get_setting( 'content_archive' )) echo 'class="hidden"';?>>
		<p>
			<label for="<?php echo exmachina_settings_field_id( 'content_archive_limit' ); ?>"><?php _e( 'Limit content to', 'exmachina-core' ); ?>
			<input type="text" name="<?php echo exmachina_settings_field_name( 'content_archive_limit' ); ?>" id="<?php echo exmachina_settings_field_id( 'content_archive_limit' ); ?>" value="<?php echo esc_attr( exmachina_get_setting( 'content_archive_limit' ) ); ?>" size="3" />
			<?php _e( 'characters', 'exmachina-core' ); ?></label>
		</p>

		<p><span class="description"><?php _e( 'Select "Display post excerpts" will limit the text and strip all formatting from the text displayed. Set 0 characters will display the first 55 words (default)', 'exmachina-core' ); ?></span></p>
	</div>

	<p>
		<?php _e( 'More Text (if applicable):', 'exmachina-core' ); ?> <input type="text" name="<?php echo exmachina_settings_field_name( 'content_archive_more' ); ?>" id="<?php echo exmachina_settings_field_id( 'content_archive_more' ); ?>" value="<?php echo esc_attr( exmachina_get_setting( 'content_archive_more' ) ); ?>" size="25" />
	</p>

	<p class="collapsed">
		<label for="<?php echo exmachina_settings_field_id( 'content_archive_thumbnail' ); ?>"><input type="checkbox" name="<?php echo exmachina_settings_field_name( 'content_archive_thumbnail' ); ?>" id="<?php echo exmachina_settings_field_id( 'content_archive_thumbnail' ); ?>" value="1" <?php checked( exmachina_get_setting( 'content_archive_thumbnail' ) ); ?> />
		<?php _e( 'Include the Featured Image?', 'exmachina-core' ); ?></label>
	</p>

	<p id="exmachina_image_size" <?php if (!exmachina_get_setting( 'content_archive_thumbnail' )) echo 'class="hidden"';?>>
		<label for="<?php echo exmachina_settings_field_id( 'image_size' ); ?>"><?php _e( 'Image Size:', 'exmachina-core' ); ?></label>
		<select name="<?php echo exmachina_settings_field_name( 'image_size' ); ?>" id="<?php echo exmachina_settings_field_id( 'image_size' ); ?>">
		<?php
		$sizes = exmachina_get_image_sizes();
		foreach ( (array) $sizes as $name => $size )
			echo '<option value="' . esc_attr( $name ) . '"' . selected( exmachina_get_setting( 'image_size' ), $name, FALSE ) . '>' . esc_html( $name ) . ' (' . absint( $size['width'] ) . ' &#x000D7; ' . absint( $size['height'] ) . ')</option>' . "\n";
		?>
		</select>
	</p>
	<p>
		<label for="<?php echo exmachina_settings_field_id( 'posts_nav' ); ?>"><?php _e( 'Select Post Navigation Format:', 'exmachina-core' ); ?></label>
		<select name="<?php echo exmachina_settings_field_name( 'posts_nav' ); ?>" id="<?php echo exmachina_settings_field_id( 'posts_nav' ); ?>">
			<option value="prev-next"<?php selected( 'prev-next', exmachina_get_setting( 'posts_nav' ) ); ?>><?php _e( 'Previous / Next', 'exmachina-core' ); ?></option>
			<option value="numeric"<?php selected( 'numeric', exmachina_get_setting( 'posts_nav' ) ); ?>><?php _e( 'Numeric', 'exmachina-core' ); ?></option>
		</select>
	</p>
	<p><span class="description"><?php _e( 'These options will affect any blog listings page, including archive, author, blog, category, search, and tag pages.', 'exmachina-core' ); ?></span></p>
	<p>
		<label for="<?php echo exmachina_settings_field_id( 'single_nav' ); ?>"><input type="checkbox" name="<?php echo exmachina_settings_field_name( 'single_nav' ); ?>" id="<?php echo exmachina_settings_field_id( 'single_nav' ); ?>" value="1" <?php checked( exmachina_get_setting( 'single_nav' ) ); ?> />
		<?php _e( 'Disable single post navigation link?', 'exmachina-core' ); ?></label>
	</p>

<?php }


/**
 * Saves the scripts meta box settings by filtering the "sanitize_option_{$prefix}_theme_settings" hook.
 *
 * @since 0.3.0
 * @param array $settings Array of theme settings passed by the Settings API for validation.
 * @return array $settings
 */
function exmachina_theme_validate_archives( $settings ) {

	if ( !isset( $_POST['reset'] ) ) {
		$settings['content_archive_limit'] =  absint( $settings['content_archive_limit'] );
		$settings['content_archive_thumbnail'] =  absint( $settings['content_archive_thumbnail'] );
	}

	/* Return the theme settings. */
	return $settings;
}

/**
 * Contextual help content.
 */
function exmachina_theme_settings_archives_help() {

	$screen = get_current_screen();

	$archives_help =
		'<h3>' . __( 'Content Archives', 'exmachina-core' ) . '</h3>' .
		'<p>'  . __( 'You may change the site wide Content Archives options to control what displays in the site\'s Archives.', 'exmachina-core' ) . '</p>' .
		'<p>'  . __( 'Archives include any pages using the blog template, category pages, tag pages, date archive, author archives, and the latest posts if there is no custom home page.', 'exmachina-core' ) . '</p>' .
		'<p>'  . __( 'The first option allows you to display the full post or the post excerpt. The Display full post setting will display the entire post including HTML code up to the <!--more--> tag if used (this is HTML for the comment tag that is not displayed in the browser).', 'exmachina-core' ) . '</p>' .
		'<p>'  . __( 'The Display post excerpt setting will display the first 55 words of the post after also stripping any included HTML or the manual/custom excerpt added in the post edit screen.', 'exmachina-core' ) . '</p>' .
		'<p>'  . __( 'It may also be coupled with the second field "Limit content to [___] characters" to limit the content to a specific number of letters or spaces.', 'exmachina-core' ) . '</p>' .
		'<p>'  . __( 'The \'Include post image?\' setting allows you to show a thumbnail of the first attached image or currently set featured image.', 'exmachina-core' ) . '</p>' .
		'<p>'  . __( 'This option should not be used with the post content unless the content is limited to avoid duplicate images.', 'exmachina-core' ) . '</p>' .
		'<p>'  . __( 'The \'Image Size\' list is populated by the available image sizes defined in the theme.', 'exmachina-core' ) . '</p>' .
		'<p>'  . __( 'Post Navigation format allows you to select one of two navigation methods.', 'exmachina-core' ) . '</p>';
		'<p>'  . __( 'There is also a checkbox to disable previous & next navigation links on single post', 'exmachina-core' ) . '</p>';

	$screen->add_help_tab( array(
		'id'      => 'exmachina-settings' . '-archives',
		'title'   => __( 'Content Archives', 'exmachina-core' ),
		'content' => $archives_help,
	) );

}

?>