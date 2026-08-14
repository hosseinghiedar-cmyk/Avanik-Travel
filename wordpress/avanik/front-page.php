<?php
defined('ABSPATH') || exit;
get_header();
?>
<div class="av-home">
  <section class="av-hero av-hero--home">
    <div class="av-hero__image" aria-hidden="true"></div>
    <div class="av-container av-hero__content">
      <div class="av-hero__copy">
        <span class="av-hero__eyebrow">آوانیک پرواز</span>
        <h1 class="av-hero__title">سفر بعدی شما از اینجا شروع می‌شود</h1>
        <p class="av-hero__subtitle">رزرو بلیط هواپیما، هتل و تور با آوانیک</p>
      </div>
    </div>
  </section>

  <section class="av-hero__search" aria-label="جستجوی سفر">
    <div class="av-search-card" data-av-search>
      <div class="av-search-tabs" role="tablist" aria-label="نوع خدمات">
        <button class="av-search-tab is-active" data-service="flight" type="button" role="tab" aria-selected="true">✈ پرواز</button>
        <button class="av-search-tab" data-service="hotel" type="button" role="tab" aria-selected="false">▣ هتل</button>
        <button class="av-search-tab" data-service="tour" type="button" role="tab" aria-selected="false">☼ تور</button>
      </div>

      <div class="av-search-subtabs" data-flight-only>
        <button class="av-search-subtab is-active" data-flight-type="domestic" type="button">پرواز داخلی</button>
        <button class="av-search-subtab" data-flight-type="international" type="button">پرواز خارجی</button>
      </div>

      <div class="av-search-options" data-flight-only>
        <label class="av-radio"><input type="radio" name="trip_type" value="oneway" checked> <span>یک طرفه</span></label>
        <label class="av-radio"><input type="radio" name="trip_type" value="roundtrip"> <span>رفت و برگشت</span></label>
      </div>

      <form class="av-search-form" action="<?php echo esc_url(home_url('/flight-search')); ?>" method="get" data-av-search-form>
        <label class="av-field av-field--select" data-city-field="origin">
          <span class="av-field__label">مبدا</span>
          <button class="av-field__control" type="button" data-av-city-trigger="origin"><span data-city-label="origin">تهران</span><span class="av-field__icon">⌄</span></button>
          <input type="hidden" name="origin" value="Tehran" data-city-input="origin">
          <div class="av-city-menu" data-city-menu="origin"></div>
        </label>
        <label class="av-field av-field--select" data-city-field="destination">
          <span class="av-field__label">مقصد</span>
          <button class="av-field__control" type="button" data-av-city-trigger="destination"><span data-city-label="destination">مشهد</span><span class="av-field__icon">⌄</span></button>
          <input type="hidden" name="destination" value="Mashhad" data-city-input="destination">
          <div class="av-city-menu" data-city-menu="destination"></div>
        </label>
        <label class="av-field av-field--date">
          <span class="av-field__label">تاریخ رفت</span>
          <button class="av-field__control" type="button" data-av-date-open="departure"><span data-av-date-label="departure">انتخاب تاریخ</span><span class="av-field__icon">▣</span></button>
          <input type="hidden" name="departure" value="" data-av-date-value="departure">
        </label>
        <label class="av-field av-field--date av-return-date is-hidden" data-return-date>
          <span class="av-field__label">تاریخ برگشت</span>
          <button class="av-field__control" type="button" data-av-date-open="return"><span data-av-date-label="return">انتخاب تاریخ</span><span class="av-field__icon">▣</span></button>
          <input type="hidden" name="return" value="" data-av-date-value="return">
        </label>
        <label class="av-field av-field--passengers">
          <span class="av-field__label">مسافران</span>
          <button class="av-field__control" type="button" data-av-passengers-open><span data-av-passengers-label>۱ بزرگسال</span><span class="av-field__icon">⌄</span></button>
          <input type="hidden" name="passengers" value="1" data-av-passengers-value>
        </label>
        <button class="av-btn av-btn--primary av-search-submit" type="submit"><span>جستجو</span><span>←</span></button>
      </form>

      <div class="av-search-note" data-flight-only>تاریخ انتخابی باید از امروز به بعد باشد.</div>
    </div>
  </section>

  <div class="av-popover av-date-popover" data-av-date-popover aria-hidden="true">
    <div class="av-popover__head"><strong>انتخاب تاریخ</strong><button type="button" class="av-popover__close" data-av-popover-close>×</button></div>
    <div class="av-date-switch"><button type="button" class="is-active" data-date-mode="jalali">شمسی</button><button type="button" data-date-mode="gregorian">میلادی</button></div>
    <div class="av-calendar-head"><button type="button" data-cal-prev>‹</button><strong data-cal-title></strong><button type="button" data-cal-next>›</button></div>
    <div class="av-calendar-week"><span>ش</span><span>ی</span><span>د</span><span>س</span><span>چ</span><span>پ</span><span>ج</span></div>
    <div class="av-calendar-grid" data-cal-grid></div>
  </div>

  <div class="av-popover av-passenger-popover" data-av-passenger-popover aria-hidden="true">
    <div class="av-popover__head"><strong>تعداد مسافران</strong><button type="button" class="av-popover__close" data-av-passengers-close>×</button></div>
    <div class="av-passenger-row"><div><strong>بزرگسال</strong><small>۱۲ سال به بالا</small></div><div class="av-stepper"><button type="button" data-passenger-minus="adult">−</button><b data-passenger-count="adult">1</b><button type="button" data-passenger-plus="adult">+</button></div></div>
    <div class="av-passenger-row"><div><strong>کودک</strong><small>۲ تا ۱۱ سال</small></div><div class="av-stepper"><button type="button" data-passenger-minus="child">−</button><b data-passenger-count="child">0</b><button type="button" data-passenger-plus="child">+</button></div></div>
    <div class="av-passenger-row"><div><strong>نوزاد</strong><small>زیر ۲ سال</small></div><div class="av-stepper"><button type="button" data-passenger-minus="infant">−</button><b data-passenger-count="infant">0</b><button type="button" data-passenger-plus="infant">+</button></div></div>
    <button class="av-btn av-btn--primary av-passenger-done" type="button" data-av-passengers-done>تأیید</button>
  </div>

  <div class="av-login-modal" data-av-login-modal aria-hidden="true">
    <div class="av-login-card">
      <button type="button" class="av-login-close" data-av-login-close>×</button>
      <div class="av-login-card__icon">♙</div>
      <h2>ورود و ثبت‌نام آوانیک</h2>
      <p>برای مدیریت سفرها و دریافت اطلاع‌رسانی‌ها اطلاعات خود را وارد کنید.</p>
      <form action="<?php echo esc_url(home_url('/login')); ?>" method="get" class="av-login-form">
        <label><span>نام</span><input type="text" name="first_name" autocomplete="given-name" placeholder="نام خود را وارد کنید" required></label>
        <label><span>نام خانوادگی</span><input type="text" name="last_name" autocomplete="family-name" placeholder="نام خانوادگی را وارد کنید" required></label>
        <label><span>شماره موبایل</span><input type="tel" name="mobile" inputmode="tel" autocomplete="tel" placeholder="۰۹۱۲۱۲۳۴۵۶۷" required></label>
        <button class="av-btn av-btn--primary" type="submit">ادامه</button>
      </form>
    </div>
  </div>

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
    <div class="av-airlines__grid"><div class="av-airline">آتا</div><div class="av-airline">ماهان</div><div class="av-airline">معراج</div><div class="av-airline">Emirates</div><div class="av-airline">Qatar Airways</div><div class="av-airline">Turkish Airlines</div><div class="av-airline">flydubai</div><div class="av-airline">Air Arabia</div></div>
  </section>
</div>
<?php get_footer(); ?>
