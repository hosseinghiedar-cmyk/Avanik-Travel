<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class RefundAuditLog {
 public static function install(): void { global $wpdb; require_once ABSPATH.'wp-admin/includes/upgrade.php'; $t=$wpdb->prefix.'avanik_refund_audit'; $c=$wpdb->get_charset_collate(); dbDelta("CREATE TABLE {$t} (id bigint unsigned NOT NULL AUTO_INCREMENT, refund_id varchar(40) NOT NULL, actor_id bigint unsigned NOT NULL DEFAULT 0, action varchar(32) NOT NULL, note text NOT NULL, created_at datetime NOT NULL, PRIMARY KEY(id), KEY refund_id(refund_id)) {$c};"); }
 public static function record(string $refund_id,int $actor,string $action,string $note=''): void { global $wpdb; $wpdb->insert($wpdb->prefix.'avanik_refund_audit',['refund_id'=>$refund_id,'actor_id'=>$actor,'action'=>sanitize_key($action),'note'=>sanitize_textarea_field($note),'created_at'=>current_time('mysql')],['%s','%d','%s','%s','%s']); }
}