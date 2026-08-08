<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class ModerationFilters {
  public static function register(): void { add_action('admin_post_avanik_product_moderate',[self::class,'moderate'],5); }
  public static function moderate(): void {
    if(!current_user_can('manage_options')||!check_admin_referer('avanik_product_moderate')) wp_die('Unauthorized');
    $id=(int)($_POST['product_id']??0); $decision=sanitize_key(wp_unslash($_POST['decision']??''));
    if($decision==='approve') ProductModeration::approve($id);
    if($decision==='reject') self::reject($id,sanitize_textarea_field(wp_unslash($_POST['rejection_reason']??'')));
    wp_safe_redirect(admin_url('admin.php?page=avanik-product-moderation')); exit;
  }
  private static function reject(int $id,string $reason): void { global $wpdb; $wpdb->update(ProductRepository::table_name(),['status'=>Product::REJECTED,'rejection_reason'=>$reason,'updated_at'=>current_time('mysql')],['id'=>$id]); }
}