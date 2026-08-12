<?php
/* Template Name: Avanik Hotel Search */
defined('ABSPATH') || exit; get_header(); ?>
<main class="av-hotel-search" dir="rtl"><div class="av-container">
<section class="av-hotel-search-hero"><span class="av-hotel-eyebrow">رزرو هتل</span><h1>هتل مناسب سفرتان را پیدا کنید</h1><p>هتل‌های منتخب آوانیک را جستجو و مقایسه کنید.</p>
<form class="av-hotel-search-form">
<div class="av-hotel-search-field"><label>مقصد</label><input type="text" placeholder="شهر یا نام هتل"></div>
<div class="av-hotel-search-field"><label>ورود</label><input type="text" placeholder="تاریخ ورود"></div>
<div class="av-hotel-search-field"><label>خروج</label><input type="text" placeholder="تاریخ خروج"></div>
<div class="av-hotel-search-field"><label>اتاق و مسافر</label><select><option>۱ اتاق، ۲ بزرگسال</option><option>۲ اتاق، ۴ بزرگسال</option></select></div>
<button class="av-btn av-btn--primary">جستجوی هتل</button></form></section>
<div class="av-hotel-results-layout"><aside class="av-hotel-filters"><div class="av-hotel-filter-header"><h2>فیلترها</h2><button>پاک کردن</button></div>
<div class="av-hotel-filter-group"><h3>محدوده قیمت</h3><label><input type="checkbox"> تا ۵ میلیون تومان</label><label><input type="checkbox"> ۵ تا ۱۰ میلیون تومان</label><label><input type="checkbox"> ۱۰ تا ۲۰ میلیون تومان</label></div>
<div class="av-hotel-filter-group"><h3>درجه هتل</h3><label><input type="checkbox"> ★★★★★</label><label><input type="checkbox"> ★★★★</label><label><input type="checkbox"> ★★★</label></div>
<div class="av-hotel-filter-group"><h3>امکانات</h3><label><input type="checkbox"> صبحانه</label><label><input type="checkbox"> استخر</label><label><input type="checkbox"> وای‌فای رایگان</label><label><input type="checkbox"> پارکینگ</label></div></aside>
<section class="av-hotel-results"><div class="av-hotel-results-toolbar"><div><strong>هتل‌های استانبول</strong><span>۱۲۸ هتل پیدا شد</span></div><select><option>پیشنهاد آوانیک</option><option>کمترین قیمت</option><option>بیشترین امتیاز</option></select></div>
<div class="av-hotel-list">
<article class="av-hotel-card"><div class="av-hotel-card__image">HOTEL IMAGE</div><div class="av-hotel-card__body"><div class="av-hotel-card__rating">★ 4.8 <span>(۳۲۴ نظر)</span></div><h2>هتل نمونه آوانیک استانبول</h2><p class="av-hotel-card__location">استانبول، منطقه تکسیم</p><div class="av-hotel-card__amenities"><span>صبحانه</span><span>استخر</span><span>Wi-Fi</span></div><div class="av-hotel-card__footer"><div><small>شروع قیمت</small><strong>۷,۸۵۰,۰۰۰ تومان</strong><small>برای هر شب</small></div><a class="av-btn av-btn--outline" href="#">مشاهده هتل</a></div></div></article>
<article class="av-hotel-card"><div class="av-hotel-card__image">HOTEL IMAGE</div><div class="av-hotel-card__body"><div class="av-hotel-card__rating">★ 4.6 <span>(۱۸۷ نظر)</span></div><h2>هتل گلدن هورن</h2><p class="av-hotel-card__location">استانبول، شیشلی</p><div class="av-hotel-card__amenities"><span>صبحانه</span><span>Wi-Fi</span><span>پارکینگ</span></div><div class="av-hotel-card__footer"><div><small>شروع قیمت</small><strong>۶,۴۵۰,۰۰۰ تومان</strong><small>برای هر شب</small></div><a class="av-btn av-btn--outline" href="#">مشاهده هتل</a></div></div></article>
</div></section></div></div></main><?php get_footer(); ?>