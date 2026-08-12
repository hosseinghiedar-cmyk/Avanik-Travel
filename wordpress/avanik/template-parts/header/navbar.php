<nav class="av-navbar">
  <div class="av-container">
    <div class="av-navbar__wrapper">
      <div class="av-navbar__logo">
        <?php if (function_exists('the_custom_logo') && has_custom_logo()) : the_custom_logo(); else : ?>
          <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/avanik-logo.svg'); ?>" alt="آوانیک پرواز آسیا">
        <?php endif; ?>
      </div>

      <div class="av-navbar__menu">
        <?php
        wp_nav_menu([
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'av-menu',
          'fallback_cb'    => false,
        ]);
        ?>
      </div>

      <div class="av-navbar__actions">
        <a href="<?php echo esc_url(home_url('/flights')); ?>" class="av-btn av-btn--primary">رزرو آنلاین</a>
      </div>
    </div>
  </div>
</nav>
