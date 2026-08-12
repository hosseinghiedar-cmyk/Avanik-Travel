<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class ProviderAdmin {
  public static function register(): void {
    add_action('admin_menu', [self::class, 'menu'], 40);
    add_action('admin_post_avanik_save_provider', [self::class, 'save']);
  }

  public static function menu(): void {
    add_submenu_page(
      'avanik-theme-settings',
      'مدیریت تأمین‌کنندگان آوانیک',
      'تأمین‌کنندگان',
      'manage_options',
      'avanik-providers',
      [self::class, 'render']
    );
  }

  public static function render(): void {
    if (!current_user_can('manage_options')) return;
    $providers = ProviderRepository::all_enabled();
    ?>
    <div class="wrap" dir="rtl" style="font-family:Tahoma,"Vazirmatn",Arial,sans-serif">
      <h1>مدیریت تأمین‌کنندگان آوانیک</h1>
      <p>تأمین‌کنندگان پرواز، هتل و خدمات را از این بخش مدیریت کنید.</p>
      <table class="widefat striped"><thead><tr><th>نام</th><th>کلید</th><th>نوع</th><th>وضعیت</th><th>اولویت</th></tr></thead><tbody>
      <?php foreach ($providers as $provider): ?>
        <tr><td><?php echo esc_html($provider['name']); ?></td><td><?php echo esc_html($provider['provider_key']); ?></td><td><?php echo esc_html($provider['type']); ?></td><td>فعال</td><td><?php echo esc_html($provider['priority']); ?></td></tr>
      <?php endforeach; ?>
      </tbody></table>
    </div>
    <?php
  }

  public static function save(): void {
    if (!current_user_can('manage_options') || !check_admin_referer('avanik_save_provider')) wp_die('دسترسی غیرمجاز');
    wp_safe_redirect(admin_url('admin.php?page=avanik-providers'));
    exit;
  }
}
