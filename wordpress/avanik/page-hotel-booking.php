<?php
/**
 * Template Name: Avanik Hotel Booking
 */
defined('ABSPATH') || exit;
get_header();
?>
<main class="av-hotel-booking" dir="rtl">
  <div class="av-container">
    <div class="av-booking-header">
      <div>
        <span class="av-hotel-eyebrow">تکمیل رزرو</span>
        <h1>اطلاعات رزرو هتل</h1>
        <p>اطلاعات مهمان را وارد کنید و رزرو خود را بررسی نمایید.</p>
      </div>
      <div class="av-booking-steps">
        <span class="is-active">۱ اطلاعات مهمان</span>
        <span>۲ بررسی رزرو</span>
        <span>۳ پرداخت</span>
      </div>
    </div>

    <div class="av-booking-layout">
      <section>
        <div class="av-booking-card">
          <div class="av-booking-card__header">
            <h2>اطلاعات مهمان</h2>
            <span>اطلاعات به صورت امن ثبت می‌شود</span>
          </div>

          <div class="av-booking-form">
            <div class="av-form-field">
              <label for="guest-first-name">نام</label>
              <input id="guest-first-name" type="text" placeholder="نام">
            </div>
            <div class="av-form-field">
              <label for="guest-last-name">نام خانوادگی</label>
              <input id="guest-last-name" type="text" placeholder="نام خانوادگی">
            </div>
            <div class="av-form-field">
              <label for="guest-mobile">شماره موبایل</label>
              <input id="guest-mobile" type="tel" placeholder="09xxxxxxxxx">
            </div>
            <div class="av-form-field">
              <label for="guest-email">ایمیل</label>
              <input id="guest-email" type="email" placeholder="example@email.com">
            </div>
            <div class="av-form-field av-form-field--full">
              <label for="guest-national-id">کد ملی / شماره پاسپورت</label>
              <input id="guest-national-id" type="text" placeholder="شماره مدرک شناسایی">
            </div>
          </div>
        </div>

        <div class="av-booking-card">
          <div class="av-booking-card__header">
            <h2>درخواست‌های ویژه</h2>
          </div>
          <textarea class="av-booking-textarea" placeholder="در صورت نیاز درخواست ویژه خود را وارد کنید..."></textarea>
        </div>

        <div class="av-booking-card">
          <label class="av-booking-checkbox">
            <input type="checkbox">
            <span>شرایط و قوانین رزرو و سیاست لغو هتل را مطالعه کرده و می‌پذیرم.</span>
          </label>
          <button class="av-btn av-btn--primary av-booking-submit" type="button">ادامه و بررسی رزرو</button>
        </div>
      </section>

      <aside class="av-booking-summary">
        <div class="av-booking-summary__hotel">
          <div class="av-booking-summary__image">HOTEL</div>
          <div>
            <strong>هتل نمونه آوانیک استانبول</strong>
            <span>★ 4.8 · تکسیم</span>
          </div>
        </div>

        <div class="av-booking-summary__room">
          <h3>اتاق دبل استاندارد</h3>
          <span>۲ بزرگسال · صبحانه</span>
        </div>

        <div class="av-booking-summary__dates">
          <div><span>ورود</span><strong>۱۴۰۵/۰۶/۱۵</strong></div>
          <div><span>خروج</span><strong>۱۴۰۵/۰۶/۱۸</strong></div>
          <div><span>مدت اقامت</span><strong>۳ شب</strong></div>
        </div>

        <div class="av-booking-summary__price">
          <div><span>قیمت اتاق</span><strong>۲۴,۷۵۰,۰۰۰ تومان</strong></div>
          <div><span>مالیات و خدمات</span><strong>۲,۴۷۵,۰۰۰ تومان</strong></div>
          <hr>
          <div class="is-total"><span>مبلغ نهایی</span><strong>۲۷,۲۲۵,۰۰۰ تومان</strong></div>
        </div>
      </aside>
    </div>
  </div>
</main>
<?php get_footer(); ?>
