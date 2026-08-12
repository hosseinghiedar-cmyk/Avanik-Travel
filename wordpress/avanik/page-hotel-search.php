<?php
/* Template Name: Avanik Hotel Search */
defined('ABSPATH') || exit;
get_header();
$theme_uri = get_template_directory_uri();
$hotel_details = home_url('/hotel-details');
?>
<main class="av-hotel-search" dir="rtl"><div class="av-container">
<section class="av-hotel-search-hero"><span class="av-hotel-eyebrow">رزرو هتل</span><h1>هتل مناسب سفرتان را پیدا کنید</h1><p>هتل‌های منتخب آوانیک را جستجو و مقایسه کنید.</p>
<form class="av-hotel-search-form" data-av-hotel-form action="<?php echo esc_url(home_url('/hotels')); ?>" method="get">
<div class="av-hotel-search-field"><label>مقصد</label><input name="destination" type="text" placeholder="شهر یا نام هتل" value="<?php echo isset($_GET['destination']) ? esc_attr(wp_unslash($_GET['destination'])) : ''; ?>"></div>
<div class="av-hotel-search-field"><label>ورود</label><input name="checkin" type="date"></div>
<div class="av-hotel-search-field"><label>خروج</label><input name="checkout" type="date"></div>
<div class="av-hotel-search-field"><label>اتاق و مسافر</label><select name="rooms"><option value="1">۱ اتاق، ۲ بزرگسال</option><option value="2">۲ اتاق، ۴ بزرگسال</option></select></div>
<button class="av-btn av-btn--primary" type="submit">جستجوی هتل</button></form></section>
<div class="av-hotel-results-layout"><aside class="av-hotel-filters"><div class="av-hotel-filter-header"><h2>فیلترها</h2><button type="button" onclick="location.reload()">پاک کردن</button></div>
<div class="av-hotel-filter-group"><h3>محدوده قیمت</h3><label><input type="checkbox"> تا ۵ میلیون تومان</label><label><input type="checkbox"> ۵ تا ۱۰ میلیون تومان</label><label><input type="checkbox"> ۱۰ تا ۲۰ میلیون تومان</label></div>
<div class="av-hotel-filter-group"><h3>درجه هتل</h3><label><input type="checkbox"> ★★★★★</label><label><input type="checkbox"> ★★★★</label><label><input type="checkbox"> ★★★</label></div>
<div class="av-hotel-filter-group"><h3>امکانات</h3><label><input type="checkbox"> صبحانه</label><label><input type="checkbox"> استخر</label><label><input type="checkbox"> وای‌فای رایگان</label><label><input type="checkbox"> پارکینگ</label></div></aside>
<section class="av-hotel-results"><div class="av-hotel-results-toolbar"><div><strong>هتل‌های استانبول</strong><span>۲ هتل نمونه پیدا شد</span></div><select><option>پیشنهاد آوانیک</option><option>کمترین قیمت</option><option>بیشترین امتیاز</option></select></div>
<div class="av-hotel-list">
<article class="av-hotel-card"><div class="av-hotel-card__image av-hotel-card__image--istanbul" style="background-image:url('<?php echo esc_url($theme_uri.'/assets/images/destination-istanbul.svg'); ?>')"></div><div class="av-hotel-card__body"><div class="av-hotel-card__rating">★ 4.8 <span>(۳۲۴ نظر)</span></div><h2>هتل نمونه آوانیک استانبول</h2><p class="av-hotel-card__location">استانبول، منطقه تکسیم</p><div class="av-hotel-card__amenities"><span>صبحانه</span><span>استخر</span><span>Wi-Fi</span></div><div class="av-hotel-card__footer"><div><small>شروع قیمت</small><strong>۷,۸۵۰,۰۰۰ تومان</strong><small>برای هر شب</small></div><button class="av-btn av-btn--outline" type="button" data-av-go="<?php echo esc_url($hotel_details); ?>" data-av-hotel="Avanik Istanbul">مشاهده هتل</button></div></div></article>
<article class="av-hotel-card"><div class="av-hotel-card__image av-hotel-card__image--dubai" style="background-image:url('<?php echo esc_url($theme_uri.'/assets/images/destination-dubai.svg'); ?>')"></div><div class="av-hotel-card__body"><div class="av-hotel-card__rating">★ 4.6 <span>(۱۸۷ نظر)</span></div><h2>هتل گلدن هورن</h2><p class="av-hotel-card__location">استانبول، شیشلی</p><div class="av-hotel-card__amenities"><span>صبحانه</span><span>Wi-Fi</span><span>پارکینگ</span></div><div class="av-hotel-card__footer"><div><small>شروع قیمت</small><strong>۶,۴۵۰,۰۰۰ تومان</strong><small>برای هر شب</small></div><button class="av-btn av-btn--outline" type="button" data-av-go="<?php echo esc_url($hotel_details); ?>" data-av-hotel="Golden Horn">مشاهده هتل</button></div></div></article>
</div></section></div></div></main><?php get_footer(); ?>
