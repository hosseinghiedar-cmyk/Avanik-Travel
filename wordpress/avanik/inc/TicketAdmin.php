<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class TicketAdmin {
 public static function register(): void { add_action('admin_menu',[self::class,'menu']); }
 public static function menu(): void { add_submenu_page('tools.php','Avanik Tickets','Avanik Tickets','manage_options','avanik-tickets',[self::class,'render']); }
 public static function render(): void { if(!current_user_can('manage_options'))return; $booking_id=sanitize_text_field($_GET['booking_id']??''); echo '<div class="wrap"><h1>Avanik Tickets</h1>'; if($booking_id===''){echo '<p>Booking ID را در آدرس صفحه وارد کنید.</p></div>';return;} $tickets=TicketRepository::find_by_booking($booking_id); echo '<h2>Booking: '.esc_html($booking_id).'</h2><table class="widefat striped"><thead><tr><th>Ticket ID</th><th>PNR</th><th>Ticket</th><th>Voucher</th><th>Status</th><th>Issued</th></tr></thead><tbody>'; foreach($tickets as $t){echo '<tr><td>'.esc_html($t['ticket_id']).'</td><td>'.esc_html($t['pnr']).'</td><td>'.esc_html($t['ticket_number']).'</td><td>'.esc_html($t['voucher_reference']).'</td><td>'.esc_html($t['status']).'</td><td>'.esc_html($t['issued_at']).'</td></tr>';} if(!$tickets)echo '<tr><td colspan="6">Ticket یافت نشد.</td></tr>'; echo '</tbody></table></div>'; }
}