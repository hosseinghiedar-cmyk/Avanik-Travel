<?php if (!defined('ABSPATH')) exit; ?>
<footer class="avanik-footer">
  <div class="avanik-footer-main"><div class="avanik-shell avanik-footer-grid">
    <div class="avanik-footer-brand"><img src="<?php echo esc_url(AVANIK_URI.'/assets/images/avanik-logo-white.svg'); ?>" alt="آوانیک پرواز آسیا"><p>آوانیک پرواز آسیا، ارائه‌دهنده خدمات مسافرتی و گردشگری با تجربه‌ای متفاوت، سریع و باکیفیت.</p><div class="avanik-socials">
      <a href="<?php echo esc_url(avanik_option('instagram','#')); ?>" aria-label="Instagram">◎</a><a href="<?php echo esc_url(avanik_option('telegram','#')); ?>" aria-label="Telegram">➤</a><a href="<?php echo esc_url(avanik_option('whatsapp','#')); ?>" aria-label="WhatsApp">◉</a><a href="<?php echo esc_url(avanik_option('linkedin','#')); ?>" aria-label="LinkedIn">in</a>
    </div></div>
    <div><h3>خدمات</h3><a href="<?php echo esc_url(home_url('/پروازها/')); ?>">پروازهای داخلی</a><a href="<?php echo esc_url(home_url('/پروازها/')); ?>">پروازهای خارجی</a><a href="<?php echo esc_url(home_url('/تورهای-داخلی/')); ?>">تورهای داخلی</a><a href="<?php echo esc_url(home_url('/تورهای-خارجی/')); ?>">تورهای خارجی</a><a href="<?php echo esc_url(home_url('/هتل/')); ?>">هتل</a></div>
    <div><h3>لینک‌های سریع</h3><a href="<?php echo esc_url(home_url('/')); ?>">صفحه اصلی</a><a href="<?php echo esc_url(home_url('/درباره-ما/')); ?>">درباره ما</a><a href="<?php echo esc_url(home_url('/تماس-با-ما/')); ?>">تماس با ما</a><a href="<?php echo esc_url(home_url('/سوالات-متداول/')); ?>">سوالات متداول</a><a href="<?php echo esc_url(home_url('/قوانین/')); ?>">شرایط و قوانین</a></div>
    <div class="avanik-newsletter"><h3>خبرنامه</h3><p>با عضویت در خبرنامه، از آخرین اخبار و تخفیف‌ها مطلع شوید.</p><form><input type="email" placeholder="ایمیل خود را وارد کنید"><button type="submit">عضویت</button></form></div>
  </div></div>
  <div class="avanik-footer-bottom"><div class="avanik-shell"><span>طراحی و توسعه: تیم آوانیک</span><span>کلیه حقوق این سایت محفوظ می‌باشد.</span></div></div>
</footer>
<?php wp_footer(); ?></body></html>
