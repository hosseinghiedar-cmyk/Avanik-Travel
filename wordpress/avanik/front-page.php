<?php
defined('ABSPATH') || exit;
get_header();
?>
<main id="primary" class="site-main av-front-page">
<?php
if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();
    $content = trim( get_the_content() );
    if ( $content !== '' || ( class_exists('Elementor\\Plugin') && \Elementor\Plugin::$instance->documents->get(get_the_ID())?->is_built_with_elementor() ) ) {
      the_content();
    } else {
      ?>
      <div class="av-home">
        <section class="av-hero"><div class="av-hero__image" aria-hidden="true"></div><div class="av-container av-hero__content"><h1 class="av-hero__title">سفر بعدی شما از اینجا شروع می‌شود</h1><p class="av-hero__subtitle">رزرو بلیط هواپیما، هتل و تور با آوانیک</p></div></section>
        <section class="av-services av-container"><div class="av-service-grid"><article class="av-service-card"><div class="av-service-card__icon">✈</div><h2 class="av-service-card__title">رزرو پرواز</h2><p class="av-service-card__text">پروازهای داخلی و خارجی</p></article><article class="av-service-card"><div class="av-service-card__icon">▣</div><h2 class="av-service-card__title">رزرو هتل</h2><p class="av-service-card__text">هتل‌های منتخب ایران و جهان</p></article><article class="av-service-card"><div class="av-service-card__icon">☼</div><h2 class="av-service-card__title">تورهای مسافرتی</h2><p class="av-service-card__text">تورهای داخلی و خارجی</p></article><article class="av-service-card"><div class="av-service-card__icon">◆</div><h2 class="av-service-card__title">پشتیبانی</h2><p class="av-service-card__text">همراه شما در تمام مسیر</p></article></div></section>
        <section class="av-airlines av-container"><h2 class="av-airlines__title">ایرلاین‌های طرف قرارداد</h2><div class="av-airlines__grid"><div class="av-airline">آتا</div><div class="av-airline">ماهان</div><div class="av-airline">معراج</div><div class="av-airline">Emirates</div><div class="av-airline">Qatar Airways</div><div class="av-airline">Turkish Airlines</div><div class="av-airline">flydubai</div><div class="av-airline">Air Arabia</div></div></section>
      </div>
      <?php
    }
  endwhile;
endif;
?>
</main>
<?php get_footer();
