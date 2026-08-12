<?php
/**
 * Template Name: Avanik User Dashboard
 */
defined('ABSPATH') || exit;
get_header();
$flights_url = home_url('/flights');
$dashboard_url = home_url('/dashboard');
$confirmation_url = home_url('/booking-confirmation');
$logout_url = wp_logout_url(home_url('/'));
?>
<main class="av-dashboard-page" dir="rtl">
<div class="av-container">
<section class="av-dashboard-welcome">
<div><span class="av-dashboard-eyebrow">پنل کاربری آوانیک</span><h1>سلام، حسین 👋</h1><p>به پنل کاربری آوانیک خوش آمدید. سفرهای خود را از اینجا مدیریت کنید.</p></div>
<a class="av-btn av-btn--primary" href="<?php echo esc_url($flights_url); ?>">جستجوی سفر جدید</a>
</section>
<section class="av-dashboard-stats">
<div class="av-dashboard-stat"><span>همه رزروها</span><strong>۱۲</strong><small>رزرو ثبت‌شده</small></div><div class="av-dashboard-stat"><span>رزروهای پیش‌رو</span><strong>۲</strong><small>سفر فعال</small></div><div class="av-dashboard-stat"><span>تکمیل‌شده</span><strong>۹</strong><small>سفر انجام‌شده</small></div><div class="av-dashboard-stat"><span>لغوشده</span><strong>۱</strong><small>رزرو لغوشده</small></div>
</section>
<div class="av-dashboard-layout">
<aside class="av-dashboard-sidebar"><div class="av-dashboard-profile"><div class="av-dashboard-avatar">ح</div><div><strong>حسین</strong><span>hossein@example.com</span></div></div>
<nav class="av-dashboard-nav">
<a class="is-active" href="<?php echo esc_url($dashboard_url); ?>">داشبورد</a>
<a href="<?php echo esc_url($dashboard_url.'?tab=bookings'); ?>">رزروهای من</a>
<a href="<?php echo esc_url($dashboard_url.'?tab=account'); ?>">اطلاعات حساب</a>
<a href="<?php echo esc_url($dashboard_url.'?tab=wallet'); ?>">کیف پول</a>
<a href="<?php echo esc_url($dashboard_url.'?tab=password'); ?>">تغییر رمز عبور</a>
<a href="<?php echo esc_url($logout_url); ?>">خروج</a>
</nav></aside>
<section class="av-dashboard-main">
<div class="av-dashboard-section-head"><div><span>مدیریت سفرها</span><h2>رزروهای پیش‌رو</h2></div><a href="<?php echo esc_url($dashboard_url.'?tab=bookings'); ?>">مشاهده همه</a></div>
<article class="av-booking-card"><div class="av-booking-card__media">HOTEL</div><div class="av-booking-card__body"><div class="av-booking-card__top"><span class="av-booking-status is-confirmed">تأیید شده</span><strong>AVN-260615-58241</strong></div><h3>هتل نمونه آوانیک استانبول</h3><p>اتاق دبل استاندارد · ۳ شب · ۲ بزرگسال</p><div class="av-booking-card__meta"><span>ورود: <strong>۱۴۰۵/۰۶/۱۵</strong></span><span>خروج: <strong>۱۴۰۵/۰۶/۱۸</strong></span><span>۲۷,۲۲۵,۰۰۰ تومان</span></div><div class="av-booking-card__actions"><a class="av-btn av-btn--primary" href="<?php echo esc_url($confirmation_url.'?booking=AVN-260615-58241'); ?>">مشاهده جزئیات</a><a class="av-btn av-btn--outline" href="<?php echo esc_url($confirmation_url.'?booking=AVN-260615-58241&receipt=1'); ?>">رسید</a></div></div></article>
<article class="av-booking-card"><div class="av-booking-card__media">FLIGHT</div><div class="av-booking-card__body"><div class="av-booking-card__top"><span class="av-booking-status is-confirmed">تأیید شده</span><strong>AVN-260620-74102</strong></div><h3>پرواز تهران → استانبول</h3><p>ATA Airlines · اکونومی · ۱ بزرگسال</p><div class="av-booking-card__meta"><span>پرواز: <strong>۱۴۰۵/۰۶/۲۰</strong></span><span>ساعت: <strong>09:30</strong></span><span>۱۲,۸۰۰,۰۰۰ تومان</span></div><div class="av-booking-card__actions"><a class="av-btn av-btn--primary" href="<?php echo esc_url($confirmation_url.'?booking=AVN-260620-74102'); ?>">مشاهده جزئیات</a><a class="av-btn av-btn--outline" href="<?php echo esc_url($confirmation_url.'?booking=AVN-260620-74102&receipt=1'); ?>">رسید</a></div></div></article>
</section></div></div></main>
<?php get_footer(); ?>
