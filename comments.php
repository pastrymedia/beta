<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Comments Template
 * comments.php
 *
 * Template for displaying comments.
 * @link http://codex.wordpress.org/Theme_Development#Comments_.28comments.php.29
 *
 * @package     Beta
 * @subpackage  Templates
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright (c) 2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com/themes/beta
 */
?>

<?php
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */

/* If a post password is required or no comments are given and comments/pings are closed, return. */
if ( post_password_required() || ( !have_comments() && !comments_open() && !pings_open() ) )
  return;

if ( is_singular( 'post' ) && ( !exmachina_get_setting( 'trackbacks_posts' ) && !exmachina_get_setting( 'comments_posts' ) ) )
  return;
elseif ( is_singular( 'page' ) && ( !exmachina_get_setting( 'trackbacks_pages' ) && !exmachina_get_setting( 'comments_pages' ) ) )
  return;

?>

<div id="comments" class="entry-comments">

  <?php get_template_part( 'partials/comments-loop' ); // Loads the comments-loop.php template. ?>

</div><!-- #comments -->

<?php comment_form(); ?>