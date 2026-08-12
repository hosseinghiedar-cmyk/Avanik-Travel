<?php
defined('ABSPATH') || exit;
get_header();
?>
<main class="av-page" dir="rtl">
  <div class="av-container">
    <section class="av-page-hero">
      <span class="av-page-hero__eyebrow">AVANIK TRAVEL</span>
      <h1><?php the_title(); ?></h1>
    </section>
    <section class="av-page-content">
      <?php while (have_posts()) : the_post(); the_content(); endwhile; ?>
    </section>
  </div>
</main>
<?php get_footer(); ?>
