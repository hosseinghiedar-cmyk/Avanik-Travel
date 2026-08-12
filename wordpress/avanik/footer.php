<?php
defined('ABSPATH') || exit;
$theme_uri = get_template_directory_uri();
$phone = class_exists('\\Avanik\\ThemeSettings') ? \Avanik\ThemeSettings::get('phone') : '021-12345678';
$contact_url = home_url('/contact');
?>
</main>
<footer class="av-footer" dir="rtl">
  <div class="av-container av-footer__main">
    <div class="av-footer__grid">
      <div class="av-footer__brand"><img src="<?php echo esc_url($theme_uri.'/assets/images/avanik-logo.svg'); ?>" alt="آوانیک پرواز آسیا"><p>آوانیک پرواز آسیا، ارائه‌دهنده خدمات مسافرتی و گردشگری با تمرکز بر کیفیت، سرعت و تجربه بهتر سفر.</p><div class="av-footer__social"><a href="<?php echo esc_url($contact_url); ?>" aria-label="اینستاگرام">◎</a><a href="<?php echo esc_url($contact_url); ?>" aria-label="تلگرام">➤</a><a href="<?php echo esc_url($contact_url); ?>" aria-label="واتساپ">◔</a><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/','',$phone)); ?>" aria-label="تماس">☎</a></div></div>
      <div><h3>خدمات</h3><p><a href="<?php echo esc_url(home_url('/flights')); ?>">پروازهای داخلی</a></p><p><a href="<?php echo esc_url(home_url('/flights')); ?>">پروازهای خارجی</a></p><p><a href="<?php echo esc_url(home_url('/hotels')); ?>">هتل</a></p><p><a href="<?php echo esc_url(home_url('/tours')); ?>">تورهای داخلی</a></p><p><a href="<?php echo esc_url(home_url('/tours')); ?>">تورهای خارجی</a></p></div>
      <div><h3>لینک‌های سریع</h3><p><a href="<?php echo esc_url(home_url('/')); ?>">صفحه اصلی</a></p><p><a href="<?php echo esc_url(home_url('/about')); ?>">درباره ما</a></p><p><a href="<?php echo esc_url($contact_url); ?>">تماس با ما</a></p><p><a href="<?php echo esc_url(home_url('/dashboard')); ?>">پنل کاربری</a></p><p><a href="<?php echo esc_url($contact_url); ?>">شرایط و قوانین</a></p></div>
      <div><h3>خبرنامه</h3><p>با عضویت در خبرنامه، آخرین اخبار و تخفیف‌ها را دریافت کنید.</p><form class="av-newsletter" onsubmit="return false"><input type="email" placeholder="ایمیل خود را وارد کنید"><button class="av-btn av-btn--primary" type="submit">عضویت</button></form></div>
    </div>
  </div>
  <div class="av-footer__bottom"><div class="av-container">طراحی و توسعه: تیم آوانیک · کلیه حقوق این سایت محفوظ می‌باشد.</div></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
