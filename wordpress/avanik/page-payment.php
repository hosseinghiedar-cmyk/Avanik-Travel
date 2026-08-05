<?php
/** Template Name: Avanik Payment */
defined('ABSPATH') || exit;
get_header();
?>
<main class="av-payment-page" dir="rtl"><div class="av-container">
<header class="av-payment-header"><div><span class="av-hotel-eyebrow">پرداخت امن آوانیک</span><h1>پرداخت رزرو</h1><p>اطلاعات سفارش را بررسی کرده و روش پرداخت را انتخاب کنید.</p></div><div class="av-payment-security">🔒 پرداخت امن</div></header>
<div class="av-payment-layout"><section>
<div class="av-payment-card"><div class="av-payment-card__header"><h2>روش پرداخت</h2><span>انتخاب کنید</span></div>
<label class="av-payment-method is-selected"><input type="radio" name="payment" value="online" checked><span><strong>پرداخت آنلاین</strong><small>پرداخت از طریق درگاه بانکی</small></span><b>💳</b></label>
<label class="av-payment-method"><input type="radio" name="payment" value="wallet"><span><strong>کیف پول آوانیک</strong><small>استفاده از موجودی کیف پول</small></span><b>◉</b></label></div>
<div class="av-payment-card"><div class="av-payment-card__header"><h2>کد تخفیف</h2></div><div class="av-coupon"><input type="text" placeholder="کد تخفیف را وارد کنید"><button type="button" class="av-btn av-btn--outline">اعمال کد</button></div></div>
<div class="av-payment-card av-payment-notice"><strong>🔐 امنیت پرداخت</strong><p>اطلاعات پرداخت شما به صورت امن به درگاه بانکی منتقل می‌شود و اطلاعات کارت در آوانیک ذخیره نخواهد شد.</p></div>
</section>
<aside class="av-payment-summary"><h2>خلاصه سفارش</h2><div class="av-payment-hotel"><div class="av-payment-image">HOTEL</div><div><strong>هتل نمونه آوانیک استانبول</strong><span>اتاق دبل استاندارد</span></div></div>
<div class="av-payment-summary-row"><span>ورود</span><strong>۱۴۰۵/۰۶/۱۵</strong></div><div class="av-payment-summary-row"><span>خروج</span><strong>۱۴۰۵/۰۶/۱۸</strong></div><div class="av-payment-summary-row"><span>۳ شب</span><strong>۲۴,۷۵۰,۰۰۰ تومان</strong></div><div class="av-payment-summary-row"><span>مالیات و خدمات</span><strong>۲,۴۷۵,۰۰۰ تومان</strong></div><hr><div class="av-payment-total"><span>مبلغ قابل پرداخت</span><strong>۲۷,۲۲۵,۰۰۰ تومان</strong></div><label class="av-payment-terms"><input type="checkbox"> قوانین پرداخت و رزرو را می‌پذیرم.</label><button type="button" class="av-btn av-btn--primary av-pay-button">پرداخت ۲۷,۲۲۵,۰۰۰ تومان</button></aside>
</div></div></main>
<?php get_footer(); ?>
