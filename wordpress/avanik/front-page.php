<?php
defined('ABSPATH') || exit;
get_header();
$home_url=home_url('/');
$flight_url=home_url('/flights');
$hotel_url=home_url('/hotels');
$tour_url=home_url('/tours');
$visa_url=home_url('/visa');
$contact_url=home_url('/contact');
$theme_uri=get_template_directory_uri();
$o=\Avanik\ThemeSettings::get();
$services=[
 ['service_insurance_image','بیمه مسافرتی','بیمه مسافرتی با پوشش کامل',$contact_url],
 ['service_visa_image','ویزای مسافرتی','اخذ ویزا با بهترین قیمت',$visa_url],
 ['service_tour_image','تورهای مسافرتی','تورهای داخلی و خارجی',$tour_url],
 ['service_hotel_image','رزرو هتل','هتل‌های ایران و جهان',$hotel_url],
 ['service_flight_image','خرید بلیط هواپیما','پروازهای داخلی و خارجی',$flight_url],
];
$tours=[
 ['tour_antalya_image','تور آنتالیا','۳ شب و ۴ روز','۱۱,۹۰۰,۰۰۰ تومان'],
 ['tour_dubai_image','تور دبی','۴ شب و ۵ روز','۱۸,۵۰۰,۰۰۰ تومان'],
 ['tour_istanbul_image','تور استانبول','۳ شب و ۴ روز','۱۲,۹۰۰,۰۰۰ تومان'],
 ['tour_paris_image','تور پاریس','۴ شب و ۵ روز','۲۸,۹۰۰,۰۰۰ تومان'],
];
?>
<div class="av-home av-reference-home" dir="rtl">
  <section class="av-hero av-hero--home" aria-labelledby="av-home-title">
    <div class="av-hero__overlay" aria-hidden="true"></div>
    <div class="av-container av-hero__content av-reveal">
      <h1 id="av-home-title" class="av-hero__title"><?php echo esc_html($o['hero_title']); ?></h1>
      <p class="av-hero__subtitle"><?php echo esc_html($o['hero_subtitle']); ?></p>
      <div class="av-hero__headline-rule"><span></span><b aria-hidden="true">✈</b></div>
      <a class="av-btn av-btn--primary av-hero__cta" href="<?php echo esc_url($flight_url); ?>">رزرو آنلاین</a>
    </div>
  </section>

  <section class="av-hero__search av-reveal" aria-label="جستجوی سفر">
    <div class="av-search-card">
      <div class="av-search-tabs" role="tablist" aria-label="نوع خدمت">
        <button class="av-search-tab is-active" data-av-search-tab="flight-domestic" type="button" role="tab" aria-selected="true"><span>✈</span>پرواز داخلی</button>
        <button class="av-search-tab" data-av-search-tab="flight-international" type="button" role="tab" aria-selected="false"><span>✈</span>پرواز خارجی</button>
        <button class="av-search-tab" data-av-search-tab="hotel-domestic" type="button" role="tab" aria-selected="false"><span>▣</span>هتل داخلی</button>
        <button class="av-search-tab" data-av-search-tab="hotel-international" type="button" role="tab" aria-selected="false"><span>▣</span>هتل خارجی</button>
        <button class="av-search-tab" data-av-search-tab="tour" type="button" role="tab" aria-selected="false"><span>◈</span>تورهای مسافرتی</button>
      </div>
      <div class="av-search-options" data-av-flight-only>
        <label class="av-radio"><input type="radio" name="trip_type" value="oneway" checked> رفت و برگشت</label>
        <label class="av-radio"><input type="radio" name="trip_type" value="roundtrip"> یک طرفه</label>
      </div>
      <form class="av-search-form" data-av-home-search data-av-action="/flights" action="<?php echo esc_url($flight_url); ?>" method="get">
        <label class="av-field av-field--origin" data-av-flight-only><span class="av-field__label">مبدا</span><span class="av-field__value"><input type="text" name="origin" placeholder="تهران (همه فرودگاه‌ها)" required><b>⌄</b></span></label>
        <label class="av-field av-field--destination" data-av-flight-only><span class="av-field__label">مقصد</span><span class="av-field__value"><input type="text" name="destination" placeholder="استانبول" required><b>⌖</b></span></label>
        <label class="av-field" data-av-flight-only><span class="av-field__label">تاریخ رفت</span><span class="av-field__value"><input type="text" class="av-jalali-input" data-jalali-date name="departure_jalali" placeholder="۱۴۰۵/۰۵/۲۷" autocomplete="off" required><input type="hidden" name="departure"><b>▣</b></span></label>
        <label class="av-field" data-av-flight-only><span class="av-field__label">تاریخ برگشت</span><span class="av-field__value"><input type="text" class="av-jalali-input" data-jalali-date name="return_jalali" placeholder="۱۴۰۴/۰۶/۰۳" autocomplete="off"><input type="hidden" name="return"><b>▣</b></span></label>
        <div class="av-field av-passenger-field" data-av-flight-only data-av-passenger><span class="av-field__label">مسافر</span><button type="button" class="av-passenger-trigger" data-av-passenger-open><span data-av-passenger-summary>۱ مسافر</span><b>⌄</b></button><div class="av-passenger-popover" data-av-passenger-popover hidden><div class="av-passenger-row"><div><strong>بزرگسال</strong><small>۱۲ سال به بالا</small></div><div class="av-stepper"><button type="button" data-av-passenger-minus="adult" aria-label="کاهش بزرگسال">−</button><b data-av-passenger-count="adult">۱</b><button type="button" data-av-passenger-plus="adult" aria-label="افزایش بزرگسال">+</button></div></div><div class="av-passenger-row"><div><strong>کودک</strong><small>۲ تا ۱۱ سال</small></div><div class="av-stepper"><button type="button" data-av-passenger-minus="child" aria-label="کاهش کودک">−</button><b data-av-passenger-count="child">۰</b><button type="button" data-av-passenger-plus="child" aria-label="افزایش کودک">+</button></div></div><div class="av-child-ages" data-av-child-ages></div><button class="av-btn av-btn--primary av-passenger-done" type="button" data-av-passenger-done>تأیید</button><input type="hidden" name="adults" data-av-adults value="1"><input type="hidden" name="children" data-av-children value="0"></div></div>
        <label class="av-field av-field--wide" data-av-hotel-only><span class="av-field__label">مقصد / هتل</span><span class="av-field__value"><input type="text" name="hotel_destination" placeholder="استانبول، کیش، مشهد…"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">تاریخ ورود</span><span class="av-field__value"><input type="text" class="av-jalali-input" data-jalali-date name="checkin_jalali" placeholder="۱۴۰۵/۰۵/۲۷"><input type="hidden" name="checkin"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">تاریخ خروج</span><span class="av-field__value"><input type="text" class="av-jalali-input" data-jalali-date name="checkout_jalali" placeholder="۱۴۰۵/۰۵/۳۰"><input type="hidden" name="checkout"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">اتاق</span><span class="av-field__value"><select name="rooms"><option value="1">۱ اتاق، ۲ بزرگسال</option><option value="2">۲ اتاق، ۴ بزرگسال</option></select></span></label>
        <label class="av-field av-field--wide" data-av-tour-only><span class="av-field__label">مقصد تور</span><span class="av-field__value"><select name="tour_destination"><option>استانبول</option><option>دبی</option><option>پاریس</option><option>آنتالیا</option></select></span></label>
        <label class="av-field av-field--wide" data-av-tour-only><span class="av-field__label">تعداد مسافر</span><span class="av-field__value"><select name="tour_passengers"><option>۲ نفر</option><option>۳ نفر</option><option>۴ نفر</option></select></span></label>
        <button class="av-btn av-btn--primary av-search-submit" id="av-search-submit" data-av-search-submit type="submit">جستجو <span>⌕</span></button>
      </form>
    </div>
  </section>

  <section class="av-services av-container" aria-labelledby="av-services-title">
    <div class="av-section-heading av-section-heading--center"><div><h2 id="av-services-title">خدمات ما</h2><span></span></div></div>
    <div class="av-service-grid"><?php foreach($services as $s): ?><a class="av-service-card av-service-card--link av-reveal" href="<?php echo esc_url($s[3]); ?>"><div class="av-service-card__image"><img src="<?php echo esc_url($o[$s[0]]); ?>" alt="<?php echo esc_attr($s[1]); ?>" loading="lazy"></div><h2 class="av-service-card__title"><?php echo esc_html($s[1]); ?></h2><p class="av-service-card__text"><?php echo esc_html($s[2]); ?></p><span class="av-service-card__link">مشاهده بیشتر ←</span></a><?php endforeach; ?></div>
  </section>

  <?php if((int)$o['show_tours']===1): ?><section class="av-featured-tours av-container" aria-labelledby="av-tours-title"><div class="av-section-heading av-section-heading--center"><div><h2 id="av-tours-title">تورهای ویژه</h2><span></span></div></div><div class="av-tour-grid"><?php foreach($tours as $t): ?><a class="av-tour-card av-reveal" href="<?php echo esc_url($tour_url); ?>?destination=<?php echo rawurlencode($t[1]); ?>"><img src="<?php echo esc_url($o[$t[0]]); ?>" alt="تور <?php echo esc_attr($t[1]); ?>" loading="lazy"><div class="av-tour-card__caption"><strong>تور <?php echo esc_html($t[1]); ?></strong><span><?php echo esc_html($t[2]); ?></span><b><?php echo esc_html($t[3]); ?></b></div><i aria-hidden="true">›</i></a><?php endforeach; ?></div><a class="av-view-all" href="<?php echo esc_url($tour_url); ?>">مشاهده همه تورها</a></section><?php endif; ?>

  <?php if((int)$o['show_why']===1): ?><section class="av-why" aria-labelledby="av-why-title"><div class="av-container"><div class="av-section-heading av-section-heading--center"><div><h2 id="av-why-title">چرا آوانیک پرواز آسیا؟</h2><span></span></div></div><div class="av-why-grid"><div class="av-why-item av-reveal"><i>★</i><strong>تجربه و اعتبار</strong><span>بهترین خدمات مسافرتی با پوشش کامل</span></div><div class="av-why-item av-reveal"><i>▣</i><strong>قیمت تضمینی</strong><span>امکان پرداخت آنلاین با امنیت بالا</span></div><div class="av-why-item av-reveal"><i>♢</i><strong>پرداخت امن</strong><span>امکان پرداخت آنلاین با امنیت بالا</span></div><div class="av-why-item av-reveal"><i>◉</i><strong>تجربه دلچسب</strong><span>پشتیبانی و همراهی در مسیر سفر</span></div></div></div></section><?php endif; ?>
</div>
<?php get_footer(); ?>