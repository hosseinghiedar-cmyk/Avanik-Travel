<?php
defined('ABSPATH') || exit;
?>

<section class="av-hero">
  <div class="av-container">
    <div class="av-hero__wrapper">
      <div class="av-hero__content">
        <span class="av-badge">سامانه هوشمند رزرو سفر</span>

        <h1>تجربه‌ای جدید از رزرو پرواز، هتل و تور</h1>

        <p>بهترین قیمت، پشتیبانی ۲۴ ساعته، پرداخت امن و رزرو آنلاین.</p>

        <?php get_template_part('template-parts/home/search-box'); ?>
      </div>

      <div class="av-hero__image">
        <img
          src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-airplane.png'); ?>"
          alt="<?php echo esc_attr__('Avanik', 'avanik'); ?>"
        >
      </div>
    </div>
  </div>
</section>

<?php
get_template_part('template-parts/home/stats');
get_template_part('template-parts/home/airlines');
