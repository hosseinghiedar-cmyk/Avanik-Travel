<?php
/** Template Name: Avanik Flight Search */
defined('ABSPATH') || exit;
get_header();
$flights = [
 ['airline'=>'ماهان','code'=>'W5 1020','origin'=>'تهران','destination'=>'مشهد','depart'=>'08:30','arrive'=>'09:55','duration'=>'1:25','stops'=>'مستقیم','price'=>'5,870,000'],
 ['airline'=>'آتا','code'=>'I3 6210','origin'=>'تهران','destination'=>'مشهد','depart'=>'10:15','arrive'=>'11:45','duration'=>'1:30','stops'=>'مستقیم','price'=>'4,950,000'],
 ['airline'=>'معراج','code'=>'MRJ 421','origin'=>'تهران','destination'=>'مشهد','depart'=>'14:20','arrive'=>'15:45','duration'=>'1:25','stops'=>'مستقیم','price'=>'5,420,000'],
 ['airline'=>'Emirates','code'=>'EK 978','origin'=>'تهران','destination'=>'دبی','depart'=>'18:40','arrive'=>'21:05','duration'=>'2:25','stops'=>'مستقیم','price'=>'18,900,000'],
]; ?>
<main class="av-flight-page"><div class="av-container">
<section class="av-flight-search-bar"><div><strong>تهران</strong> → <strong>مشهد</strong></div><div>تاریخ: ۱۴۰۵/۰۵/۲۰</div><div>۱ بزرگسال</div><a class="av-btn av-btn--outline" href="<?php echo esc_url(home_url('/')); ?>">ویرایش جستجو</a></section>
<div class="av-flight-layout"><aside class="av-flight-filters"><div class="av-filter-card"><h2>فیلتر نتایج</h2><div class="av-filter-group"><span>بازه قیمت</span><input type="range" min="1" max="100" value="70"></div><div class="av-filter-group"><span>ایرلاین</span><label><input type="checkbox" checked> ماهان</label><label><input type="checkbox"> آتا</label><label><input type="checkbox"> معراج</label><label><input type="checkbox"> Emirates</label></div><div class="av-filter-group"><span>نوع پرواز</span><label><input type="checkbox" checked> مستقیم</label><label><input type="checkbox"> با توقف</label></div></div></aside>
<section class="av-flight-results"><div class="av-results-head"><strong><?php echo count($flights); ?> پرواز پیدا شد</strong><label>مرتب‌سازی: <select><option>پیشنهاد آوانیک</option><option>ارزان‌ترین</option><option>سریع‌ترین</option><option>زودترین پرواز</option></select></label></div>
<?php foreach($flights as $f): ?><article class="av-flight-card"><div class="av-flight-airline"><div class="av-airline-mark"><?php echo esc_html(mb_substr($f['airline'],0,1)); ?></div><strong><?php echo esc_html($f['airline']); ?></strong><small><?php echo esc_html($f['code']); ?></small></div><div class="av-flight-time"><strong><?php echo esc_html($f['depart']); ?></strong><span><?php echo esc_html($f['origin']); ?></span></div><div class="av-flight-duration"><span><?php echo esc_html($f['duration']); ?></span><i></i><small><?php echo esc_html($f['stops']); ?></small></div><div class="av-flight-time"><strong><?php echo esc_html($f['arrive']); ?></strong><span><?php echo esc_html($f['destination']); ?></span></div><div class="av-flight-price"><strong><?php echo esc_html($f['price']); ?></strong><small>تومان</small><a class="av-btn av-btn--primary" href="<?php echo esc_url(home_url('/flight-details')); ?>">انتخاب</a></div></article><?php endforeach; ?></section></div></div></main>
<?php get_footer(); ?>
