<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class PassengerSecurityPolicy {
 public static function view(array $booking,array $passenger,bool $reveal=false): array { if(!PassengerAccessPolicy::can_view($booking))return []; $privileged=$reveal&&current_user_can('manage_options'); $out=PassengerAccessPolicy::fields_for_view($passenger,$privileged); PassengerAuditEvents::record_view((string)($booking['id']??$booking['booking_id']??''),(int)($passenger['id']??0),['passenger']); return $out; }
 public static function update_allowed(array $booking): bool { return PassengerAccessPolicy::can_view($booking); }
 public static function update(array $booking,array $passenger,array $fields): bool { if(!self::update_allowed($booking))return false; PassengerAuditEvents::record_update((string)($booking['id']??$booking['booking_id']??''),(int)($passenger['id']??0),array_keys($fields)); return true; }
}