<?php
defined('ABSPATH') || exit;
get_header();
?>
<div class="av-home">
  <section class="av-hero">
    <div class="av-hero__image" aria-hidden="true"></div>
    <div class="av-container av-hero__content">
      <h1 class="av-hero__title">سفر بعدی شما از اینجا شروع می‌شود</h1>
      <p class="av-hero__subtitle">رزرو بلیط هواپیما، هتل و تور با آوانیک</p>
    </div>
  </section>

  <section class="av-hero__search" aria-label="جستجوی سفر">
    <div class="av-search-card">
      <div class="av-search-tabs" role="tablist">
        <button class="av-search-tab is-active" type="button">پرواز داخلی</button>
        <button class="av-search-tab" type="button">پرواز خارجی</button>
        <button class="av-search-tab" type="button">هتل داخلی</button>
        <button class="av-search-tab" type="button">هتل خارجی</button>
        <button class="av-search-tab" type="button">تور</button>
      </div>
      <div class="av-search-options">
        <label class="av-radio"><input type="radio" name="trip_type" checked> یک طرفه</label>
        <label class="av-radio"><input type="radio" name="trip_type"> رفت و برگشت</label>
      </div>
      <form class="av-search-form" action="<?php echo esc_url(home_url('/flight-search')); ?>" method="get">
        <label class="av-field">
          <span class="av-field__label">مبدا</span>
          <span class="av-field__value">انتخاب شهر یا فرودگاه</span>
          <input type="hidden" name="origin" value="">
        </label>
        <label class="av-field">
          <span class="av-field__label">مقصد</span>
          <span class="av-field__value">انتخاب شهر یا فرودگاه</span>
          <input type="hidden" name="destination" value="">
        </label>
        <label class="av-field">
          <span class="av-field__label">تاریخ رفت</span>
          <span class="av-field__value">انتخاب تاریخ</span>
          <input type="hidden" name="departure" value="">
        </label>
        <label class="av-field">
          <span class="av-field__label">مسافران</span>
          <span class="av-field__value">۱ بزرگسال</span>
          <input type="hidden" name="passengers" value="1">
        </label>
        <button class="av-btn av-btn--primary av-search-submit" type="submit">جستجوی پرواز</button>
      </form>
    </div>
  </section>

  <section class="av-services av-container">
    <div class="av-service-grid">
      <article class="av-service-card"><div class="av-service-card__icon">✈</div><h2 class="av-service-card__title">رزرو پرواز</h2><p class="av-service-card__text">پروازهای داخلی و خارجی</p></article>
      <article class="av-service-card"><div class="av-service-card__icon">▣</div><h2 class="av-service-card__title">رزرو هتل</h2><p class="av-service-card__text">هتل‌های منتخب ایران و جهان</p></article>
      <article class="av-service-card"><div class="av-service-card__icon">☼</div><h2 class="av-service-card__title">تورهای مسافرتی</h2><p class="av-service-card__text">تورهای داخلی و خارجی</p></article>
      <article class="av-service-card"><div class="av-service-card__icon">◆</div><h2 class="av-service-card__title">پشتیبانی</h2><p class="av-service-card__text">همراه شما در تمام مسیر</p></article>
    </div>
  </section>

  <section class="av-airlines av-container">
    <h2 class="av-airlines__title">ایرلاین‌های طرف قرارداد</h2>
    <div class="av-airlines__grid">
      <div class="av-airline">آتا</div><div class="av-airline">ماهان</div><div class="av-airline">معراج</div>
      <div class="av-airline">Emirates</div><div class="av-airline">Qatar Airways</div><div class="av-airline">Turkish Airlines</div>
      <div class="av-airline">flydubai</div><div class="av-airline">Air Arabia</div>
    </div>
  </section>
</div>
<?php get_footer(); ?>
