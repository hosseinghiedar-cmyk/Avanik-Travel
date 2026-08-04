<?php
/**
 * Template Name: Avanik Hotel Booking Review
 */
defined('ABSPATH') || exit;
get_header();
?>
<main class="av-hotel-booking" dir="rtl">
  <div class="av-container">
    <div class="av-booking-header">
      <div>
        <span class="av-hotel-eyebrow">بررسی نهایی</span>
        <h1>بررسی اطلاعات رزرو</h1>
        <p>قبل از پرداخت، اطلاعات رزرو را کنترل کنید.</p>
      </div>
      <div class="av-booking-steps">
        <span class="is-complete">۱ اطلاعات مهمان</span>
        <span class="is-active">۲ بررسی رزرو</span>
        <span>۳ پرداخت</span>
      </div>
    </div>

    <div class="av-booking-review">
      <section class="av-booking-card">
        <div class="av-booking-card__header">
          <h2>اطلاعات مهمان</h2>
          <button class="av-booking-edit" type="button">ویرایش</button>
        </div>
        <div class="av-review-grid">
          <div><span>نام مهمان</span><strong>علی رضایی</strong></div>
          <div><span>موبایل</span><strong>09xxxxxxxxx</strong></div>
          <div><span>ایمیل</span><strong>example@email.com</strong></div>
          <div><span>مدرک شناسایی</span><strong>**********</strong></div>
        </div>
      </section>

      <section class="av-booking-card">
        <div class="av-booking-card__header"><h2>جزئیات اقامت</h2></div>
        <div class="av-review-hotel">
          <div class="av-booking-summary__image">HOTEL</div>
          <div><h3>هتل نمونه آوانیک استانبول</h3><p>اتاق دبل استاندارد · ۲ بزرگسال · صبحانه</p></div>
        </div>
        <div class="av-review-dates">
          <div><span>ورود</span><strong>۱۴۰۵/۰۶/۱۵</strong></div>
          <div><span>خروج</span><strong>۱۴۰۵/۰۶/۱۸</strong></div>
          <div><span>اقامت</span><strong>۳ شب</strong></div>
        </div>
      </section>

      <section class="av-booking-card av-review-total">
        <span>مبلغ قابل پرداخت</span>
        <strong>۲۷,۲۲۵,۰۰۰ تومان</strong>
        <button class="av-btn av-btn--primary" type="button">ادامه به پرداخت</button>
      </section>
    </div>
  </div>
</main>
<?php get_footer(); ?>
