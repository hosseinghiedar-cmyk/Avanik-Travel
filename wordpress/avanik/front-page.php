<?php
defined('ABSPATH') || exit;
get_header();
$home_url=home_url('/');$flight_url=home_url('/flights');$hotel_url=home_url('/hotels');$tour_url=home_url('/tours');$visa_url=home_url('/visa');$contact_url=home_url('/contact');$o=\Avanik\ThemeSettings::get();
$services=[
 ['service_flight_image','خرید بلیط هواپیما','پروازهای داخلی و خارجی با امکان مقایسه و رزرو.',$flight_url],
 ['service_hotel_image','رزرو هتل','هتل‌های داخلی و خارجی با انتخاب بر اساس قیمت و امتیاز.',$hotel_url],
 ['service_tour_image','تورهای مسافرتی','تورهای داخلی و خارجی برای مقاصد محبوب و ویژه.',$tour_url],
 ['service_visa_image','ویزای مسافرتی','اخذ ویزا با مشاوره و پیگیری مراحل درخواست.',$visa_url],
 ['service_insurance_image','بیمه مسافرتی','پوشش مناسب برای سفرهای داخلی و خارجی.',$contact_url],
];
$flight_dests=[
 ['hero-istanbul.svg','تهران'],['destination-kish.svg','مشهد'],['destination-istanbul.svg','شیراز'],['hero-paris.svg','اصفهان'],
 ['destination-dubai.svg','کیش'],['hero-dubai.svg','تبریز'],['destination-istanbul.svg','استانبول'],['destination-dubai.svg','دبی'],
];
?>
<div class="av-home av-reference-home" dir="rtl">
  <section class="av-hero av-hero--home" data-av-hero-slider data-av-hero-interval="<?php echo esc_attr($o['hero_interval']); ?>" aria-labelledby="av-home-title">
    <div class="av-hero__slides" aria-hidden="true">
      <?php foreach(['hero_image','hero_image_2','hero_image_3'] as $i=>$key): ?><div class="av-hero__slide <?php echo $i===0?'is-active':''; ?>" style="background-image:url('<?php echo esc_url($o[$key]); ?>')"></div><?php endforeach; ?>
    </div>
    <div class="av-hero__overlay" aria-hidden="true"></div>
    <div class="av-container av-hero__content av-reveal">
      <span class="av-hero__eyebrow">AVANIK PARVAZ ASIA</span>
      <h1 id="av-home-title" class="av-hero__title"><?php echo esc_html($o['hero_title']); ?></h1>
      <p class="av-hero__subtitle"><?php echo esc_html($o['hero_subtitle']); ?></p>
      <div class="av-hero__headline-rule"><span></span><b aria-hidden="true">✈</b></div>
      <a class="av-btn av-btn--primary av-hero__cta" href="<?php echo esc_url($flight_url); ?>">رزرو آنلاین</a>
    </div>
    <div class="av-hero__dots" aria-hidden="true"><i class="is-active"></i><i></i><i></i></div>
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
      <div class="av-search-heading"><div><span>جستجوی هوشمند سفر</span><strong data-av-search-title>پرواز داخلی</strong></div><small>مبدا، مقصد، تاریخ و تعداد مسافران را انتخاب کنید.</small></div>
      <div class="av-search-options" data-av-flight-only><label class="av-radio"><input type="radio" name="trip_type" value="oneway" checked> یک طرفه</label><label class="av-radio"><input type="radio" name="trip_type" value="roundtrip"> رفت و برگشت</label></div>
      <form class="av-search-form" data-av-home-search data-av-action="/flights" action="<?php echo esc_url($flight_url); ?>" method="get">
        <label class="av-field" data-av-flight-only><span class="av-field__label">مبدا</span><span class="av-field__value"><input type="text" name="origin" placeholder="تهران (همه فرودگاه‌ها)" required></span></label>
        <label class="av-field" data-av-flight-only><span class="av-field__label">مقصد</span><span class="av-field__value"><input type="text" name="destination" placeholder="استانبول" required></span></label>
        <label class="av-field" data-av-flight-only><span class="av-field__label">تاریخ رفت</span><span class="av-field__value"><input type="text" class="av-jalali-input" data-jalali-date name="departure_jalali" placeholder="۱۴۰۵/۰۵/۲۲" autocomplete="off" required><input type="hidden" name="departure"></span></label>
        <div class="av-field av-passenger-field" data-av-flight-only data-av-passenger><span class="av-field__label">مسافر</span><button type="button" class="av-passenger-trigger" data-av-passenger-open><span data-av-passenger-summary>۱ بزرگسال</span><b>⌄</b></button><div class="av-passenger-popover" data-av-passenger-popover hidden><div class="av-passenger-row"><div><strong>بزرگسال</strong><small>۱۲ سال به بالا</small></div><div class="av-stepper"><button type="button" data-av-passenger-minus="adult" aria-label="کاهش بزرگسال">−</button><b data-av-passenger-count="adult">۱</b><button type="button" data-av-passenger-plus="adult" aria-label="افزایش بزرگسال">+</button></div></div><div class="av-passenger-row"><div><strong>کودک</strong><small>۲ تا ۱۱ سال</small></div><div class="av-stepper"><button type="button" data-av-passenger-minus="child" aria-label="کاهش کودک">−</button><b data-av-passenger-count="child">۰</b><button type="button" data-av-passenger-plus="child" aria-label="افزایش کودک">+</button></div></div><div class="av-child-ages" data-av-child-ages></div><button class="av-btn av-btn--primary av-passenger-done" type="button" data-av-passenger-done>تأیید</button><input type="hidden" name="adults" data-av-adults value="1"><input type="hidden" name="children" data-av-children value="0"></div></div>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">مقصد / هتل</span><span class="av-field__value"><input type="text" name="hotel_destination" placeholder="استانبول، کیش، مشهد…"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">تاریخ ورود</span><span class="av-field__value"><input type="text" class="av-jalali-input" data-jalali-date name="checkin_jalali" placeholder="۱۴۰۵/۰۵/۲۲"><input type="hidden" name="checkin"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">تاریخ خروج</span><span class="av-field__value"><input type="text" class="av-jalali-input" data-jalali-date name="checkout_jalali" placeholder="۱۴۰۵/۰۵/۲۵"><input type="hidden" name="checkout"></span></label>
        <label class="av-field" data-av-hotel-only><span class="av-field__label">اتاق</span><span class="av-field__value"><select name="rooms"><option value="1">۱ اتاق، ۲ بزرگسال</option><option value="2">۲ اتاق، ۴ بزرگسال</option></select></span></label>
        <label class="av-field av-field--wide" data-av-tour-only><span class="av-field__label">مقصد تور</span><span class="av-field__value"><select name="tour_destination"><option>استانبول</option><option>دبی</option><option>پاریس</option><option>آنتالیا</option></select></span></label>
        <label class="av-field av-field--wide" data-av-tour-only><span class="av-field__label">تعداد مسافر</span><span class="av-field__value"><select name="tour_passengers"><option>۲ نفر</option><option>۳ نفر</option><option>۴ نفر</option></select></span></label>
        <button class="av-btn av-btn--primary av-search-submit" id="av-search-submit" data-av-search-submit type="submit">جستجو 🔎</button>
      </form>
      <div class="av-search-note">تاریخ‌ها شمسی هستند؛ هنگام ارسال فرم به تاریخ میلادی تبدیل می‌شوند. ساختار برای اتصال به API واقعی آماده است.</div>
    </div>
  </section>

  <section class="av-reference-flight-dests av-container" aria-labelledby="av-flight-dests-title">
    <div class="av-reference-heading"><h2 id="av-flight-dests-title">بلیط هواپیما</h2><strong>رزرو بلیط هواپیما داخلی و خارجی</strong></div>
    <div class="av-flight-dest-grid">
      <?php foreach($flight_dests as $i=>$d): ?><a class="av-flight-dest av-reveal" href="<?php echo esc_url($flight_url); ?>?destination=<?php echo rawurlencode($d[1]); ?>"><img src="<?php echo esc_url($theme_uri.'/assets/images/'.$d[0]); ?>" alt="بلیط هواپیما <?php echo esc_attr($d[1]); ?>" loading="lazy"><span>بلیط هواپیما <?php echo esc_html($d[1]); ?></span></a><?php endforeach; ?>
    </div>
  </section>

  <section class="av-trust-strip" aria-label="مزیت‌های رزرو آنلاین"><div class="av-container av-trust-strip__grid"><div class="av-trust-pill av-reveal"><b>✓</b><div><strong>موجودی کامل</strong><span>انتخاب‌های متنوع برای سفر</span></div></div><div class="av-trust-pill av-reveal"><b>▣</b><div><strong>تجربه دلچسب خرید</strong><span>فرآیند ساده و سریع رزرو</span></div></div><div class="av-trust-pill av-reveal"><b>▰</b><div><strong>مشاور در مدیریت هزینه</strong><span>پیشنهادهای متناسب با بودجه</span></div></div><div class="av-trust-pill av-reveal"><b>◉</b><div><strong>پشتیبانی سفر</strong><span><?php echo esc_html($o['support']); ?></span></div></div></div></section>

  <section class="av-services av-container"><div class="av-section-heading"><div><span>خدمات ما</span><h2>همه خدمات سفر، یکجا</h2></div><a href="<?php echo esc_url($contact_url); ?>">مشاوره سفر ←</a></div><div class="av-service-grid"><?php foreach($services as $s): ?><a class="av-service-card av-service-card--link av-reveal" href="<?php echo esc_url($s[3]); ?>"><div class="av-service-card__image"><img src="<?php echo esc_url($o[$s[0]]); ?>" alt="<?php echo esc_attr($s[1]); ?>" loading="lazy"></div><h2 class="av-service-card__title"><?php echo esc_html($s[1]); ?></h2><p class="av-service-card__text"><?php echo esc_html($s[2]); ?></p><span class="av-service-card__link">مشاهده بیشتر ←</span></a><?php endforeach; ?></div></section>

  <?php if((int)$o['show_tours']===1): ?><section class="av-featured-tours av-container"><div class="av-section-heading"><div><span>تورهای ویژه</span><h2>پیشنهادهای منتخب آوانیک</h2></div><a href="<?php echo esc_url($tour_url); ?>">مشاهده همه تورها ←</a></div><div class="av-tour-grid">
  <?php $tours=[['tour_istanbul_image','استانبول','۳ شب و ۴ روز','۱۲,۹۰۰,۰۰۰ تومان'],['tour_dubai_image','دبی','۴ شب و ۵ روز','۱۸,۵۰۰,۰۰۰ تومان'],['tour_paris_image','پاریس','۴ شب و ۵ روز','۲۸,۹۰۰,۰۰۰ تومان'],['tour_antalya_image','آنتالیا','۳ شب و ۴ روز','۱۱,۹۰۰,۰۰۰ تومان']];foreach($tours as $t): ?><a class="av-tour-card av-reveal" href="<?php echo esc_url($tour_url); ?>?destination=<?php echo rawurlencode($t[1]); ?>"><img src="<?php echo esc_url($o[$t[0]]); ?>" alt="تور <?php echo esc_attr($t[1]); ?>" loading="lazy"><div><strong>تور <?php echo esc_html($t[1]); ?></strong><span><?php echo esc_html($t[2]); ?></span><b><?php echo esc_html($t[3]); ?></b></div></a><?php endforeach; ?></div></section><?php endif; ?>

  <section class="av-destinations av-container"><div class="av-section-heading"><div><span>مقاصد محبوب</span><h2>برای سفر بعدی از کجا شروع کنیم؟</h2></div><a href="<?php echo esc_url($tour_url); ?>">همه مقاصد ←</a></div><div class="av-destination-grid"><?php $dests=[['destination_istanbul_image','استانبول','تور، هتل و پرواز'],['destination_dubai_image','دبی','یک سفر مدرن و متفاوت'],['destination_kish_image','کیش','سفر ساحلی در ایران']];foreach($dests as $d): ?><a class="av-destination-card av-reveal" href="<?php echo esc_url($tour_url); ?>?destination=<?php echo rawurlencode($d[1]); ?>"><img src="<?php echo esc_url($o[$d[0]]); ?>" alt="<?php echo esc_attr($d[1]); ?>" loading="lazy"><div><strong><?php echo esc_html($d[1]); ?></strong><span><?php echo esc_html($d[2]); ?></span></div></a><?php endforeach; ?></div></section>

  <?php if((int)$o['show_why']===1): ?><section class="av-why"><div class="av-container"><div class="av-section-heading av-section-heading--center"><div><span>تجربه بهتر سفر</span><h2>چرا آوانیک پرواز آسیا؟</h2></div></div><div class="av-why-grid"><div class="av-why-item av-reveal"><i>★</i><strong>تجربه و اعتبار</strong><span>رزرو مطمئن با تجربه‌ای ساده</span></div><div class="av-why-item av-reveal"><i>⌁</i><strong>پرداخت امن</strong><span>ساختار آماده اتصال به درگاه</span></div><div class="av-why-item av-reveal"><i>✓</i><strong>انتخاب هوشمند</strong><span>مقایسه خدمات و قیمت قبل از رزرو</span></div><div class="av-why-item av-reveal"><i>✦</i><strong>پشتیبانی</strong><span><?php echo esc_html($o['support']); ?></span></div></div></div></section><?php endif; ?>

  <section class="av-guides av-container"><div class="av-section-heading"><div><span>راهنمای سفر</span><h2>قبل از رزرو، هوشمندانه انتخاب کنید</h2></div></div><div class="av-guide-grid"><article class="av-guide-card av-reveal"><small>پرواز</small><h3>چطور بلیط مناسب‌تر پیدا کنیم؟</h3><p>مقایسه قیمت، ساعت حرکت، تعداد توقف و شرایط استرداد را در یک نگاه بررسی کنید.</p><a href="<?php echo esc_url($flight_url); ?>">جستجوی پرواز ←</a></article><article class="av-guide-card av-reveal"><small>هتل</small><h3>انتخاب هتل فقط با قیمت نیست</h3><p>امتیاز، موقعیت، امکانات و نوع اتاق را کنار قیمت بررسی کنید تا انتخاب بهتری داشته باشید.</p><a href="<?php echo esc_url($hotel_url); ?>">جستجوی هتل ←</a></article><article class="av-guide-card av-reveal"><small>تور</small><h3>تور را مطابق سبک سفرتان انتخاب کنید</h3><p>مقصد، مدت اقامت، خدمات پکیج و بودجه را قبل از رزرو با هم مقایسه کنید.</p><a href="<?php echo esc_url($tour_url); ?>">مشاهده تورها ←</a></article></div></section>

  <?php if((int)$o['show_airlines']===1): ?><section class="av-airlines av-container"><div class="av-section-heading"><div><span>همکاران</span><h2>ایرلاین‌های منتخب</h2></div></div><div class="av-airlines__grid"><div class="av-airline">آتا</div><div class="av-airline">ماهان</div><div class="av-airline">معراج</div><div class="av-airline">Emirates</div><div class="av-airline">Qatar Airways</div><div class="av-airline">Turkish Airlines</div><div class="av-airline">flydubai</div><div class="av-airline">Air Arabia</div></div></section><?php endif; ?>
</div>
<?php get_footer(); ?>