<?php
defined('ABSPATH') || exit;
get_header();
?>
<main id="primary" class="site-main av-main">
  <div class="av-container avanik-page-content">
    <?php
    if (have_posts()) :
      while (have_posts()) :
        the_post();
        the_content();
      endwhile;
    endif;
    ?>
  </div>
</main>
<?php get_footer(); ?>
