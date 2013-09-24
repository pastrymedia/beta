<?php
/**
 * Primary Menu Template
 */
?>	
<nav class="nav-primary row" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
	
	<?php do_atomic( 'before_primary_menu' ); // beta_before_primary_menu ?>

	<?php 
	wp_nav_menu( array(
		'theme_location' => 'primary',
		'container'      => '',
		'menu_class'     => 'menu beta-nav-menu menu-primary',
		'fallback_cb'	 => 'beta_default_menu'
		)); 
	?>

	<?php do_atomic( 'after_primary_menu' ); // beta_after_primary_menu ?>

	
</nav><!-- .nav-primary -->
