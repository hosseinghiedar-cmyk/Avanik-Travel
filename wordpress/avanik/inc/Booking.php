<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class Booking {
 public const STATUS_PENDING='pending';
 public const STATUS_AWAITING_PAYMENT='awaiting_payment';
 public const STATUS_PAID='paid';
 public const STATUS_CONFIRMED='confirmed';
 public const STATUS_TICKETED='ticketed';
 public const STATUS_CANCELLED='cancelled';
 public const STATUS_REFUNDED='refunded';
 public const STATUS_FAILED='failed';
 public const STATUS_EXPIRED='expired';
 public static function statuses(): array { return [self::STATUS_PENDING=>'در انتظار',self::STATUS_AWAITING_PAYMENT=>'در انتظار پرداخت',self::STATUS_PAID=>'پرداخت شده',self::STATUS_CONFIRMED=>'تأیید شده',self::STATUS_TICKETED=>'Ticketed',self::STATUS_CANCELLED=>'لغو شده',self::STATUS_REFUNDED=>'مسترد شده',self::STATUS_FAILED=>'ناموفق',self::STATUS_EXPIRED=>'منقضی شده']; }
 public static function generate_id(): string { return 'AVN-'.strtoupper(wp_generate_password(10,false,false)); }
 public static function is_valid_status(string $status): bool { return array_key_exists($status,self::statuses()); }
 public static function terminal_statuses(): array { return [self::STATUS_TICKETED,self::STATUS_CANCELLED,self::STATUS_REFUNDED,self::STATUS_FAILED,self::STATUS_EXPIRED]; }
 public static function can_transition(string $from,string $to): bool { if(!self::is_valid_status($from)||!self::is_valid_status($to)||$from===$to)return false; $map=[self::STATUS_PENDING=>[self::STATUS_AWAITING_PAYMENT,self::STATUS_FAILED,self::STATUS_EXPIRED,self::STATUS_CANCELLED],self::STATUS_AWAITING_PAYMENT=>[self::STATUS_PAID,self::STATUS_FAILED,self::STATUS_EXPIRED,self::STATUS_CANCELLED],self::STATUS_PAID=>[self::STATUS_CONFIRMED,self::STATUS_CANCELLED],self::STATUS_CONFIRMED=>[self::STATUS_TICKETED,self::STATUS_CANCELLED],self::STATUS_TICKETED=>[self::STATUS_CANCELLED],self::STATUS_CANCELLED=>[self::STATUS_REFUNDED],self::STATUS_REFUNDED=>[],self::STATUS_FAILED=>[],self::STATUS_EXPIRED=>[]]; return in_array($to,$map[$from]??[],true); }
}