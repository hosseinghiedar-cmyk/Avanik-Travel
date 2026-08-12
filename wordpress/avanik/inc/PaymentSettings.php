<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class PaymentSettings {
  const OPTION='avanik_payment_settings';

  public static function register(): void {
    add_action('admin_init',[self::class,'settings']);
    add_action('admin_menu',[self::class,'menu'],50);
  }

  public static function settings(): void {
    register_setting('avanik_payment_settings',self::OPTION,['sanitize_callback'=>[self::class,'sanitize']]);
  }

  public static function menu(): void {
    add_submenu_page('avanik-theme-settings','تنظیمات پرداخت آوانیک','پرداخت و درگاه','manage_options','avanik-payment-settings',[self::class,'render']);
  }

  public static function get(): array {
    $v=get_option(self::OPTION,[]);
    return wp_parse_args(is_array($v)?$v:[],['enabled'=>1,'gateway'=>'zarinpal','zarinpal_mode'=>'disabled','zarinpal_merchant_id'=>'','zarinpal_endpoint'=>'','zarinpal_callback_url'=>'','zarinpal_plugin_compat'=>1]);
  }

  public static function sanitize($input): array {
    $input=is_array($input)?$input:[];
    return [
      'enabled'=>empty($input['enabled'])?0:1,
      'gateway'=>in_array(($input['gateway']??'zarinpal'),['zarinpal','card_to_card'],true)?$input['gateway']:'zarinpal',
      'zarinpal_mode'=>in_array(($input['zarinpal_mode']??'disabled'),['disabled','sandbox','production','plugin'],true)?$input['zarinpal_mode']:'disabled',
      'zarinpal_merchant_id'=>sanitize_text_field($input['zarinpal_merchant_id']??''),
      'zarinpal_endpoint'=>esc_url_raw($input['zarinpal_endpoint']??''),
      'zarinpal_callback_url'=>esc_url_raw($input['zarinpal_callback_url']??''),
      'zarinpal_plugin_compat'=>empty($input['zarinpal_plugin_compat'])?0:1
    ];
  }

  public static function render(): void {
    if(!current_user_can('manage_options'))return;
    $s=self::get();
    ?>
    <div class="wrap" dir="rtl" style="font-family:Tahoma,'Vazirmatn',Arial,sans-serif;max-width:1100px">
      <h1>پرداخت و درگاه آوانیک</h1>
      <p>تنظیمات پرداخت از داخل منوی «آوانیک» مدیریت می‌شود.</p>
      <form method="post" action="options.php">
        <?php settings_fields('avanik_payment_settings'); ?>
        <table class="form-table">
          <tr><th>فعال بودن پرداخت</th><td><label><input type="checkbox" name="<?php echo esc_attr(self::OPTION); ?>[enabled]" value="1" <?php checked($s['enabled'],1); ?>> فعال</label></td></tr>
          <tr><th>درگاه پیش‌فرض</th><td><select name="<?php echo esc_attr(self::OPTION); ?>[gateway]"><option value="zarinpal" <?php selected($s['gateway'],'zarinpal'); ?>>زرین‌پال</option><option value="card_to_card" <?php selected($s['gateway'],'card_to_card'); ?>>کارت به کارت</option></select></td></tr>
          <tr><th>حالت زرین‌پال</th><td><select name="<?php echo esc_attr(self::OPTION); ?>[zarinpal_mode]"><option value="disabled" <?php selected($s['zarinpal_mode'],'disabled'); ?>>فعلاً غیرفعال</option><option value="sandbox" <?php selected($s['zarinpal_mode'],'sandbox'); ?>>آزمایشی</option><option value="production" <?php selected($s['zarinpal_mode'],'production'); ?>>عملیاتی</option><option value="plugin" <?php selected($s['zarinpal_mode'],'plugin'); ?>>استفاده از افزونه زرین‌پال</option></select></td></tr>
          <tr><th>شناسه پذیرنده</th><td><input class="regular-text" name="<?php echo esc_attr(self::OPTION); ?>[zarinpal_merchant_id]" value="<?php echo esc_attr($s['zarinpal_merchant_id']); ?>"></td></tr>
          <tr><th>نشانی API سفارشی</th><td><input class="regular-text" name="<?php echo esc_attr(self::OPTION); ?>[zarinpal_endpoint]" value="<?php echo esc_attr($s['zarinpal_endpoint']); ?>"><p class="description">برای تغییر API یا سرویس واسط، بدون تغییر هسته.</p></td></tr>
          <tr><th>نشانی بازگشت سفارشی</th><td><input class="regular-text" name="<?php echo esc_attr(self::OPTION); ?>[zarinpal_callback_url]" value="<?php echo esc_attr($s['zarinpal_callback_url']); ?>"></td></tr>
          <tr><th>سازگاری با افزونه زرین‌پال</th><td><label><input type="checkbox" name="<?php echo esc_attr(self::OPTION); ?>[zarinpal_plugin_compat]" value="1" <?php checked($s['zarinpal_plugin_compat'],1); ?>> فعال</label></td></tr>
        </table>
        <?php submit_button('ذخیره تنظیمات پرداخت','primary'); ?>
      </form>
    </div>
    <?php
  }
}
