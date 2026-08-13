<?php
defined('ABSPATH') || exit;
$settings=class_exists('\\Avanik\\ThemeSettings') ? \Avanik\ThemeSettings::get() : [];
$logo=!empty($settings['logo_url'])?$settings['logo_url']:get_template_directory_uri().'/assets/images/avanik-logo.svg';
$socials=[
 'instagram'=>['label'=>'اینستاگرام','icon'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.4" cy="6.6" r="1"/></svg>'],
 'telegram'=>['label'=>'تلگرام','icon'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 4 3.8 10.7c-.8.3-.8 1.4 0 1.7l4.4 1.5 1.7 5.3c.2.7 1.1.9 1.6.4l2.5-2.5 4.8 3.5c.6.4 1.4.1 1.6-.6L23 5.4c.2-.9-.8-1.7-2-1.4Z"/><path d="m8.5 13.9 3.2-2.2 3.8-3"/></svg>'],
 'whatsapp'=>['label'=>'واتساپ','icon'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A10 10 0 0 0 4.7 19.2L3 22l2.9-1.7A10 10 0 1 0 20.5 3.5Z"/><path d="M8.3 7.5c.3-.4.7-.4 1-.1l1.1 1.3c.2.3.2.6 0 .9l-.5.7c.7 1.3 1.7 2.3 3 3l.7-.5c.3-.2.6-.2.9 0l1.3 1.1c.3.3.3.7-.1 1-1 1-2.2 1.1-3.4.5-2.6-1.3-4.5-3.2-5.8-5.8-.6-1.2-.5-2.4.5-3.4Z"/></svg>'],
 'linkedin'=>['label'=>'لینکدین','icon'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3.5A2.5 2.5 0 1 1 5 8.5 2.5 2.5 0 0 1 5 3.5ZM3.5 9.5h3V21h-3zM9 9.5h2.9v1.6h.1c.4-.8 1.5-1.9 3.4-1.9 3.6 0 4.2 2.4 4.2 5.5V21h-3v-5.6c0-1.3 0-3-1.9-3s-2.2 1.5-2.2 2.9V21H9z"/></svg>'],
 'x'=>['label'=>'ایکس','icon'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h4.2l4.1 5.6L17 4h2.9l-6.3 7.3L20 20h-4.2l-4.5-6.1L6.9 20H4l6.2-7.5z"/></svg>']
];
?>
<header class="av-header">
  <?php get_template_part('template-parts/header/topbar'); ?>
  <div class="av-header__main"><div class="av-container av-header__inner">
    <a class="av-header__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="آوانیک پرواز آسیا"><img class="av-bundled-logo" src="<?php echo esc_url($logo); ?>" alt="آوانیک پرواز آسیا"></a>
    <nav class="av-navbar" aria-label="منوی اصلی"><?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'av-navbar__menu','fallback_cb'=>false,'depth'=>2]); ?></nav>
    <div class="av-header__actions">
      <?php if(!empty($settings['header_socials'])): ?><div class="av-header__socials" aria-label="شبکه‌های اجتماعی"><?php foreach($socials as $key=>$social): if(empty($settings[$key])) continue; ?><a href="<?php echo esc_url($settings[$key]); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($social['label']); ?>" title="<?php echo esc_attr($social['label']); ?>"><?php echo $social['icon']; ?></a><?php endforeach; ?></div><?php endif; ?>
      <a class="av-login-icon" href="<?php echo esc_url(wp_login_url()); ?>" aria-label="ورود به حساب کاربری" title="ورود به حساب کاربری"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c.8-4.1 3.5-6 8-6s7.2 1.9 8 6"/></svg></a>
      <button class="av-btn av-btn--primary av-header__mobile-toggle" type="button" data-av-mobile-open aria-expanded="false" aria-controls="av-mobile-menu">منو</button>
    </div>
  </div></div>
  <div class="av-navbar__mobile-menu" id="av-mobile-menu" data-av-mobile-menu aria-hidden="true"><div class="av-navbar__mobile-head"><strong>منوی آوانیک</strong><button class="av-btn av-btn--outline" type="button" data-av-mobile-close>بستن</button></div><nav aria-label="منوی موبایل"><?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'av-navbar__mobile-list','fallback_cb'=>false,'depth'=>2]); ?></nav></div>
</header>