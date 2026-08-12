<?php
defined('ABSPATH') || exit;
get_header();
$home_url = home_url('/');
$flight_url = home_url('/flights');
$hotel_url = home_url('/hotels');
$tour_url = home_url('/tours');
?>
<div class="av-home" dir="rtl">
  <section class="av-hero av-hero--home">
    <div class="av-hero__image" aria-hidden="true"></div>
    <div class="av-container av-hero__content">
      <span class="av-hero__eyebrow">AVANIK TRAVEL</span>
      <h1 class="av-hero__title">سفر بعدی شما از اینجا شروع می‌شود</h1>
      <p class="av-hero__subtitle">پرواز، هتل و تور را یکجا جستجو کنید و سفر خود را با آوانیک ساده‌تر بسازید.</p>
      <div class="av-hero__trust"><span>✓ رزرو سریع</span><span>✓ پشتیبانی سفر</span><span>✓ مسیرهای داخلی و خارجی</span></div>
    </div>
  </section>

  <section class="av-hero__search" aria-label="جستجوی سفر">
    <div class="av-search-card">
      <div class="av-search-tabs" role="tablist" aria-label="نوع خدمت">
        <button class="av-search-tab is-active" data-av-search-tab="flight-domestic" type="button" role="tab" aria-selected="true">پرواز داخلی</button>
        <button class="av-search-tab" data-av-search-tab="flight-international" type="button" role="tab" aria-selected="false">پرواز خارجی</button>
        <button class="av-search-tab" data-av-search-tab="hotel-domestic" type="button" role="tab" aria-selected="false">هتل داخلی</button>
        <button class="av-search-tab" data-av-search-tab="hotel-international" type="button" role="tab" aria-selected="false">هتل خارجی</button>
        <button class="av-search-tab" data-av-search-tab="tour" type="button" role="tab" aria-selected="false">تور</button>
      </div>
      <div class="av-search-heading"><div><span>جستجوی سفر</span><strong data-av-search-title>پرواز داخلی</strong></div><small>اطلاعات را وارد کنید و از بین گزینه‌های آماده انتخاب کنید.</small></div>
      <div class="av-search-options" data-av-flight-only>
        <label class="av-radio"><input type="radio" name="trip_type" value="oneway" checked> یک طرفه</label>
        <label class="av-radio"><input type="radio" name="trip_type" value="roundtrip"> رفت و برگشت</label>
      </div>
      <form class="av-search-form" data-av-home-search data-av-action="/flight-search" action="<?php echo esc_url($flight_url); ?>" method="get">
        <label class="av-field" data-av-flight-only><span class="av-field__label">مبدا</span><span class="av-field__value"><input type="text" name="origin" placeholder="تهران یا فرودگاه مهرآباد" required></span></label>
        <label class="av-field" data-av-flight-only><span class="av-field__label">مقصد</span><span class="av-field__value"><input type="text" name="destination" placeholder="مشهد، دبی یا استانبول" required></span></label>
        <label class="av-field" data-av-flight-only><span class="av-field__label">تاریخ رفت</span><span class="av-field__value"><input type="date" name="departure" required></span></label>
        <label class="av-field" data-av-flight-only><span class="av-field__label">مسافران</span><span class="av-field__value"><select name="passengers"><option value="1">۱ بزرگسال</option><option value="2">۲ بزرگسال</option><option value="3">۳ بزرگسال</option><option value="4">۴ بزرگسال</option></select></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">مقصد / هتل</span><span class="av-field__value"><input type="text" name="destination" placeholder="استانبول، کیش، مشهد…"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">ورود</span><span class="av-field__value"><input type="date" name="checkin"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">خروج</span><span class="av-field__value"><input type="date" name="checkout"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">اتاق</span><span class="av-field__value"><select name="rooms"><option value="1">۱ اتاق، ۲ بزرگسال</option><option value="2">۲ اتاق، ۴ بزرگسال</option></select></span></label>
        <label class="av-field av-field--wide" data-av-tour-only><span class="av-field__label">مقصد تور</span><span class="av-field__value"><select name="tour_destination"><option>استانبول</option><option>دبی</option><option>کیش</option><option>آنتالیا</option></select></span></label>
        <label class="av-field av-field--wide" data-av-tour-only><span class="av-field__label">تعداد مسافر</span><span class="av-field__value"><select name="tour_passengers"><option>۲ نفر</option><option>۳ نفر</option><option>۴ نفر</option></select></span></label>
        <button class="av-btn av-btn--primary av-search-submit" id="av-search-submit" data-av-search-submit type="submit">جستجوی پرواز</button>
      </form>
      <div class="av-search-note">نسخه نمایشی آوانیک: نتایج این بسته از داده‌های نمونه استفاده می‌کنند و برای اتصال به API تأمین‌کننده آماده هستند.</div>
    </div>
  </section>

  <section class="av-services av-container">
    <div class="av-section-heading"><div><span>خدمات آوانیک</span><h2>همه چیز برای یک سفر راحت</h2></div><a href="<?php echo esc_url($tour_url); ?>">مشاهده همه خدمات ←</a></div>
    <div class="av-service-grid">
      <article class="av-service-card av-service-card--link" data-av-service-link="<?php echo esc_url($flight_url); ?>" tabindex="0"><div class="av-service-card__icon">✈</div><h2 class="av-service-card__title">رزرو پرواز</h2><p class="av-service-card__text">پروازهای داخلی و خارجی با مقایسه قیمت و ساعت حرکت.</p><span class="av-service-card__link">جستجوی پرواز ←</span></article>
      <article class="av-service-card av-service-card--link" data-av-service-link="<?php echo esc_url($hotel_url); ?>" tabindex="0"><div class="av-service-card__icon">▣</div><h2 class="av-service-card__title">رزرو هتل</h2><p class="av-service-card__text">انتخاب هتل بر اساس مقصد، امتیاز، قیمت و امکانات.</p><span class="av-service-card__link">جستجوی هتل ←</span></article>
      <article class="av-service-card av-service-card--link" data-av-service-link="<?php echo esc_url($tour_url); ?>" tabindex="0"><div class="av-service-card__icon">☼</div><h2 class="av-service-card__title">تورهای مسافرتی</h2><p class="av-service-card__text">تورهای داخلی و خارجی با صفحه جزئیات و مسیر رزرو.</p><span class="av-service-card__link">مشاهده تورها ←</span></article>
      <article class="av-service-card av-service-card--link" data-av-service-link="<?php echo esc_url(home_url('/contact')); ?>" tabindex="0"><div class="av-service-card__icon">◆</div><h2 class="av-service-card__title">پشتیبانی سفر</h2><p class="av-service-card__text">دسترسی سریع به اطلاعات تماس و پشتیبانی رزرو.</p><span class="av-service-card__link">تماس با ما ←</span></article>
    </div>
  </section>

  <section class="av-destinations av-container">
    <div class="av-section-heading"><div><span>مقاصد محبوب</span><h2>برای سفر بعدی‌تان ایده بگیرید</h2></div></div>
    <div class="av-destination-grid">
      <a class="av-destination-card" href="<?php echo esc_url($tour_url); ?>?destination=استانبول"><img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/destination-istanbul.svg'); ?>" alt="استانبول"><div><strong>استانبول</strong><span>تور و هتل‌های محبوب</span></div></a>
      <a class="av-destination-card" href="<?php echo esc_url($tour_url); ?>?destination=دبی"><img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/destination-dubai.svg'); ?>" alt="دبی"><div><strong>دبی</strong><span>پرواز و هتل‌های منتخب</span></div></a>
      <a class="av-destination-card" href="<?php echo esc_url($tour_url); ?>?destination=کیش"><img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/destination-kish.svg'); ?>" alt="کیش"><div><strong>کیش</strong><span>تورهای داخلی و اقامت</span></div></a>
    </div>
  </section>

  <section class="av-airlines av-container">
    <div class="av-section-heading"><div><span>همکاران نمونه</span><h2>ایرلاین‌های قابل نمایش در نسخه دمو</h2></div></div>
    <div class="av-airlines__grid"><div class="av-airline">آتا</div><div class="av-airline">ماهان</div><div class="av-airline">معراج</div><div class="av-airline">Emirates</div><div class="av-airline">Qatar Airways</div><div class="av-airline">Turkish Airlines</div><div class="av-airline">flydubai</div><div class="av-airline">Air Arabia</div></div>
  </section>
</div>
<?php get_footer(); ?>
