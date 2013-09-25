<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Single Post display
 * single.php
 *
 * Template file used to render a single post page.
 * @link http://codex.wordpress.org/Theme_Development#Single_Post_.28single.php.29
 *
 * @package     Beta
 * @subpackage  Templates
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright (c) 2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com/themes/beta
 */
?>

<?php get_header(); ?>

  <main class="<?php echo apply_atomic( 'beta_main_class', 'content' );?>" role="main" itemprop="mainContentOfPage" itemscope="itemscope" itemtype="http://schema.org/Blog">

    <?php do_atomic( 'before_content' ); // beta_before_content ?>

    <?php while ( have_posts() ) : the_post(); ?>

      <?php get_template_part( 'partials/content', 'single' ); ?>

      <?php exmachina_content_nav( 'nav-below' ); ?>

      <?php comments_template(); // Loads the comments.php template. ?>

    <?php endwhile; // end of the loop. ?>

    <?php do_atomic( 'after_content' ); // beta_after_content ?>

  </main><!-- .content -->

<?php get_footer(); ?>