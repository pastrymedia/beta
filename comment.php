<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Comment Template
 * comment.php
 *
 * Template file for displaying a single comment.
 *
 * @package     Beta
 * @subpackage  Templates
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright (c) 2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com/themes/beta
 */
?>

<li id="comment-<?php comment_ID(); ?>" class="<?php exmachina_comment_class(); ?>">

  <article itemtype="http://schema.org/UserComments" class="comment-item" itemscope="itemscope" itemprop="comment">

    <p class="comment-author" itemtype="http://schema.org/Person" itemscope="itemscope" itemprop="creator">
      <?php echo exmachina_avatar(); ?>
      <?php echo apply_atomic_shortcode( 'comment_author', '[comment-author]' ); ?>
    </p>

    <p class="comment-meta">
      <?php echo apply_atomic_shortcode( 'comment_meta', '[comment-published] [comment-permalink before="| "] [comment-edit-link before="| "]' ); ?>
    <p>

    <div class="comment-content">
      <?php comment_text(); ?>
    </div><!-- .comment-content -->

    <?php echo exmachina_comment_reply_link_shortcode( array() ); ?>

  </article><!-- .comment-item -->

<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>