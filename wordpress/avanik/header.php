<?php if (!defined('ABSPATH')) exit; ?><!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class('avanik-site'); ?>>
<?php wp_body_open(); ?>
<header class="avanik-header avanik-header--hero" dir="rtl">
  <div class="avanik-shell avanik-nav">
    <a class="avanik-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="آوانیک پرواز آسیا">
      <img src="<?php echo esc_url(avanik_option('logo',AVANIK_URI.'/assets/images/avanik-logo.svg')); ?>" alt="آوانیک پرواز آسیا" decoding="async">
    </a>
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
      </ul>
      <?php } ?>
    </nav>
    <a class="avanik-header-user" href="<?php echo esc_url(wp_login_url()); ?>" aria-label="ورود به حساب کاربری"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"></circle><path d="M4.5 20c.8-3.4 3.2-5.3 7.5-5.3s6.7 1.9 7.5 5.3"></path></svg></a>
    <button class="avanik-mobile-toggle" type="button" aria-label="باز کردن منو" aria-expanded="false">☰</button>
  </div>
</header>
