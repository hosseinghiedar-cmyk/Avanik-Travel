<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class ThemeSetup {
  private const VERSION = '0.4.0-demo2';
  private const OPTION = 'avanik_demo_setup_version';

  public static function register(): void {
    add_action('after_switch_theme', [self::class, 'install_demo']);
  }

  public static function install_demo(): void {
    $pages = [
      ['home', 'صفحه اصلی', ''],
      ['flights', 'پروازها', '', 'page-flight-search.php'],
      ['flight-details', 'جزئیات پرواز', '', 'page-flight-details.php'],
      ['hotels', 'هتل‌ها', '', 'page-hotel-search.php'],
      ['hotel-details', 'جزئیات هتل', '', 'page-hotel-details.php'],
      ['hotel-booking', 'رزرو هتل', '', 'page-hotel-booking.php'],
      ['hotel-booking-review', 'بررسی رزرو هتل', '', 'page-hotel-booking-review.php'],
      ['tours', 'تورهای مسافرتی', '<p>در آوانیک می‌توانید تورهای داخلی و خارجی را جستجو و رزرو کنید.</p><div class="av-page-cards"><article><h2>تورهای داخلی</h2><p>مشهد، کیش، شیراز، اصفهان و سایر مقاصد.</p></article><article><h2>تورهای خارجی</h2><p>استانبول، دبی، آنتالیا، تفلیس و مقاصد منتخب.</p></article></div>'],
      ['tour-details', 'جزئیات تور', '', 'page-tour-details.php'],
      ['booking', 'تکمیل رزرو', '', 'page-booking.php'],
      ['booking-confirmation', 'تأیید رزرو', '', 'page-booking-confirmation.php'],
      ['payment', 'پرداخت', '', 'page-payment.php'],
      ['dashboard', 'داشبورد من', '', 'page-dashboard.php'],
      ['login', 'ورود', '', 'page-login.php'],
      ['register', 'ثبت نام', '', 'page-register.php'],
      ['visa', 'خدمات ویزا', '<p>خدمات مشاوره و پیگیری ویزا برای مقاصد منتخب آوانیک.</p>'],
      ['about', 'درباره آوانیک', '<p>آوانیک یک پلتفرم خدمات سفر برای رزرو پرواز، هتل و تور است.</p>'],
      ['contact', 'تماس با ما', '<p>برای پشتیبانی رزرو و خدمات سفر با تیم آوانیک در ارتباط باشید.</p>'],
    ];

    foreach ($pages as $item) {
      [$slug, $title, $content] = array_pad($item, 4, '');
      $template = $item[3] ?? '';
      $existing = get_page_by_path($slug, OBJECT, 'page');
      if ($existing instanceof \WP_Post) {
        $page_id = (int) $existing->ID;
      } else {
        $page_id = wp_insert_post([
          'post_type' => 'page',
          'post_status' => 'publish',
          'post_title' => $title,
          'post_name' => $slug,
          'post_content' => $content,
        ], true);
        if (is_wp_error($page_id)) continue;
      }
      if ($template && file_exists(get_template_directory() . '/' . $template)) {
        update_post_meta($page_id, '_wp_page_template', $template);
      } elseif (!$template) {
        update_post_meta($page_id, '_wp_page_template', 'default');
      }
    }

    self::ensure_front_page();
    self::ensure_menu();
    update_option(self::OPTION, self::VERSION, false);
    flush_rewrite_rules();
  }

  private static function ensure_front_page(): void {
    $home = get_page_by_path('home', OBJECT, 'page');
    if ($home instanceof \WP_Post) {
      update_option('show_on_front', 'page');
      update_option('page_on_front', (int) $home->ID);
    }
  }

  private static function ensure_menu(): void {
    $menu_name = 'آوانیک - منوی اصلی';
    $menu = wp_get_nav_menu_object($menu_name);
    $menu_id = $menu ? (int) $menu->term_id : wp_create_nav_menu($menu_name);
    if (!$menu_id || is_wp_error($menu_id)) return;

    $items = [
      ['صفحه اصلی', 'home'],
      ['پروازها', 'flights'],
      ['هتل‌ها', 'hotels'],
      ['تورهای مسافرتی', 'tours'],
      ['خدمات ویزا', 'visa'],
      ['درباره ما', 'about'],
      ['تماس با ما', 'contact'],
      ['داشبورد', 'dashboard'],
      ['ورود', 'login'],
    ];

    $existing_items = wp_get_nav_menu_items($menu_id) ?: [];
    $existing_object_ids = [];
    foreach ($existing_items as $existing) {
      $existing_object_ids[(int) $existing->object_id] = true;
    }

    foreach ($items as [$label, $slug]) {
      $page = get_page_by_path($slug, OBJECT, 'page');
      if (!$page instanceof \WP_Post || isset($existing_object_ids[(int) $page->ID])) continue;
      wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title' => $label,
        'menu-item-object' => 'page',
        'menu-item-object-id' => (int) $page->ID,
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish',
      ]);
    }

    $locations = get_theme_mod('nav_menu_locations', []);
    $locations['primary'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
  }
}
