<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Footer Template
 * footer.php
 *
 * Template file for displaying the theme footer.
 * @link http://codex.wordpress.org/Theme_Development#Footer_.28footer.php.29
 *
 * @package     Beta
 * @subpackage  Templates
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright (c) 2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com/themes/beta
 */
?>

    <?php do_atomic( 'after_main' ); // beta_after_main ?>

  </div><!-- .site-inner -->

  <?php get_template_part( 'partials/footer' ); ?>

</div><!-- .site-container -->

<?php do_atomic( 'after' ); // beta_after ?>

<?php wp_footer(); ?>

</body>
</html>