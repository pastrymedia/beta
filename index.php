<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Index Template
 * index.php
 *
 * The main template file.
 * @link http://codex.wordpress.org/Theme_Development#Index_.28index.php.29
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

    <?php if ( have_posts() ) : ?>

      <?php /* Start the Loop */ ?>
      <?php while ( have_posts() ) : the_post(); ?>

        <?php
          /* Include the Post-Format-specific template for the content.
           * If you want to overload this in a child theme then include a file
           * called content-___.php (where ___ is the Post Format name) and that will be used instead.
           */
          get_template_part( 'partials/content', get_post_format() );
        ?>

      <?php endwhile; ?>

      <?php exmachina_content_nav( 'nav-below' ); ?>

    <?php else : ?>

      <?php get_template_part( 'partials/no-results', 'index' ); ?>

    <?php endif; ?>

    <?php do_atomic( 'after_content' ); // beta_after_content ?>

  </main><!-- .content -->

<?php get_footer(); ?>