<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class RefundReports {
 public static function summary(): array { global $wpdb; $t=RefundRepository::table_name(); $rows=$wpdb->get_results("SELECT status, COUNT(*) count, COALESCE(SUM(customer_refund),0) amount FROM {$t} GROUP BY status",ARRAY_A); $out=[]; foreach($rows as $r)$out[$r['status']]=['count'=>(int)$r['count'],'amount'=>(float)$r['amount']]; return $out; }
}