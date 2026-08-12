<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class AgencyProductDashboard {
  public static function register(): void {
    add_shortcode('avanik_agency_products', [self::class, 'render']);
  }

  public static function render(): string {
    if (!is_user_logged_in()) return '<p>ابتدا وارد حساب شوید.</p>';
    $user = wp_get_current_user();
    if (!AgencyOnboarding::can_sell($user->ID)) return '<p>حساب شما هنوز برای فروش تأیید نشده است.</p>';
    return '<section class="av-agency-products" dir="rtl"><h2>مدیریت محصولات</h2><p>ثبت و مدیریت تور، هتل، پرواز و پکیج از این بخش انجام می‌شود.</p></section>';
  }
}
