<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class AgencyProductEditor {
  public static function register(): void { add_shortcode('avanik_product_editor',[self::class,'render']); add_action('admin_post_avanik_product_update',[self::class,'save']); }
  public static function render(): string {
    if(!is_user_logged_in()) return '<p>ابتدا وارد شوید.</p>'; $u=wp_get_current_user(); if(!AgencyOnboarding::can_sell($u->ID)) return '<p>حساب شما هنوز تأیید نشده است.</p>';
    $id=(int)($_GET['product_id']??0); $rows=ProductRepository::for_supplier($u->ID); $p=null; foreach($rows as $r){if((int)$r['id']===$id){$p=$r;break;}} if(!$p) return '<p>محصول پیدا نشد.</p>';
    $meta=json_decode((string)$p['metadata'],true)?:[]; ob_start(); ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" dir="rtl">
      <input type="hidden" name="action" value="avanik_product_update"><input type="hidden" name="product_id" value="<?php echo esc_attr($p['id']); ?>"><?php wp_nonce_field('avanik_product_update'); ?>
      <p><label>نوع <select name="type"><?php foreach(['tour'=>'تور','hotel'=>'هتل','flight'=>'پرواز','package'=>'پکیج'] as $k=>$v): ?><option value="<?php echo esc_attr($k); ?>" <?php selected($p['type'],$k); ?>><?php echo esc_html($v); ?></option><?php endforeach; ?></select></label></p>
      <p><label>عنوان<br><input required name="title" value="<?php echo esc_attr($p['title']); ?>"></label></p>
      <p><label>قیمت<br><input required type="number" min="0" step="0.01" name="price" value="<?php echo esc_attr($p['price']); ?>"></label></p>
      <p><label>ارز<br><input name="currency" value="<?php echo esc_attr($p['currency']); ?>"></label></p>
      <p><label>ظرفیت<br><input type="number" min="0" name="capacity" value="<?php echo esc_attr($p['capacity']); ?>"></label></p>
      <p><label>مقصد<br><input name="destination" value="<?php echo esc_attr($meta['destination']??''); ?>"></label></p>
      <p><label>تاریخ شروع<br><input type="date" name="start_date" value="<?php echo esc_attr($meta['start_date']??''); ?>"></label></p>
      <p><label>تاریخ پایان<br><input type="date" name="end_date" value="<?php echo esc_attr($meta['end_date']??''); ?>"></label></p>
      <p><label>توضیحات<br><textarea name="description" rows="7"><?php echo esc_textarea($meta['description']??''); ?></textarea></label></p>
      <button type="submit">ذخیره محصول</button>
    </form>
    <?php return (string)ob_get_clean();
  }
  public static function save(): void {
    if(!is_user_logged_in()||!check_admin_referer('avanik_product_update')) wp_die('Unauthorized'); $u=wp_get_current_user(); if(!AgencyOnboarding::can_sell($u->ID)) wp_die('Seller account is not approved.');
    ProductRepository::update_owned((int)($_POST['product_id']??0),$u->ID,[
      'type'=>wp_unslash($_POST['type']??Product::TOUR),'title'=>wp_unslash($_POST['title']??''),'price'=>(float)($_POST['price']??0),'currency'=>wp_unslash($_POST['currency']??'IRR'),'capacity'=>(int)($_POST['capacity']??0),
      'metadata'=>['destination'=>sanitize_text_field(wp_unslash($_POST['destination']??'')),'start_date'=>sanitize_text_field(wp_unslash($_POST['start_date']??'')),'end_date'=>sanitize_text_field(wp_unslash($_POST['end_date']??'')),'description'=>sanitize_textarea_field(wp_unslash($_POST['description']??''))]
    ]);
    wp_safe_redirect(wp_get_referer()?:home_url('/')); exit;
  }
}