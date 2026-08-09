<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class PassengerLegacyMigration {
 public static function migrate_batch(int $limit=100): int { if(!PassengerDataSecurity::encryption_ready())return 0; global $wpdb; $rows=$wpdb->get_results($wpdb->prepare('SELECT * FROM '.BookingPassengers::table_name().' ORDER BY id ASC LIMIT %d',max(1,min(500,$limit))),ARRAY_A)?:[]; $count=0; foreach($rows as $r){$updates=[]; foreach(['national_id','passport_no'] as $f){$v=(string)($r[$f]??''); if($v===''||self::looks_encrypted($v))continue;$updates[$f]=PassengerDataSecurity::encrypt($v);} if($updates){$updates['updated_at']=current_time('mysql');$wpdb->update(BookingPassengers::table_name(),$updates,['id'=>(int)$r['id']]);$count++;}} return $count; }
 private static function looks_encrypted(string $value): bool { $raw=base64_decode($value,true); return $raw!==false&&strlen($raw)>32; }
}