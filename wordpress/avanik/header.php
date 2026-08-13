<?php if (!defined('ABSPATH')) exit; ?><!doctype html>
<html <?php language_attributes(); ?> dir="rtl"><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('avanik-site'); ?>><?php wp_body_open(); ?>
<header class="avanik-header">
<div class="avanik-topbar"><div class="avanik-shell avanik-topbar-inner">
<div class="avanik-topbar-right">
<a class="avanik-login-link" href="<?php echo esc_url(wp_login_url()); ?>" aria-label="ورود به حساب کاربری"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21c.7-4.1 3.2-6.2 8-6.2s7.3 2.1 8 6.2"></path></svg></a>
<span class="avanik-login-text">ورود / ثبت‌نام</span>
</div>
<div class="avanik-topbar-left">
<?php if((int)avanik_option('show_social_header',1)): ?><div class="avanik-header-socials" aria-label="شبکه‌های اجتماعی">
<?php foreach([['instagram','اینستاگرام','instagram','<path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4Z"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/>'],['telegram','تلگرام','telegram','<path d="m21 3-4 18-6-6-4-2 14-10Z"/><path d="m11 15 2-6"/>'],['whatsapp','واتساپ','whatsapp','<path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4.1A8 8 0 1 1 20 11.5Z"/><path d="M9 8.5c.3 2.1 1.5 3.5 3.7 4.7l1.4-1.2c.3-.2.6-.2.9 0l1.2.7"/>'],['linkedin','لینکدین','linkedin','<path d="M6 9v9M6 6v.1M10 18v-9M10 13c0-2.5 1.5-4 3.6-4 2.3 0 3.4 1.6 3.4 4v5"/>'],['youtube','یوتیوب','youtube','<path d="M21 8.2a2.5 2.5 0 0 0-1.8-1.8C17.6 6 12 6 12 6s-5.6 0-7.2.4A2.5 2.5 0 0 0 3 8.2 26 26 0 0 0 2.6 12c0 1.3.1 2.6.4 3.8a2.5 2.5 0 0 0 1.8 1.8C6.4 18 12 18 12 18s5.6 0 7.2-.4a2.5 2.5 0 0 0 1.8-1.8c.3-1.2.4-2.5.4-3.8s-.1-2.6-.4-3.8Z"/><path d="m10 9 5 3-5 3V9Z"/>']] as $s): $url=avanik_option($s[0],'#'); if($url && $url!=='#'): ?><a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($s[1]); ?>" class="social-<?php echo esc_attr($s[2]); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><?php echo $s[3]; ?></svg></a><?php endif; endforeach; ?></div><?php endif; ?>
<a class="avanik-top-contact" href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/','',avanik_option('phone','021-12345678'))); ?>"><span class="avanik-icon">☎</span><?php echo esc_html(avanik_option('phone','021-12345678')); ?></a>
</div>
</div></div>
<div class="avanik-nav-wrap"><div class="avanik-shell avanik-nav">
<a class="avanik-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="آوانیک پرواز آسیا"><img src="<?php echo esc_url(avanik_option('logo',AVANIK_URI.'/assets/images/avanik-logo.svg')); ?>" alt="آوانیک پرواز آسیا"></a>
<nav class="avanik-main-menu" aria-label="منوی اصلی آوانیک"><?php if(has_nav_menu('primary')){wp_nav_menu(['theme_location'=>'primary','container'=>false,'fallback_cb'=>false,'menu_class'=>'avanik-menu-list']);}else{?><ul class="avanik-menu-list"><li class="current-menu-item"><a href="<?php echo esc_url(home_url('/')); ?>">صفحه اصلی</a></li><li><a href="<?php echo esc_url(home_url('/پروازها/')); ?>">پروازها</a></li><li><a href="<?php echo esc_url(home_url('/تورهای-خارجی/')); ?>">تورهای خارجی</a></li><li><a href="<?php echo esc_url(home_url('/هتل/')); ?>">هتل</a></li><li><a href="<?php echo esc_url(home_url('/ویزای-مسافرتی/')); ?>">ویزای مسافرتی</a></li><li><a href="<?php echo esc_url(home_url('/بلاگ/')); ?>">بلاگ</a></li><li><a href="<?php echo esc_url(home_url('/درباره-ما/')); ?>">درباره ما</a></li><li><a href="<?php echo esc_url(home_url('/تماس-با-ما/')); ?>">تماس با ما</a></li></ul><?php } ?></nav>
<button class="avanik-mobile-toggle" type="button" aria-label="باز کردن منو">☰</button></div></div></header>
