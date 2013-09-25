<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Archives display
 * archive.php
 *
 * Template used to render archives pages.
 * @link http://codex.wordpress.org/Creating_an_Archive_Index#The_Archives_Page
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

  <main  class="<?php echo apply_atomic( 'beta_main_class', 'content' );?>" role="main" itemprop="mainContentOfPage" itemscope="itemscope" itemtype="http://schema.org/Blog">

    <?php do_atomic( 'before_content' ); // beta_before_content ?>

    <?php if ( have_posts() ) : ?>

      <header class="page-header">
        <h1 class="archive-title">
          <?php
            if ( is_category() ) :
              single_cat_title();

            elseif ( is_tag() ) :
              single_tag_title();

            elseif ( is_author() ) :
              /* Queue the first post, that way we know
               * what author we're dealing with (if that is the case).
              */
              the_post();
              printf( __( 'Author: %s', 'beta' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
              /* Since we called the_post() above, we need to
               * rewind the loop back to the beginning that way
               * we can run the loop properly, in full.
               */
              rewind_posts();

            elseif ( is_day() ) :
              printf( __( 'Day: %s', 'beta' ), '<span>' . get_the_date() . '</span>' );

            elseif ( is_month() ) :
              printf( __( 'Month: %s', 'beta' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

            elseif ( is_year() ) :
              printf( __( 'Year: %s', 'beta' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

            elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
              _e( 'Asides', 'beta' );

            elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
              _e( 'Images', 'beta');

            elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
              _e( 'Videos', 'beta' );

            elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
              _e( 'Quotes', 'beta' );

            elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
              _e( 'Links', 'beta' );

            else :
              _e( 'Archives', 'beta' );

            endif;
          ?>
        </h1>
        <?php
          // Show an optional term description.
          $term_description = term_description();
          if ( ! empty( $term_description ) ) :
            printf( '<div class="taxonomy-description">%s</div>', $term_description );
          endif;
        ?>
      </header><!-- .page-header -->

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

      <?php get_template_part( 'partials/no-results', 'archive' ); ?>

    <?php endif; ?>

    <?php do_atomic( 'after_content' ); // beta_after_content ?>

  </main><!-- .content -->

<?php get_footer(); ?>