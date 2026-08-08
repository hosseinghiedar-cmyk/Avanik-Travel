<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class BookingNotifications {
 public static function register(): void { add_action('avanik_booking_confirmed',[self::class,'confirmed'],20,1); add_action('avanik_booking_cancelled_customer',[self::class,'cancelled'],20,2); }
 private static function mail(array $b,string $subject,string $body): void { $user=get_user_by('id',(int)$b['customer_id']); $to=$user?$user->user_email:(string)$b['passenger_email']; if(is_email($to))wp_mail($to,$subject,$body,['Content-Type: text/plain; charset=UTF-8']); }
 public static function confirmed(string $id): void { $b=BookingRepository::find_by_id($id); if(!$b)return; self::mail($b,'آوانیک - تأیید رزرو','رزرو '.$id.' با موفقیت تأیید شد.'); }
 public static function cancelled(string $id,array $b=[]): void { if(!$b)$b=BookingRepository::find_by_id($id); if(!$b)return; self::mail($b,'آوانیک - لغو رزرو','رزرو '.$id.' لغو شد.'); }
}