<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class AgencyDashboard {
  public static function register(): void {
    add_shortcode('avanik_agency_dashboard', [self::class, 'render']);
  }

  public static function render(): string {
    if (!is_user_logged_in()) return '<p>برای دسترسی به پنل آژانس ابتدا وارد شوید.</p>';
    $user = wp_get_current_user();
    if (!in_array(Marketplace::ROLE_SUPPLIER, (array) $user->roles, true) && !in_array(Marketplace::ROLE_AGENT, (array) $user->roles, true)) {
      return '<p>حساب شما مجوز دسترسی به پنل آژانس را ندارد.</p>';
    }
    ob_start();
    ?>
    <section class="av-agency-dashboard" dir="rtl">
      <h2>پنل همکاری آوانیک</h2>
      <p>خوش آمدید، <?php echo esc_html($user->display_name); ?>.</p>
      <div class="av-agency-dashboard__cards">
        <div>محصولات من</div><div>رزروهای من</div><div>درآمد و کمیسیون</div><div>پروفایل کسب‌وکار</div>
      </div>
    </section>
    <?php
    return (string) ob_get_clean();
  }
}
