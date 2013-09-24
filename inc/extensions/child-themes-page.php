<?php

if ( is_admin() ) {
	/* Hook the beta child themes page function to 'admin_menu'. */
	add_action( 'admin_menu', 'beta_child_themes_page_init', 11 );

	function beta_child_themes_page_init() {

		$page = add_theme_page( 
			sprintf( esc_html__( 'Beta Child Themes', 'beta' ) ),	// Settings page name.
			esc_html__( 'Beta Child Themes', 'beta' ), 			// Menu item name.
			hybrid_settings_page_capability(), 						// Required capability.
			'beta-child-themes', 									// Screen name.
			'beta_child_themes_list' );							// Callback function.

		add_action ( 'beta_child_theme', 'child_theme_button' );
	}

	function beta_child_themes_list() {

		// Set template directory uri
	    $screenshot_dir = get_template_directory_uri() . '/inc/images/';

		$betachilds = array(
			 	array( 'name' => 'Alpha',
			 		   'url' => 'http://wordpress.org/themes/alpha',
			 		   'img' => 'alpha.png'),
			 	array( 'name' => 'Beta',
			 		   'url' => 'http://wordpress.org/themes/beta',
			 		   'img' => 'beta.png'),
			 	array( 'name' => 'Beta Child',
			 		   'url' => 'http://themehall.com/product/beta-child',
			 		   'img' => 'beta-child.png')
			 );
		?>
	 	<div class="wrap">

			<?php screen_icon(); ?>
			<h2>
				<?php printf( __( 'Beta Child Themes', 'Beta' ) ); ?>
			</h2>

			<p>
				<?php printf( __( 'Personalize your Beta powered site with one of Beta child themes below', 'Beta' ) ); ?>
			</p>

			<div id="availablethemes">
			<?php
			$currenttheme = wp_get_theme();

			foreach ( $betachilds as $betachild) {
				if ($betachild['name'] != $currenttheme->name) {
					echo '<div class="available-theme">
						<a class="screenshot" target="_blank" href="' . $betachild['url'] .'">
							<img alt="'.$betachild['name'].'" src="'. $screenshot_dir.$betachild['img'] .'">
						</a>
					</div>';
				}
			}
			?>			
				<div class="available-theme">
					<a class="screenshot">
						<img alt="More to Come" src="<?php echo $screenshot_dir . 'more.png';?>">
					</a>
				</div>		
			</div>

		</div>
		<?php
	}

	function child_theme_button() {
		?>
		<a href="<?php echo admin_url( 'themes.php?page=beta-child-themes' ); ?>" class="add-new-h2"><?php esc_html_e( 'Child Themes', 'beta' ); ?></a>
		<?php
	}
}


/**
 * Add Child Theme menu item to Admin Bar.
 */

function beta_child_theme_adminbar() {

	global $wp_admin_bar;

	$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id' => 'beta-child-themes',
			'title' => __( 'Beta Child Themes', 'beta' ),
			'href' => admin_url( 'themes.php?page=beta-child-themes' )
		));
}
add_action( 'wp_before_admin_bar_render', 'beta_child_theme_adminbar' );