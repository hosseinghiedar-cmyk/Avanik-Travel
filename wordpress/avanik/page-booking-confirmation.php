<?php
/**
 * Template Name: Avanik Booking Confirmation
 */
defined('ABSPATH') || exit;
get_header();

$order = [
    'reference' => 'AVK-260820-1024',
    'airline' => 'ماهان',
    'flight' => 'W5 1020',
    'route' => 'تهران → مشهد',
    'date' => '۱۴۰۵/۰۵/۲۰',
    'time' => '08:30 - 09:55',
    'passenger' => '۱ بزرگسال',
    'total' => '5,870,000',
];
?>

<main class="av-confirmation-page">
  <div class="av-container">

    <div class="av-confirmation-card">

      <div class="av-confirmation-success">
        <div class="av-confirmation-check">✓</div>
        <h1>رزرو شما با موفقیت انجام شد</h1>
        <p>اطلاعات رزرو و بلیط به ایمیل شما ارسال خواهد شد.</p>
      </div>

      <div class="av-booking-reference">
        <span>کد پیگیری رزرو</span>
        <strong><?php echo esc_html($order['reference']); ?></strong>
      </div>

      <div class="av-confirmation-grid">

        <section class="av-confirmation-section">
          <h2>اطلاعات پرواز</h2>

          <div class="av-confirmation-flight">
            <div class="av-order-airline-mark">م</div>
            <div>
              <strong><?php echo esc_html($order['airline']); ?></strong>
              <small><?php echo esc_html($order['flight']); ?></small>
            </div>
          </div>

          <div class="av-confirmation-route">
            <div>
              <span><?php echo esc_html($order['route']); ?></span>
              <strong><?php echo esc_html($order['time']); ?></strong>
            </div>
          </div>

          <div class="av-confirmation-meta">
            <div><span>تاریخ</span><strong><?php echo esc_html($order['date']); ?></strong></div>
            <div><span>مسافر</span><strong><?php echo esc_html($order['passenger']); ?></strong></div>
          </div>
        </section>

        <section class="av-confirmation-section">
          <h2>وضعیت پرداخت</h2>

          <div class="av-status-success">
            <span>✓</span>
            <div>
              <strong>پرداخت موفق</strong>
              <small><?php echo esc_html($order['total']); ?> تومان</small>
            </div>
          </div>

          <p class="av-confirmation-help">
            برای مشاهده رزروهای خود می‌توانید وارد داشبورد کاربری شوید.
          </p>
        </section>

      </div>

      <div class="av-confirmation-actions">
        <button class="av-btn av-btn--outline" type="button">چاپ رزرو</button>
        <button class="av-btn av-btn--secondary" type="button">دانلود اطلاعات</button>
        <a class="av-btn av-btn--primary" href="<?php echo esc_url(home_url('/')); ?>">بازگشت به صفحه اصلی</a>
      </div>

    </div>
  </div>
</main>

<?php get_footer(); ?>
