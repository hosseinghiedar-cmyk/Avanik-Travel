<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class ProviderAdmin {
  public static function register(): void {
    add_action('admin_menu',[self::class,'menu'],40);
    add_action('admin_post_avanik_save_provider',[self::class,'save']);
  }
  public static function menu(): void {
    add_menu_page('تأمین‌کنندگان آوانیک','تأمین‌کنندگان','manage_options','avanik-providers',[self::class,'render'],'dashicons-networking',4);
    add_submenu_page('avanik-providers','تأمین‌کنندگان آوانیک','همه تأمین‌کنندگان','manage_options','avanik-providers',[self::class,'render']);
  }
  public static function render(): void {
    if(!current_user_can('manage_options'))return;
    $providers=ProviderRepository::all_enabled();
    echo '<div class="wrap av-admin-wrap" dir="rtl"><style>.av-admin-wrap{max-width:1180px}.av-admin-card{background:#fff;border:1px solid #e4e9ef;border-radius:18px;padding:24px;margin:20px 0;box-shadow:0 8px 28px rgba(15,35,60,.06)}.av-admin-card h1{color:#072B5A}.av-admin-card h2{color:#072B5A;border-bottom:2px solid #F2B134;padding-bottom:10px}.av-provider-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}.av-provider{border:1px solid #e6ebf1;border-radius:14px;padding:18px;background:#fbfcfe}.av-provider strong{display:block;color:#072B5A;font-size:17px}.av-provider span{display:block;color:#687386;font-size:13px;margin-top:5px}@media(max-width:800px){.av-provider-grid{grid-template-columns:1fr}}</style><div class="av-admin-card"><h1>تأمین‌کنندگان آوانیک</h1><p>مدیریت تأمین‌کنندگان پرواز، هتل و سایر سرویس‌های رزرو.</p><div class="av-provider-grid">';
    if(!$providers){echo '<div class="av-provider"><strong>تأمین‌کننده‌ای ثبت نشده است</strong><span>برای اتصال API می‌توانید تأمین‌کننده جدید اضافه کنید.</span></div>';}else{foreach($providers as $provider){echo '<div class="av-provider"><strong>'.esc_html($provider['name']).'</strong><span>کلید: '.esc_html($provider['provider_key']).'</span><span>نوع: '.esc_html($provider['type']).' · اولویت: '.esc_html($provider['priority']).'</span><span>وضعیت: فعال</span></div>';}}
    echo '</div></div></div>';
  }
  public static function save(): void {
    if(!current_user_can('manage_options')||!check_admin_referer('avanik_save_provider'))wp_die('دسترسی غیرمجاز');
    wp_safe_redirect(admin_url('admin.php?page=avanik-providers'));exit;
  }
}
