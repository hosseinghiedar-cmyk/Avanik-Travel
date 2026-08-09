<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class BookingTicketBridge {
 public static function register(): void { add_action('avanik_provider_confirmed',[self::class,'on_confirmed'],20,2); }
 public static function on_confirmed(string $booking_id,array $result=[]): void { $ticket=TicketingService::issue($booking_id); if(is_wp_error($ticket))do_action('avanik_ticket_issue_failed',$booking_id,$ticket); }
}