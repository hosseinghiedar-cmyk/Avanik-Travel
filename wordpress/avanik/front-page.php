<?php get_header(); ?>
<main class="avanik-home">
<section class="avanik-hero" style="--avanik-hero-image:url('<?php echo esc_url(avanik_option('hero',AVANIK_URI.'/assets/images/hero-reference-istanbul.jpg')); ?>')">
  <div class="avanik-hero-bg" aria-hidden="true"></div>
  <div class="avanik-hero-wash" aria-hidden="true"></div>
  <div class="avanik-shell avanik-hero-content">
    <div class="avanik-hero-copy">
      <div class="avanik-eyebrow"><?php echo esc_html(avanik_option('hero_eyebrow','سفر بعدی شما از اینجا شروع می‌شود')); ?></div>
      <h1><?php echo esc_html(avanik_option('hero_title_prefix','پرواز به')); ?> <strong><?php echo esc_html(avanik_option('hero_title_accent','استانبول')); ?></strong></h1>
      <p><?php echo esc_html(avanik_option('hero_subtitle','با بهترین قیمت و خدمات ویژه')); ?></p>
    </div>
  </div>
</section>

<section class="avanik-search-card" aria-label="جستجوی خدمات سفر">
  <div class="avanik-shell">
    <div class="avanik-search-tabs" role="tablist" aria-label="نوع خدمات">
      <button class="active" type="button" data-service="domestic-flight" aria-selected="true"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 14l8.5-2.4L13 4l2.5-.7 1.2 7.3L22 9.2l1 .9-6.1 4.2.8 4.6-1.8.5-3.3-3.8-8.2 2.3z"/></svg>پرواز داخلی</button>
      <button type="button" data-service="foreign-flight" aria-selected="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 14l8.5-2.4L13 4l2.5-.7 1.2 7.3L22 9.2l1 .9-6.1 4.2.8 4.6-1.8.5-3.3-3.8-8.2 2.3z"/></svg>پرواز خارجی</button>
      <button type="button" data-service="domestic-tour" aria-selected="false"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="6" width="16" height="14" rx="2"/><path d="M8 6V4h8v2M8 10h8M8 14h5"/></svg>تور داخلی</button>
      <button type="button" data-service="foreign-tour" aria-selected="false"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="6" width="16" height="14" rx="2"/><path d="M8 6V4h8v2M8 10h8M8 14h5"/></svg>تور خارجی</button>
      <button type="button" data-service="hotel" aria-selected="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20V6h16v14M4 12h16M8 9h3v3H8zM14 9h3v3h-3z"/></svg>هتل</button>
    </div>

    <form class="avanik-search-form" onsubmit="return AvanikSearch.submit(event)">
      <label class="avanik-field avanik-field--location" data-city-field="origin">
        <span>مبدا</span><div class="avanik-input-wrap"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-6.1 7-12a7 7 0 1 0-14 0c0 5.9 7 12 7 12z"/><circle cx="12" cy="9" r="2.2"/></svg><button class="avanik-city-trigger" type="button" data-city-trigger="origin"><span data-city-label="origin">تهران (همه فرودگاه‌ها)</span><span>⌄</span></button></div>
        <input type="hidden" name="origin" value="tehran" data-city-value="origin"><div class="avanik-city-menu" data-city-menu="origin"></div>
      </label>
      <label class="avanik-field avanik-field--location" data-city-field="destination">
        <span>مقصد</span><div class="avanik-input-wrap"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-6.1 7-12a7 7 0 1 0-14 0c0 5.9 7 12 7 12z"/><circle cx="12" cy="9" r="2.2"/></svg><button class="avanik-city-trigger" type="button" data-city-trigger="destination"><span data-city-label="destination">مشهد</span><span>⌄</span></button></div>
        <input type="hidden" name="destination" value="mashhad" data-city-value="destination"><div class="avanik-city-menu" data-city-menu="destination"></div>
      </label>
      <label class="avanik-field avanik-field--date"><span>تاریخ رفت</span><div class="avanik-input-wrap"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4M17 3v4M3 10h18"/></svg><button class="avanik-date-trigger" type="button" data-date-open="departure"><span data-date-label="departure">امروز</span><span>⌄</span></button></div><input type="hidden" name="departure" data-date-value="departure"></label>
      <label class="avanik-field avanik-field--date"><span>تاریخ برگشت</span><div class="avanik-input-wrap"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4M17 3v4M3 10h18"/></svg><button class="avanik-date-trigger" type="button" data-date-open="return"><span data-date-label="return">امروز</span><span>⌄</span></button></div><input type="hidden" name="return" data-date-value="return"></label>
      <button class="avanik-swap-btn" type="button" aria-label="جابجایی مبدا و مقصد"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 7h11l-3-3M17 17H6l3 3"/></svg></button>
      <div class="avanik-passenger-field avanik-field"><span>مسافر</span><button type="button" class="avanik-passenger-trigger" aria-expanded="false"><span><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.2"/><path d="M5 20c.7-3.2 3-5 7-5s6.3 1.8 7 5"/></svg><b class="avanik-passenger-total">۱ مسافر</b></span><b class="avanik-chevron">⌄</b></button>
        <div class="avanik-passenger-popover" aria-hidden="true">
          <div class="avanik-passenger-row"><div><strong>بزرگسال</strong><small>۱۲ سال به بالا</small></div><div class="avanik-stepper"><button type="button" data-pass="adult" data-step="-1">−</button><b id="adult-count">۱</b><button type="button" data-pass="adult" data-step="1">+</button></div></div>
          <div class="avanik-passenger-row"><div><strong>کودک</strong><small>۲ تا ۱۱ سال</small></div><div class="avanik-stepper"><button type="button" data-pass="child" data-step="-1">−</button><b id="child-count">۰</b><button type="button" data-pass="child" data-step="1">+</button></div></div>
          <div class="avanik-passenger-row"><div><strong>نوزاد</strong><small>زیر ۲ سال</small></div><div class="avanik-stepper"><button type="button" data-pass="infant" data-step="-1">−</button><b id="infant-count">۰</b><button type="button" data-pass="infant" data-step="1">+</button></div></div>
          <button type="button" class="avanik-passenger-done">تأیید</button>
        </div>
      </div>
      <button class="avanik-search-btn" type="submit"><span>جستجو</span><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.5"/><path d="M16 16l5 5"/></svg></button>
    </form>
  </div>
</section>

<div class="avanik-date-popover" data-date-popover aria-hidden="true">
  <div class="avanik-date-head"><strong>انتخاب تاریخ</strong><button type="button" data-date-close aria-label="بستن">×</button></div>
  <div class="avanik-date-switch"><button type="button" class="active" data-date-mode="jalali">شمسی</button><button type="button" data-date-mode="gregorian">میلادی</button></div>
  <div class="avanik-calendar-head"><button type="button" data-cal-prev aria-label="ماه قبل">‹</button><strong data-cal-title></strong><button type="button" data-cal-next aria-label="ماه بعد">›</button></div>
  <div class="avanik-calendar-week"><span>ش</span><span>ی</span><span>د</span><span>س</span><span>چ</span><span>پ</span><span>ج</span></div><div class="avanik-calendar-grid" data-cal-grid></div>
</div>

<section class="avanik-section avanik-services"><div class="avanik-shell"><div class="avanik-section-title"><h2>خدمات ما</h2><span></span></div><div class="avanik-service-grid">
<?php $services=[['✈','خرید بلیط هواپیما','پروازهای داخلی و خارجی'],['▣','رزرو هتل','هتل‌های ایران و جهان'],['▱','تورهای مسافرتی','تورهای داخلی و خارجی'],['▤','ویزای مسافرتی','اخذ ویزا با بهترین قیمت'],['◈','بیمه مسافرتی','بیمه مسافرتی با پوشش کامل']]; foreach($services as $s): ?><a class="avanik-service-card" href="#"><i><?php echo $s[0]; ?></i><h3><?php echo esc_html($s[1]); ?></h3><p><?php echo esc_html($s[2]); ?></p><span>مشاهده بیشتر ←</span></a><?php endforeach; ?></div></div></section>
<section class="avanik-section avanik-destinations"><div class="avanik-shell"><div class="avanik-section-title"><h2>مقصدهای محبوب</h2><span></span></div><div class="avanik-destination-grid">
<?php $cities=[['استانبول','استانبول','assets/images/hero-istanbul.svg'],['پاریس','برج ایفل','assets/images/destination-paris.svg'],['لندن','لندن','assets/images/destination-london.svg'],['نیویورک','نیویورک','assets/images/destination-newyork.svg'],['دبی','دبی','assets/images/destination-dubai.svg'],['آنتالیا','آنتالیا','assets/images/destination-antalya.svg']]; foreach($cities as $c): ?><a class="avanik-destination-card" href="#"><img src="<?php echo esc_url(AVANIK_URI.'/'.$c[2]); ?>" alt="<?php echo esc_attr($c[0]); ?>"><div class="avanik-destination-overlay"><strong>تور <?php echo esc_html($c[0]); ?></strong><small><?php echo esc_html($c[1]); ?></small><span>مشاهده تورها ←</span></div></a><?php endforeach; ?></div><a class="avanik-outline-btn" href="#">مشاهده همه مقصدها</a></div></section>
<section class="avanik-why"><div class="avanik-shell"><div class="avanik-section-title light"><h2>چرا آوانیک پرواز آسیا؟</h2><span></span></div><div class="avanik-why-grid"><div><b>★</b><h3>تجربه و اعتبار</h3><p>سال‌ها تجربه در خدمات سفر و گردشگری</p></div><div><b>✓</b><h3>پرداخت امن</h3><p>امکان پرداخت آنلاین سریع و مطمئن</p></div><div><b>◇</b><h3>قیمت تضمینی</h3><p>بهترین قیمت با پشتیبانی واقعی</p></div><div><b>♧</b><h3>پشتیبانی سریع</h3><p>همراه شما قبل و بعد از سفر</p></div></div></div></section>
</main>
<?php get_footer(); ?>
