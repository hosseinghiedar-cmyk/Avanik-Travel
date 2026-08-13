<?php if (!defined('ABSPATH')) exit; ?><!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('avanik-site'); ?>>
<?php wp_body_open(); ?>
<header class="avanik-header">
  <div class="avanik-topbar"><div class="avanik-shell avanik-topbar-inner">
    <div class="avanik-top-contact"><a href="tel:<?php echo esc_attr(avanik_option('phone','021-12345678')); ?>"><span class="avanik-icon">☎</span><?php echo esc_html(avanik_option('phone','021-12345678')); ?></a></div>
    <div class="avanik-header-socials">
      <?php foreach([['instagram','اینستاگرام','◎'],['telegram','تلگرام','➤'],['whatsapp','واتساپ','◔'],['linkedin','لینکدین','in'],['youtube','یوتیوب','▶']] as $s): $url=avanik_option($s[0],'#'); if($url): ?><a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr($s[1]); ?>"><?php echo esc_html($s[2]); ?></a><?php endif; endforeach; ?>
    </div>
    <a class="avanik-login-link" href="<?php echo esc_url(wp_login_url()); ?>" aria-label="ورود / ثبت نام"><span class="avanik-user-icon">♙</span></a>
  </div></div>
  <div class="avanik-nav-wrap"><div class="avanik-shell avanik-nav">
    <a class="avanik-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="آوانیک پرواز آسیا"><img src="<?php echo esc_url(avanik_option('logo',AVANIK_URI.'/assets/images/avanik-logo.svg')); ?>" alt="آوانیک پرواز آسیا"></a>
    <nav class="avanik-main-menu" aria-label="منوی اصلی">
      <?php if (has_nav_menu('primary')) { wp_nav_menu(['theme_location'=>'primary','container'=>false,'fallback_cb'=>false,'menu_class'=>'avanik-menu-list']); } else { ?>
      <ul class="avanik-menu-list">
        <li class="current-menu-item"><a href="<?php echo esc_url(home_url('/')); ?>">صفحه اصلی</a></li>
        <li><a href="<?php echo esc_url(home_url('/پروازها/')); ?>">پروازها</a></li>
        <li><a href="<?php echo esc_url(home_url('/تورهای-خارجی/')); ?>">تورهای خارجی</a></li>
        <li><a href="<?php echo esc_url(home_url('/هتل/')); ?>">هتل</a></li>
        <li><a href="<?php echo esc_url(home_url('/ویزای-مسافرتی/')); ?>">ویزای مسافرتی</a></li>
        <li><a href="<?php echo esc_url(home_url('/بلاگ/')); ?>">بلاگ</a></li>
        <li><a href="<?php echo esc_url(home_url('/درباره-ما/')); ?>">درباره ما</a></li>
        <li><a href="<?php echo esc_url(home_url('/تماس-با-ما/')); ?>">تماس با ما</a></li>
      </ul><?php } ?>
    </nav>
    <button class="avanik-mobile-toggle" type="button" aria-label="باز کردن منو">☰</button>
  </div></div>
</header>
