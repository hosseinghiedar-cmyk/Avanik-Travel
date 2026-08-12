<?php
defined('ABSPATH') || exit;
get_header();
$home_url = home_url('/');
$flight_url = home_url('/flights');
$hotel_url = home_url('/hotels');
$tour_url = home_url('/tours');
$visa_url = home_url('/visa');
$contact_url = home_url('/contact');
$theme_uri = get_template_directory_uri();
?>
<div class="av-home av-reference-home" dir="rtl">
  <section class="av-hero av-hero--home">
    <div class="av-hero__image" aria-hidden="true"></div>
    <div class="av-container av-hero__content av-reveal">
      <span class="av-hero__eyebrow">AVANIK PARVAZ ASIA</span>
      <h1 class="av-hero__title"><?php echo esc_html(\Avanik\ThemeSettings::get('hero_title')); ?></h1>
      <p class="av-hero__subtitle"><?php echo esc_html(\Avanik\ThemeSettings::get('hero_subtitle')); ?></p>
      <div class="av-hero__headline-rule"><span></span><b>✈</b></div>
      <a class="av-btn av-btn--primary av-hero__cta" href="<?php echo esc_url($flight_url); ?>">رزرو آنلاین</a>
    </div>
  </section>

  <section class="av-hero__search av-reveal" aria-label="جستجوی سفر">
    <div class="av-search-card">
      <div class="av-search-tabs" role="tablist" aria-label="نوع خدمت">
        <button class="av-search-tab is-active" data-av-search-tab="flight-domestic" type="button" role="tab" aria-selected="true">پرواز داخلی ✈</button>
        <button class="av-search-tab" data-av-search-tab="flight-international" type="button" role="tab" aria-selected="false">پرواز خارجی ✈</button>
        <button class="av-search-tab" data-av-search-tab="hotel-domestic" type="button" role="tab" aria-selected="false">هتل داخلی ▣</button>
        <button class="av-search-tab" data-av-search-tab="hotel-international" type="button" role="tab" aria-selected="false">هتل خارجی ▣</button>
        <button class="av-search-tab" data-av-search-tab="tour" type="button" role="tab" aria-selected="false">تورهای مسافرتی ◈</button>
      </div>
      <div class="av-search-heading"><div><span>جستجوی سفر</span><strong data-av-search-title>پرواز داخلی</strong></div><small>مبدا، مقصد، تاریخ و تعداد مسافران را انتخاب کنید.</small></div>
      <div class="av-search-options" data-av-flight-only>
        <label class="av-radio"><input type="radio" name="trip_type" value="oneway" checked> یک طرفه</label>
        <label class="av-radio"><input type="radio" name="trip_type" value="roundtrip"> رفت و برگشت</label>
      </div>
      <form class="av-search-form" data-av-home-search data-av-action="/flights" action="<?php echo esc_url($flight_url); ?>" method="get">
        <label class="av-field" data-av-flight-only><span class="av-field__label">مبدا</span><span class="av-field__value"><input type="text" name="origin" placeholder="تهران (همه فرودگاه‌ها)" required></span></label>
        <label class="av-field" data-av-flight-only><span class="av-field__label">مقصد</span><span class="av-field__value"><input type="text" name="destination" placeholder="استانبول" required></span></label>
        <label class="av-field" data-av-flight-only><span class="av-field__label">تاریخ رفت</span><span class="av-field__value"><input type="date" name="departure" required></span></label>
        <label class="av-field" data-av-flight-only><span class="av-field__label">مسافر</span><span class="av-field__value"><select name="passengers"><option value="1">۱ مسافر</option><option value="2">۲ مسافر</option><option value="3">۳ مسافر</option><option value="4">۴ مسافر</option></select></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">مقصد / هتل</span><span class="av-field__value"><input type="text" name="destination" placeholder="استانبول، کیش، مشهد…"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">تاریخ ورود</span><span class="av-field__value"><input type="date" name="checkin"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">تاریخ خروج</span><span class="av-field__value"><input type="date" name="checkout"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">اتاق</span><span class="av-field__value"><select name="rooms"><option value="1">۱ اتاق، ۲ بزرگسال</option><option value="2">۲ اتاق، ۴ بزرگسال</option></select></span></label>
        <label class="av-field av-field--wide" data-av-tour-only><span class="av-field__label">مقصد تور</span><span class="av-field__value"><select name="tour_destination"><option>استانبول</option><option>دبی</option><option>پاریس</option><option>آنتالیا</option></select></span></label>
        <label class="av-field av-field--wide" data-av-tour-only><span class="av-field__label">تعداد مسافر</span><span class="av-field__value"><select name="tour_passengers"><option>۲ نفر</option><option>۳ نفر</option><option>۴ نفر</option></select></span></label>
        <button class="av-btn av-btn--primary av-search-submit" id="av-search-submit" data-av-search-submit type="submit">جستجوی پرواز 🔎</button>
      </form>
      <div class="av-search-note">قیمت‌ها و نتایج این نسخه نمایشی هستند و ساختار برای اتصال به سرویس‌های واقعی آماده شده است.</div>
    </div>
  </section>

  <section class="av-services av-container">
    <div class="av-section-heading"><div><span>خدمات ما</span><h2>همه خدمات سفر در آوانیک</h2></div></div>
    <div class="av-service-grid">
      <article class="av-service-card av-service-card--link av-reveal" data-av-service-link="<?php echo esc_url($flight_url); ?>" tabindex="0"><div class="av-service-card__icon">🎫</div><h2 class="av-service-card__title">خرید بلیط هواپیما</h2><p class="av-service-card__text">پروازهای داخلی و خارجی با امکان مقایسه و رزرو.</p><span class="av-service-card__link">مشاهده بیشتر ←</span></article>
      <article class="av-service-card av-service-card--link av-reveal" data-av-service-link="<?php echo esc_url($hotel_url); ?>" tabindex="0"><div class="av-service-card__icon">🏨</div><h2 class="av-service-card__title">رزرو هتل</h2><p class="av-service-card__text">هتل‌های داخلی و خارجی با انتخاب بر اساس قیمت و امتیاز.</p><span class="av-service-card__link">مشاهده بیشتر ←</span></article>
      <article class="av-service-card av-service-card--link av-reveal" data-av-service-link="<?php echo esc_url($tour_url); ?>" tabindex="0"><div class="av-service-card__icon">🧳</div><h2 class="av-service-card__title">تورهای مسافرتی</h2><p class="av-service-card__text">تورهای داخلی و خارجی برای مقاصد محبوب و ویژه.</p><span class="av-service-card__link">مشاهده بیشتر ←</span></article>
      <article class="av-service-card av-service-card--link av-reveal" data-av-service-link="<?php echo esc_url($visa_url); ?>" tabindex="0"><div class="av-service-card__icon">🛂</div><h2 class="av-service-card__title">ویزای مسافرتی</h2><p class="av-service-card__text">اخذ ویزا با مشاوره و پیگیری مراحل درخواست.</p><span class="av-service-card__link">مشاهده بیشتر ←</span></article>
      <article class="av-service-card av-service-card--link av-reveal" data-av-service-link="<?php echo esc_url($contact_url); ?>" tabindex="0"><div class="av-service-card__icon">🛡</div><h2 class="av-service-card__title">بیمه مسافرتی</h2><p class="av-service-card__text">پوشش مناسب برای سفرهای داخلی و خارجی.</p><span class="av-service-card__link">مشاهده بیشتر ←</span></article>
    </div>
  </section>

  <?php if ((int) \Avanik\ThemeSettings::get('show_tours') === 1) : ?>
  <section class="av-destinations av-container av-featured-tours">
    <div class="av-section-heading"><div><span>تورهای ویژه</span><h2>پیشنهادهای منتخب آوانیک</h2></div><a href="<?php echo esc_url($tour_url); ?>">مشاهده همه تورها ←</a></div>
    <div class="av-tour-grid">
      <a class="av-tour-card av-reveal" href="<?php echo esc_url($tour_url); ?>?destination=استانبول"><img src="<?php echo esc_url($theme_uri.'/assets/images/destination-istanbul.svg'); ?>" alt="تور استانبول"><div><strong>تور استانبول</strong><span>۳ شب و ۴ روز</span><b>۱۲,۹۰۰,۰۰۰ تومان</b></div></a>
      <a class="av-tour-card av-reveal" href="<?php echo esc_url($tour_url); ?>?destination=دبی"><img src="<?php echo esc_url($theme_uri.'/assets/images/destination-dubai.svg'); ?>" alt="تور دبی"><div><strong>تور دبی</strong><span>۴ شب و ۵ روز</span><b>۱۸,۵۰۰,۰۰۰ تومان</b></div></a>
      <a class="av-tour-card av-reveal" href="<?php echo esc_url($tour_url); ?>?destination=پاریس"><img src="<?php echo esc_url($theme_uri.'/assets/images/avanik-hero.svg'); ?>" alt="تور پاریس"><div><strong>تور پاریس</strong><span>۴ شب و ۵ روز</span><b>۲۸,۹۰۰,۰۰۰ تومان</b></div></a>
      <a class="av-tour-card av-reveal" href="<?php echo esc_url($tour_url); ?>?destination=آنتالیا"><img src="<?php echo esc_url($theme_uri.'/assets/images/destination-kish.svg'); ?>" alt="تور آنتالیا"><div><strong>تور آنتالیا</strong><span>۳ شب و ۴ روز</span><b>۱۱,۹۰۰,۰۰۰ تومان</b></div></a>
    </div>
  </section>
  <?php endif; ?>

  <?php if ((int) \Avanik\ThemeSettings::get('show_why') === 1) : ?>
  <section class="av-why"><div class="av-container"><div class="av-section-heading av-section-heading--center"><div><span>تجربه بهتر سفر</span><h2>چرا آوانیک پرواز آسیا؟</h2></div></div><div class="av-why-grid"><div class="av-why-item av-reveal"><i>★</i><strong>تجربه و اعتبار</strong><span>سال‌ها فعالیت و همراهی با مسافران</span></div><div class="av-why-item av-reveal"><i>⌁</i><strong>پرداخت امن</strong><span>فرآیند پرداخت ساده و مطمئن</span></div><div class="av-why-item av-reveal"><i>✓</i><strong>قیمت تضمینی</strong><span>انتخاب مناسب با مقایسه قیمت</span></div><div class="av-why-item av-reveal"><i>✦</i><strong>تجربه و پشتیبانی</strong><span><?php echo esc_html(\Avanik\ThemeSettings::get('support')); ?></span></div></div></div></section>
  <?php endif; ?>

  <section class="av-airlines av-container"><div class="av-section-heading"><div><span>همکاران</span><h2>ایرلاین‌های منتخب</h2></div></div><div class="av-airlines__grid"><div class="av-airline">آتا</div><div class="av-airline">ماهان</div><div class="av-airline">معراج</div><div class="av-airline">Emirates</div><div class="av-airline">Qatar Airways</div><div class="av-airline">Turkish Airlines</div><div class="av-airline">flydubai</div><div class="av-airline">Air Arabia</div></div></section>
</div>
<?php get_footer(); ?>
