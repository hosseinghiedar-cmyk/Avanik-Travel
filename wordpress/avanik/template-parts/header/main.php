<?php
defined('ABSPATH') || exit;
?>
<header class="av-header">
  <?php get_template_part('template-parts/header/topbar'); ?>
  <div class="av-header__main">
    <div class="av-container av-header__inner">
      <a class="av-header__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <?php if (function_exists('the_custom_logo') && has_custom_logo()) : the_custom_logo(); else : ?>
          <img class="av-bundled-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/avanik-logo.svg'); ?>" alt="آوانیک تراول">
        <?php endif; ?>
      </a>
      <nav class="av-navbar" aria-label="منوی اصلی">
        <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'av-navbar__menu','fallback_cb'=>false,'depth'=>2]); ?>
      </nav>
      <div class="av-header__actions">
        <a class="av-btn av-btn--outline" href="<?php echo esc_url(home_url('/login')); ?>">ورود / ثبت نام</a>
        <button class="av-btn av-btn--primary av-header__mobile-toggle" type="button" data-av-mobile-open aria-expanded="false" aria-controls="av-mobile-menu">منو</button>
      </div>
    </div>
  </div>
  <div class="av-navbar__mobile-menu" id="av-mobile-menu" data-av-mobile-menu aria-hidden="true">
    <div class="av-navbar__mobile-head">
      <strong>منوی آوانیک</strong>
      <button class="av-btn av-btn--outline" type="button" data-av-mobile-close>بستن</button>
    </div>
    <nav aria-label="منوی موبایل">
      <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'av-navbar__mobile-list','fallback_cb'=>false,'depth'=>2]); ?>
    </nav>
  </div>
</header>
