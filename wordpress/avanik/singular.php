<?php
/**
 * Universal singular fallback for Elementor and WordPress.
 * The_content() is intentionally present in the standard loop.
 */
if (!defined('ABSPATH')) exit;
get_header();
?>
<main id="primary" class="site-main avanik-singular">
  <div class="avanik-shell avanik-page-content">
    <?php while (have_posts()) : the_post(); the_content(); endwhile; ?>
  </div>
</main>
<?php get_footer(); ?>
