<?php
/**
 * Template Name: Avanik Payment
 */
defined('ABSPATH') || exit;
get_header();

$order = [
    'reference' => 'AVK-260820-1024',
    'airline' => 'ماهان',
    'flight' => 'W5 1020',
    'route' => 'تهران → مشهد',
    'date' => '۱۴۰۵/۰۵/۲۰',
    'passenger' => '۱ بزرگسال',
    'base' => '5,500,000',
    'tax' => '370,000',
    'total' => '5,870,000',
];
?>

<main class="av-payment-page">
  <div class="av-container">

    <nav class="av-booking-progress" aria-label="مراحل رزرو">
      <div class="is-active"><span>۱</span> انتخاب پرواز</div>
      <div class="is-active"><span>۲</span> اطلاعات مسافر</div>
      <div class="is-active"><span>۳</span> پرداخت</div>
      <div><span>۴</span> صدور بلیط</div>
    </nav>

    <div class="av-payment-layout">

      <section class="av-payment-main">

        <div class="av-section-card">
          <header class="av-section-card__header">
            <div>
              <h1>پرداخت</h1>
              <p>اطلاعات سفارش را بررسی کرده و روش پرداخت را انتخاب کنید.</p>
            </div>
            <span class="av-secure-badge">پرداخت امن</span>
          </header>

          <div class="av-payment-method">
            <label class="av-payment-method__item is-selected">
              <input type="radio" name="payment_method" checked>
              <span class="av-payment-method__icon">₿</span>
              <span>
                <strong>پرداخت آنلاین</strong>
                <small>پرداخت از طریق درگاه بانکی</small>
              </span>
            </label>

            <label class="av-payment-method__item">
              <input type="radio" name="payment_method">
              <span class="av-payment-method__icon">◫</span>
              <span>
                <strong>اعتبار آوانیک</strong>
                <small>استفاده از موجودی حساب کاربری</small>
              </span>
            </label>
          </div>

          <div class="av-payment-notice">
            <strong>توجه</strong>
            <span>پس از تأیید، شما به صفحه امن پرداخت بانکی منتقل خواهید شد.</span>
          </div>

          <label class="av-check-field">
            <input type="checkbox" required>
            <span>قوانین خرید بلیط و شرایط استفاده از خدمات آوانیک را مطالعه کرده‌ام و می‌پذیرم.</span>
          </label>

          <button class="av-btn av-btn--primary av-payment-submit" type="button">
            پرداخت <?php echo esc_html($order['total']); ?> تومان
          </button>
        </div>

        <div class="av-section-card av-security-card">
          <div class="av-security-icon">✓</div>
          <div>
            <strong>پرداخت امن و محافظت‌شده</strong>
            <p>اطلاعات پرداخت شما در محیط امن درگاه بانکی وارد می‌شود.</p>
          </div>
        </div>

      </section>

      <aside class="av-payment-sidebar">

        <section class="av-section-card">
          <header class="av-section-card__header">
            <h2>خلاصه سفارش</h2>
          </header>

          <div class="av-order-flight">
            <div class="av-order-airline-mark">م</div>
            <div>
              <strong><?php echo esc_html($order['airline']); ?></strong>
              <small><?php echo esc_html($order['flight']); ?></small>
            </div>
          </div>

          <div class="av-order-route"><?php echo esc_html($order['route']); ?></div>

          <div class="av-order-meta">
            <div><span>تاریخ</span><strong><?php echo esc_html($order['date']); ?></strong></div>
            <div><span>مسافر</span><strong><?php echo esc_html($order['passenger']); ?></strong></div>
          </div>

          <hr>

          <div class="av-price-row">
            <span>قیمت بلیط</span>
            <strong><?php echo esc_html($order['base']); ?> تومان</strong>
          </div>

          <div class="av-price-row">
            <span>مالیات و عوارض</span>
            <strong><?php echo esc_html($order['tax']); ?> تومان</strong>
          </div>

          <div class="av-price-total">
            <span>مبلغ نهایی</span>
            <strong><?php echo esc_html($order['total']); ?> تومان</strong>
          </div>
        </section>

      </aside>
    </div>
  </div>
</main>

<?php get_footer(); ?>
