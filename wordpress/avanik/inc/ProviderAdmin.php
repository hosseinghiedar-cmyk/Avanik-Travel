<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class ProviderAdmin {
  public static function register(): void {
    add_action('admin_menu', [self::class, 'menu'], 40);
    add_action('admin_post_avanik_save_provider', [self::class, 'save']);
  }

  public static function menu(): void {
    add_menu_page('ارائه‌دهندگان آوانیک','ارائه‌دهندگان','manage_options','avanik-providers',[self::class,'render'],'dashicons-networking',3.1);
  }

  public static function render(): void {
    if (!current_user_can('manage_options')) return;
    $providers = ProviderRepository::all_enabled();
    ?>
    <div class="wrap av-admin-wrap" dir="rtl">
      <div class="av-admin-panel-title"><span class="dashicons dashicons-networking"></span><div><h1>ارائه‌دهندگان آوانیک</h1><p>تأمین‌کنندگان پرواز، هتل و خدمات را از این بخش مدیریت کنید.</p></div></div>
      <div class="av-admin-card"><table class="widefat striped"><thead><tr><th>نام</th><th>کلید</th><th>نوع</th><th>وضعیت</th><th>اولویت</th></tr></thead><tbody>
      <?php foreach ($providers as $provider): ?>
        <tr><td><?php echo esc_html($provider['name']); ?></td><td><code><?php echo esc_html($provider['provider_key']); ?></code></td><td><?php echo esc_html($provider['type']); ?></td><td><span class="av-status av-status--ok">فعال</span></td><td><?php echo esc_html($provider['priority']); ?></td></tr>
      <?php endforeach; ?>
      <?php if (!$providers): ?><tr><td colspan="5">ارائه‌دهنده فعالی ثبت نشده است.</td></tr><?php endif; ?>
      </tbody></table></div>
    </div>
    <?php
  }

  public static function save(): void {
    if (!current_user_can('manage_options') || !check_admin_referer('avanik_save_provider')) wp_die('دسترسی غیرمجاز');
    wp_safe_redirect(admin_url('admin.php?page=avanik-providers'));
    exit;
  }
}
