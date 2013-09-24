<?php
/**
 * The main index template file.
 *
 * @package Beta Framework
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title>Beta Framework</title>
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>
    <div class="page">
      <article>
        <div class="entry-content">
          <h3>Beta Framework</h3>
          <p>Just a placeholder while we build the backend functions.</p>
          <hr>
        </div><!-- .entry-content -->
      </article><!-- article -->
    </div><!-- #page -->
    <?php wp_footer(); ?>
  </body>
</html>