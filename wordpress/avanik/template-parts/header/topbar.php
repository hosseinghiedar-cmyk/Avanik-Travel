<?php
defined('ABSPATH') || exit;
$phone = class_exists('\\Avanik\\ThemeSettings') ? \Avanik\ThemeSettings::get('phone') : '021-12345678';
$support = class_exists('\\Avanik\\ThemeSettings') ? \Avanik\ThemeSettings::get('support') : 'پشتیبانی ۲۴ ساعته';
?>
<div class="av-topbar">
  <div class="av-container av-topbar__inner">
    <div class="av-topbar__right">
      <a class="av-topbar__link" href="<?php echo esc_url(home_url('/contact')); ?>">تماس با ما</a>
      <span class="av-topbar__link">☎ <?php echo esc_html($phone); ?></span>
    </div>
    <div class="av-topbar__left">
      <a class="av-topbar__link" href="<?php echo esc_url(home_url('/login')); ?>">♙ ورود / ثبت نام</a>
      <span class="av-topbar__link">◷ <?php echo esc_html($support); ?></span>
    </div>
  </div>
</div>
