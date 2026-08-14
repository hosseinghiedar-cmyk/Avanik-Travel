<?php
defined('ABSPATH') || exit;
get_header();
?>
<main id="primary" class="site-main av-page">
  <div class="av-container">
    <?php
    while ( have_posts() ) :
      the_post();
      the_content();
    endwhile;
    ?>
  </div>
</main>
<?php get_footer();
