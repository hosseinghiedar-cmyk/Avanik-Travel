<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class ProductRepository {
  public static function table_name(): string { global $wpdb; return $wpdb->prefix . 'avanik_products'; }
  public static function install(): void {
    global $wpdb; require_once ABSPATH . 'wp-admin/includes/upgrade.php'; $table=self::table_name(); $charset=$wpdb->get_charset_collate();
    $sql="CREATE TABLE {$table} (id bigint(20) unsigned NOT NULL AUTO_INCREMENT,supplier_user_id bigint(20) unsigned NOT NULL,type varchar(30) NOT NULL,title varchar(255) NOT NULL,status varchar(30) NOT NULL DEFAULT 'draft',price decimal(18,2) NOT NULL DEFAULT 0,currency varchar(10) NOT NULL DEFAULT 'IRR',capacity int unsigned NOT NULL DEFAULT 0,inventory longtext NULL,metadata longtext NULL,rejection_reason text NULL,created_at datetime NOT NULL,updated_at datetime NOT NULL,published_at datetime NULL,PRIMARY KEY (id),KEY supplier_status (supplier_user_id,status),KEY type_status (type,status)) {$charset};"; dbDelta($sql);
  }
  public static function create(array $data): int { global $wpdb; $now=current_time('mysql'); $wpdb->insert(self::table_name(),['supplier_user_id'=>(int)($data['supplier_user_id']??0),'type'=>sanitize_key($data['type']??Product::TOUR),'title'=>sanitize_text_field($data['title']??''),'status'=>Product::DRAFT,'price'=>(float)($data['price']??0),'currency'=>sanitize_text_field($data['currency']??'IRR'),'capacity'=>max(0,(int)($data['capacity']??0)),'inventory'=>wp_json_encode($data['inventory']??[]),'metadata'=>wp_json_encode($data['metadata']??[]),'created_at'=>$now,'updated_at'=>$now]); return (int)$wpdb->insert_id; }
  public static function for_supplier(int $user_id): array { global $wpdb; return $wpdb->get_results($wpdb->prepare('SELECT * FROM '.self::table_name().' WHERE supplier_user_id=%d ORDER BY id DESC',$user_id),ARRAY_A)?:[]; }
  public static function update_owned(int $id,int $user_id,array $data): bool { global $wpdb; return false!==$wpdb->update(self::table_name(),['title'=>sanitize_text_field($data['title']??''),'type'=>sanitize_key($data['type']??Product::TOUR),'price'=>(float)($data['price']??0),'currency'=>sanitize_text_field($data['currency']??'IRR'),'capacity'=>max(0,(int)($data['capacity']??0)),'metadata'=>wp_json_encode($data['metadata']??[]),'updated_at'=>current_time('mysql'),'status'=>Product::DRAFT,'rejection_reason'=>null],['id'=>$id,'supplier_user_id'=>$user_id]); }
  public static function submit_for_review(int $id,int $supplier_user_id): bool { global $wpdb; return false!==$wpdb->update(self::table_name(),['status'=>Product::PENDING_REVIEW,'updated_at'=>current_time('mysql')],['id'=>$id,'supplier_user_id'=>$supplier_user_id]); }
  public static function delete_owned(int $id,int $user_id): bool { global $wpdb; return false!==$wpdb->delete(self::table_name(),['id'=>$id,'supplier_user_id'=>$user_id]); }
}