<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class RefundIdempotency {
 public static function table_name(): string { global $wpdb; return $wpdb->prefix.'avanik_refund_idempotency'; }
 public static function install(): void { global $wpdb; require_once ABSPATH.'wp-admin/includes/upgrade.php'; $t=self::table_name(); $c=$wpdb->get_charset_collate(); dbDelta("CREATE TABLE {$t} (id bigint unsigned NOT NULL AUTO_INCREMENT, refund_id varchar(40) NOT NULL, idempotency_key varchar(80) NOT NULL, created_at datetime NOT NULL, PRIMARY KEY(id), UNIQUE KEY refund_key(refund_id,idempotency_key), UNIQUE KEY key_only(idempotency_key)) {$c};"); }
 public static function claim(string $refund_id,string $key): bool { global $wpdb; $key=sanitize_text_field($key); if($key==='')return false; return false!==$wpdb->insert(self::table_name(),['refund_id'=>$refund_id,'idempotency_key'=>$key,'created_at'=>current_time('mysql')],['%s','%s','%s']); }
}