<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class ProductMedia {
 public static function register(): void { add_action('admin_post_avanik_product_media',[self::class,'save']); }
 public static function save(): void { if(!is_user_logged_in()||!check_admin_referer('avanik_product_media')) wp_die('Unauthorized'); $u=wp_get_current_user(); if(!AgencyOnboarding::can_sell($u->ID)) wp_die('Seller account is not approved.'); $id=(int)($_POST['product_id']??0); if(!empty($_FILES['image']['name'])){ require_once ABSPATH.'wp-admin/includes/file.php'; require_once ABSPATH.'wp-admin/includes/media.php'; require_once ABSPATH.'wp-admin/includes/image.php'; $attachment=media_handle_upload('image',0); if(!is_wp_error($attachment)){ $rows=ProductRepository::for_supplier($u->ID); foreach($rows as $r){if((int)$r['id']===$id){$meta=json_decode((string)$r['metadata'],true)?:[];$meta['image_ids'][]=(int)$attachment;ProductRepository::update_owned($id,$u->ID,['type'=>$r['type'],'title'=>$r['title'],'price'=>$r['price'],'currency'=>$r['currency'],'capacity'=>$r['capacity'],'metadata'=>$meta]);break;}}}} wp_safe_redirect(wp_get_referer()?:home_url('/')); exit; }
}