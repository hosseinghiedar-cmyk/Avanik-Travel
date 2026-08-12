<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class ThemeSettings {
  private const OPTION = 'avanik_theme_options';

  public static function register(): void {
    add_action('admin_menu', [self::class, 'admin_menu']);
    add_action('admin_init', [self::class, 'register_settings']);
    add_action('wp_head', [self::class, 'frontend_css'], 30);
    add_filter('body_class', [self::class, 'body_class']);
  }

  public static function defaults(): array {
    return [
      'primary' => '#072B5A',
      'gold' => '#F2B134',
      'white' => '#FFFFFF',
      'hero_title' => 'پرواز به استانبول',
      'hero_subtitle' => 'با بهترین قیمت و خدمات ویژه',
      'phone' => '021-12345678',
      'support' => 'پشتیبانی ۲۴ ساعته',
      'sticky_header' => 1,
      'animations' => 1,
      'show_tours' => 1,
      'show_why' => 1,
    ];
  }

  public static function get(string $key = '') {
    $options = wp_parse_args((array) get_option(self::OPTION, []), self::defaults());
    return $key === '' ? $options : ($options[$key] ?? null);
  }

  public static function admin_menu(): void {
    add_menu_page(
      'آوانیک',
      'آوانیک',
      'manage_options',
      'avanik-theme-settings',
      [self::class, 'render'],
      'dashicons-airplane',
      3
    );
    add_submenu_page('avanik-theme-settings', 'تنظیمات قالب', 'تنظیمات قالب', 'manage_options', 'avanik-theme-settings', [self::class, 'render']);
    add_submenu_page('avanik-theme-settings', 'راهنمای قالب', 'راهنمای قالب', 'manage_options', 'avanik-theme-guide', [self::class, 'guide']);
  }

  public static function register_settings(): void {
    register_setting('avanik_theme_options_group', self::OPTION, [
      'type' => 'array',
      'sanitize_callback' => [self::class, 'sanitize'],
      'default' => self::defaults(),
    ]);
  }

  public static function sanitize($input): array {
    $d = self::defaults();
    $input = is_array($input) ? $input : [];
    $out = $d;
    foreach (['primary', 'gold', 'white'] as $key) {
      $color = isset($input[$key]) ? sanitize_hex_color($input[$key]) : null;
      if ($color) $out[$key] = $color;
    }
    foreach (['hero_title', 'hero_subtitle', 'phone', 'support'] as $key) {
      if (isset($input[$key])) $out[$key] = sanitize_text_field($input[$key]);
    }
    foreach (['sticky_header', 'animations', 'show_tours', 'show_why'] as $key) {
      $out[$key] = empty($input[$key]) ? 0 : 1;
    }
    return $out;
  }

  private static function field(string $name, string $label, string $type = 'text', string $help = ''): void {
    $value = esc_attr((string) self::get($name));
    echo '<tr><th scope="row"><label for="avanik_'.$name.'">'.esc_html($label).'</label></th><td>';
    if ($type === 'color') {
      echo '<input id="avanik_'.$name.'" name="avanik_theme_options['.$name.']" type="color" value="'.$value.'" class="av-admin-color">';
      echo '<code class="av-admin-color-code">'.$value.'</code>';
    } else {
      echo '<input id="avanik_'.$name.'" name="avanik_theme_options['.$name.']" type="text" value="'.$value.'" class="regular-text">';
    }
    if ($help) echo '<p class="description">'.esc_html($help).'</p>';
    echo '</td></tr>';
  }

  private static function toggle(string $name, string $label, string $help = ''): void {
    echo '<tr><th scope="row">'.esc_html($label).'</th><td><label class="av-admin-switch"><input type="checkbox" name="avanik_theme_options['.$name.']" value="1" '.checked((int) self::get($name), 1, false).'><span></span></label>';
    if ($help) echo '<p class="description">'.esc_html($help).'</p>';
    echo '</td></tr>';
  }

  public static function render(): void {
    if (!current_user_can('manage_options')) return;
    $logo = get_template_directory_uri().'/assets/images/avanik-logo.svg';
    ?>
    <div class="wrap av-admin-wrap" dir="rtl">
      <style>
        .av-admin-wrap{max-width:1100px}.av-admin-hero{background:linear-gradient(135deg,#072B5A,#0b447f);color:#fff;border-radius:18px;padding:28px 32px;margin:20px 0;display:flex;align-items:center;justify-content:space-between;gap:25px;box-shadow:0 14px 35px rgba(7,43,90,.18)}
        .av-admin-hero img{width:220px;max-height:70px;object-fit:contain;background:#fff;border-radius:12px;padding:8px}.av-admin-hero h1{color:#fff;margin:0 0 6px;font-size:28px}.av-admin-hero p{margin:0;opacity:.85}
        .av-admin-card{background:#fff;border:1px solid #e4e9ef;border-radius:16px;padding:24px;margin:18px 0;box-shadow:0 8px 25px rgba(15,35,60,.06)}.av-admin-card h2{margin-top:0;color:#072B5A;border-bottom:2px solid #F2B134;padding-bottom:10px}
        .av-admin-card th{width:230px;text-align:right}.av-admin-card td,.av-admin-card th{padding:16px 10px}.av-admin-color{width:70px!important;height:42px!important;padding:3px!important}.av-admin-color-code{margin-right:10px}.av-admin-switch{display:inline-flex;align-items:center}.av-admin-switch input{display:none}.av-admin-switch span{width:52px;height:28px;background:#c9d1dc;border-radius:30px;display:inline-block;position:relative;cursor:pointer}.av-admin-switch span:after{content:"";width:22px;height:22px;background:#fff;border-radius:50%;position:absolute;top:3px;right:3px;transition:.2s;box-shadow:0 2px 5px #0002}.av-admin-switch input:checked+span{background:#F2B134}.av-admin-switch input:checked+span:after{right:27px}.av-admin-submit{background:#072B5A!important;border-color:#072B5A!important;padding:8px 28px!important}.av-admin-note{border-right:4px solid #F2B134;background:#fff9ed;padding:14px 18px;border-radius:10px;margin-top:18px}.av-admin-list{line-height:2}.av-admin-list li{margin-bottom:5px}
      </style>
      <div class="av-admin-hero"><div><h1>تنظیمات قالب آوانیک</h1><p>کنترل ظاهر، صفحه اصلی و رفتارهای نمایشی قالب از داخل خود آوانیک.</p></div><img src="<?php echo esc_url($logo); ?>" alt="آوانیک پرواز آسیا"></div>
      <form method="post" action="options.php">
        <?php settings_fields('avanik_theme_options_group'); ?>
        <div class="av-admin-card"><h2>رنگ‌بندی آوانیک</h2><table class="form-table">
          <?php self::field('primary','رنگ سرمه‌ای اصلی','color','#072B5A'); self::field('gold','رنگ طلایی آوانیک','color','#F2B134'); self::field('white','رنگ سفید','color','#FFFFFF'); ?>
        </table></div>
        <div class="av-admin-card"><h2>صفحه اصلی</h2><table class="form-table">
          <?php self::field('hero_title','عنوان اصلی Hero'); self::field('hero_subtitle','زیرعنوان Hero'); self::field('phone','شماره تماس'); self::field('support','متن پشتیبانی'); ?>
        </table></div>
        <div class="av-admin-card"><h2>رفتار و انیمیشن</h2><table class="form-table">
          <?php self::toggle('sticky_header','هدر چسبان','هدر هنگام اسکرول بالای صفحه باقی بماند.'); self::toggle('animations','انیمیشن‌های نرم','انیمیشن ورود کارت‌ها و Hover فعال باشد.'); self::toggle('show_tours','نمایش بخش تورهای ویژه','کارت‌های تور در صفحه اصلی نمایش داده شوند.'); self::toggle('show_why','نمایش بخش چرا آوانیک','بخش مزیت‌های آوانیک در صفحه اصلی نمایش داده شود.'); ?>
        </table></div>
        <div class="av-admin-note">لوگوی آوانیک در این نسخه از فایل رسمی Bundle قالب استفاده می‌کند و برای جلوگیری از تغییر ناخواسته، مسیر آن ثابت نگه داشته شده است.</div>
        <?php submit_button('ذخیره تنظیمات آوانیک','primary','submit',true,['class'=>'av-admin-submit']); ?>
      </form>
    </div>
    <?php
  }

  public static function guide(): void {
    if (!current_user_can('manage_options')) return;
    echo '<div class="wrap av-admin-wrap" dir="rtl"><h1>راهنمای قالب آوانیک</h1><div class="av-admin-card"><h2>نصب و راه‌اندازی</h2><ol class="av-admin-list"><li>پوسته آوانیک را فعال کنید.</li><li>صفحات آماده و منوی اصلی به‌صورت خودکار ساخته می‌شوند.</li><li>برای تغییر ظاهر، فقط از منوی «آوانیک ← تنظیمات قالب» استفاده کنید.</li><li>برای PHP 8.3 و WordPress جدید آماده شده است.</li></ol><p>این نسخه شامل جریان نمایشی جستجو، هتل، تور، رزرو و پرداخت است.</p></div></div>';
  }

  public static function body_class(array $classes): array {
    if ((int) self::get('sticky_header') === 1) $classes[] = 'av-sticky-header-enabled';
    if ((int) self::get('animations') === 1) $classes[] = 'av-animations-enabled';
    return $classes;
  }

  public static function frontend_css(): void {
    if (is_admin()) return;
    $o = self::get();
    printf('<style>:root{--av-primary:%1$s;--av-primary-dark:%1$s;--av-accent:%2$s;--av-white:%3$s;}</style>', esc_attr($o['primary']), esc_attr($o['gold']), esc_attr($o['white']));
  }
}
