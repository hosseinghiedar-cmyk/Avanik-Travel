<?php get_header(); ?>
<main class="avanik-home">
<section class="avanik-hero"><div class="avanik-hero-bg"></div><div class="avanik-shell avanik-hero-content">
  <div class="avanik-hero-copy"><div class="avanik-eyebrow">سفر بعدی شما از اینجا شروع می‌شود</div><h1>پرواز به <strong>استانبول</strong></h1><p>با بهترین قیمت و خدمات ویژه</p><a class="avanik-primary-btn" href="<?php echo esc_url(home_url('/رزرو-پرواز/')); ?>">رزرو آنلاین <span>←</span></a></div>
</div></section>
<section class="avanik-search-card"><div class="avanik-shell">
  <div class="avanik-search-tabs"><button class="active">✈ پرواز داخلی</button><button>✈ پرواز خارجی</button><button>▣ تور داخلی</button><button>▣ تور خارجی</button><button>▤ هتل</button></div>
  <form class="avanik-search-form" onsubmit="return AvanikSearch.submit(event)">
    <label>مبدا<select><option>تهران (همه فرودگاه‌ها)</option><option>مشهد</option><option>شیراز</option></select></label>
    <label>مقصد<select><option>استانبول</option><option>پاریس</option><option>لندن</option><option>نیویورک</option><option>دبی</option></select></label>
    <label>تاریخ رفت<input value="<?php echo esc_attr(avanik_today_jalali()); ?>" class="jalali-date"></label>
    <label>تاریخ برگشت<input value="<?php echo esc_attr(avanik_today_jalali()); ?>" class="jalali-date"></label>
    <div class="avanik-passenger-field"><span>مسافر</span><button type="button" class="avanik-passenger-trigger">۱ مسافر <b>⌄</b></button><div class="avanik-passenger-popover">
      <div><span>بزرگسال</span><div><button type="button" data-pass="adult" data-step="-1">−</button><b id="adult-count">۱</b><button type="button" data-pass="adult" data-step="1">+</button></div></div>
      <div><span>کودک</span><div><button type="button" data-pass="child" data-step="-1">−</button><b id="child-count">۰</b><button type="button" data-pass="child" data-step="1">+</button></div></div>
    </div></div>
    <button class="avanik-search-btn" type="submit">جستجو <span>⌕</span></button>
  </form>
  <div class="avanik-search-hint">مقصد را انتخاب کنید و بهترین گزینه‌های سفر را ببینید.</div>
</div></section>
<section class="avanik-section avanik-services"><div class="avanik-shell"><div class="avanik-section-title"><h2>خدمات ما</h2><span></span></div><div class="avanik-service-grid">
<?php $services=[['✈','خرید بلیط هواپیما','پروازهای داخلی و خارجی'],['▣','رزرو هتل','هتل‌های ایران و جهان'],['▱','تورهای مسافرتی','تورهای داخلی و خارجی'],['▤','ویزای مسافرتی','اخذ ویزا با بهترین قیمت'],['◈','بیمه مسافرتی','بیمه مسافرتی با پوشش کامل']]; foreach($services as $s): ?><a class="avanik-service-card" href="#"><i><?php echo $s[0]; ?></i><h3><?php echo esc_html($s[1]); ?></h3><p><?php echo esc_html($s[2]); ?></p><span>مشاهده بیشتر ←</span></a><?php endforeach; ?></div></div></section>
<section class="avanik-section avanik-destinations"><div class="avanik-shell"><div class="avanik-section-title"><h2>مقصدهای محبوب</h2><span></span></div><div class="avanik-destination-grid">
<?php $cities=[['استانبول','استانبول','assets/images/hero-istanbul.svg'],['پاریس','برج ایفل','assets/images/destination-paris.svg'],['لندن','لندن','assets/images/destination-london.svg'],['نیویورک','نیویورک','assets/images/destination-newyork.svg'],['دبی','دبی','assets/images/destination-dubai.svg'],['آنتالیا','آنتالیا','assets/images/destination-antalya.svg']]; foreach($cities as $c): ?><a class="avanik-destination-card" href="#"><img src="<?php echo esc_url(AVANIK_URI.'/'.$c[2]); ?>" alt="<?php echo esc_attr($c[0]); ?>"><div class="avanik-destination-overlay"><strong>تور <?php echo esc_html($c[0]); ?></strong><small><?php echo esc_html($c[1]); ?></small><span>مشاهده تورها ←</span></div></a><?php endforeach; ?></div><a class="avanik-outline-btn" href="#">مشاهده همه مقصدها</a></div></section>
<section class="avanik-why"><div class="avanik-shell"><div class="avanik-section-title light"><h2>چرا آوانیک پرواز آسیا؟</h2><span></span></div><div class="avanik-why-grid"><div><b>★</b><h3>تجربه و اعتبار</h3><p>سال‌ها تجربه در خدمات سفر و گردشگری</p></div><div><b>✓</b><h3>پرداخت امن</h3><p>امکان پرداخت آنلاین سریع و مطمئن</p></div><div><b>◇</b><h3>قیمت تضمینی</h3><p>بهترین قیمت با پشتیبانی واقعی</p></div><div><b>♧</b><h3>پشتیبانی سریع</h3><p>همراه شما قبل و بعد از سفر</p></div></div></div></section>
</main>
<?php get_footer(); ?>
