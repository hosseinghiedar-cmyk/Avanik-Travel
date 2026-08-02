<?php
/**
 * Template Name: Avanik Flight Details
 */
defined('ABSPATH') || exit;
get_header();
$flight = [
'airline'=>'ماهان','code'=>'W5 1020','origin'=>'تهران','destination'=>'مشهد','depart'=>'08:30','arrive'=>'09:55','duration'=>'1 ساعت و 25 دقیقه','date'=>'۱۴۰۵/۰۵/۲۰','cabin'=>'اکونومی','baggage'=>'۲۰ کیلوگرم','price'=>'5,870,000'];
?>
<main class="av-booking-page"><div class="av-container">
<nav class="av-booking-progress" aria-label="مراحل رزرو">
<div class="is-active"><span>۱</span> انتخاب پرواز</div><div class="is-active"><span>۲</span> اطلاعات مسافر</div><div><span>۳</span> پرداخت</div><div><span>۴</span> صدور بلیط</div>
</nav>
<div class="av-booking-layout">
<section class="av-passenger-section"><div class="av-section-card">
<header class="av-section-card__header"><div><h1>اطلاعات مسافر</h1><p>لطفاً اطلاعات را دقیقاً مطابق مدرک شناسایی وارد کنید.</p></div><span class="av-step-badge">۱ بزرگسال</span></header>
<form class="av-passenger-form" action="<?php echo esc_url(home_url('/payment')); ?>" method="post">
<section class="av-form-section"><h2>مسافر ۱ — بزرگسال</h2><div class="av-form-grid">
<label class="av-form-field"><span>نام فارسی</span><input type="text" name="first_name_fa" required></label>
<label class="av-form-field"><span>نام خانوادگی فارسی</span><input type="text" name="last_name_fa" required></label>
<label class="av-form-field"><span>نام انگلیسی</span><input type="text" name="first_name_en" required></label>
<label class="av-form-field"><span>نام خانوادگی انگلیسی</span><input type="text" name="last_name_en" required></label>
<label class="av-form-field"><span>کد ملی</span><input type="text" name="national_id" inputmode="numeric" maxlength="10"></label>
<label class="av-form-field"><span>تاریخ تولد</span><input type="text" name="birth_date" placeholder="۱۴۰۰/۰۱/۰۱"></label>
</div></section>
<section class="av-form-section"><h2>اطلاعات تماس</h2><div class="av-form-grid">
<label class="av-form-field"><span>شماره موبایل</span><input type="tel" name="mobile" required placeholder="۰۹۱۲۱۲۳۴۵۶۷"></label>
<label class="av-form-field"><span>ایمیل</span><input type="email" name="email" required></label>
</div></section>
<label class="av-check-field"><input type="checkbox" name="terms" required><span>اطلاعات واردشده را بررسی کرده‌ام و قوانین خرید بلیط را می‌پذیرم.</span></label>
<button class="av-btn av-btn--primary av-continue-btn" type="submit">ادامه و پرداخت</button>
</form></div></section>
<aside class="av-booking-sidebar">
<section class="av-section-card av-flight-summary"><header class="av-section-card__header"><h2>خلاصه پرواز</h2></header>
<div class="av-summary-airline"><div class="av-airline-mark">م</div><div><strong><?php echo esc_html($flight['airline']); ?></strong><small><?php echo esc_html($flight['code']); ?></small></div></div>
<div class="av-summary-route"><div><strong><?php echo esc_html($flight['depart']); ?></strong><span><?php echo esc_html($flight['origin']); ?></span></div><div class="av-summary-line">✈</div><div><strong><?php echo esc_html($flight['arrive']); ?></strong><span><?php echo esc_html($flight['destination']); ?></span></div></div>
<div class="av-summary-details"><div><span>تاریخ</span><strong><?php echo esc_html($flight['date']); ?></strong></div><div><span>مدت پرواز</span><strong><?php echo esc_html($flight['duration']); ?></strong></div><div><span>کلاس</span><strong><?php echo esc_html($flight['cabin']); ?></strong></div><div><span>بار مجاز</span><strong><?php echo esc_html($flight['baggage']); ?></strong></div></div></section>
<section class="av-section-card av-price-summary"><div><span>قیمت بلیط</span><strong><?php echo esc_html($flight['price']); ?> تومان</strong></div><div><span>مالیات و عوارض</span><strong>محاسبه در مرحله پرداخت</strong></div><hr><div class="av-total"><span>مبلغ قابل پرداخت</span><strong><?php echo esc_html($flight['price']); ?> تومان</strong></div></section>
</aside></div></div></main>
<?php get_footer(); ?>
