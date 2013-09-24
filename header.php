<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<title><?php exmachina_document_title(); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body class="<?php exmachina_body_class(); ?>" itemscope="itemscope" itemtype="http://schema.org/WebPage">

<?php do_atomic( 'before' ); // beta_before ?>

<div class="site-container">

	<?php get_template_part( 'partials/header' ); ?>

	<div class="site-inner row">

		<?php do_atomic( 'before_main' ); // beta_before_main ?>