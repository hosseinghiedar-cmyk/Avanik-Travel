<?php
/**
 * Template Name: Avanik Tour Details
 */
defined('ABSPATH') || exit;
get_header();
?>
<main class="av-tour-details">
  <div class="av-container">
    <div class="av-tour-gallery">
      <div class="av-tour-gallery__main"><div class="av-tour-image-placeholder">ISTANBUL</div></div>
      <div class="av-tour-gallery__side">
        <div class="av-tour-image-placeholder">BOSPHORUS</div>
        <div class="av-tour-image-placeholder">CITY</div>
      </div>
    </div>

    <div class="av-tour-layout">
      <article class="av-tour-content">
        <header class="av-tour-header">
          <div class="av-tour-meta-top">
            <span>استانبول، ترکیه</span><span>★ 4.8 (124 نظر)</span>
          </div>
          <h1>تور استانبول؛ تجربه‌ای متفاوت از شهر دو قاره</h1>
          <div class="av-tour-facts">
            <span>◷ ۵ روز و ۴ شب</span>
            <span>✓ ترانسفر فرودگاهی</span>
            <span>✓ صبحانه</span>
          </div>
        </header>

        <section class="av-tour-section">
          <h2>درباره این تور</h2>
          <p>یک سفر کامل برای تجربه استانبول، از جاذبه‌های تاریخی تا گشت بسفر.</p>
        </section>

        <section class="av-tour-section">
          <h2>برنامه سفر</h2>
          <div class="av-itinerary">
            <div class="av-itinerary__item"><div class="av-itinerary__day">روز اول</div><div><h3>ورود به استانبول</h3><p>ترانسفر فرودگاهی و تحویل اتاق هتل.</p></div></div>
            <div class="av-itinerary__item"><div class="av-itinerary__day">روز دوم</div><div><h3>تور شهری استانبول</h3><p>بازدید از جاذبه‌های اصلی و محله‌های تاریخی.</p></div></div>
            <div class="av-itinerary__item"><div class="av-itinerary__day">روز سوم</div><div><h3>گشت آزاد</h3><p>زمان آزاد برای خرید و تجربه شهر.</p></div></div>
            <div class="av-itinerary__item"><div class="av-itinerary__day">روز چهارم</div><div><h3>تور بسفر</h3><p>گشت دریایی و بازدید از مناطق ساحلی.</p></div></div>
            <div class="av-itinerary__item"><div class="av-itinerary__day">روز پنجم</div><div><h3>بازگشت</h3><p>تحویل اتاق و ترانسفر به فرودگاه.</p></div></div>
          </div>
        </section>

        <section class="av-tour-section">
          <div class="av-tour-inclusions">
            <div><h2>خدمات شامل</h2><ul><li>هتل و اقامت</li><li>صبحانه</li><li>ترانسفر فرودگاهی</li><li>گشت‌های برنامه</li></ul></div>
            <div><h2>خدمات غیرشامل</h2><ul><li>هزینه‌های شخصی</li><li>بیمه‌های اختیاری</li><li>گشت‌های خارج از برنامه</li></ul></div>
          </div>
        </section>
      </article>

      <aside class="av-tour-booking-card">
        <div class="av-tour-booking-card__header">
          <span>شروع قیمت از</span><strong>18,900,000 تومان</strong><small>برای هر نفر</small>
        </div>
        <div class="av-tour-booking-field"><label for="tour-date">تاریخ سفر</label>
          <select id="tour-date"><option>۱۴۰۵/۰۶/۱۵</option><option>۱۴۰۵/۰۶/۲۲</option><option>۱۴۰۵/۰۶/۲۹</option></select>
        </div>
        <div class="av-tour-booking-field"><label for="tour-travelers">تعداد مسافر</label>
          <select id="tour-travelers"><option>۱ بزرگسال</option><option>۲ بزرگسال</option><option>۲ بزرگسال + ۱ کودک</option></select>
        </div>
        <div class="av-tour-booking-total"><span>مبلغ تقریبی</span><strong>18,900,000 تومان</strong></div>
        <button class="av-btn av-btn--primary av-tour-booking-cta" type="button">رزرو تور</button>
        <p class="av-tour-booking-note">امکان تکمیل اطلاعات در مراحل بعدی رزرو.</p>
      </aside>
    </div>
  </div>
</main>
<?php get_footer(); ?>
