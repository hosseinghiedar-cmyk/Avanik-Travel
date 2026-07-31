<nav class="av-navbar">
  <div class="av-container">
    <div class="av-navbar__wrapper">
      <div class="av-navbar__logo">
        <?php the_custom_logo(); ?>
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
        <a href="#" class="av-btn av-btn--primary">رزرو آنلاین</a>
      </div>
    </div>
  </div>
</nav>
