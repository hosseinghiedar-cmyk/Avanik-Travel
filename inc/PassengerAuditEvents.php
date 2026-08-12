<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class PassengerAuditEvents {
 public static function record_view(string $booking_id,int $passenger_id,array $fields=[]): void { PassengerAuditLog::record('view',$booking_id,$passenger_id,$fields); }
 public static function record_update(string $booking_id,int $passenger_id,array $fields=[]): void { PassengerAuditLog::record('update',$booking_id,$passenger_id,$fields); }
 public static function record_create(string $booking_id,int $passenger_id,array $fields=[]): void { PassengerAuditLog::record('create',$booking_id,$passenger_id,$fields); }
 public static function record_delete(string $booking_id,int $passenger_id,array $fields=[]): void { PassengerAuditLog::record('delete',$booking_id,$passenger_id,$fields); }
}