<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class PassengerCustomFieldAdmin {
 public static function register(): void { add_action('admin_post_avanik_save_passenger_fields',[self::class,'save']); }
 public static function save(): void { if(!is_user_logged_in()||!current_user_can('edit_posts')||!check_admin_referer('avanik_passenger_fields'))wp_die('Unauthorized'); $id=absint($_POST['product_id']??0); $fields=$_POST['fields']??[]; if(!is_array($fields))$fields=[]; PassengerCustomFields::save($id,get_current_user_id(),$fields); wp_safe_redirect(wp_get_referer()?:admin_url()); exit; }
}