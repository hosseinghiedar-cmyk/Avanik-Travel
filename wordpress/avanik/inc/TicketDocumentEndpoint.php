<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class TicketDocumentEndpoint {
 public static function register(): void { add_action('init',[self::class,'handle']); }
 public static function handle(): void { if(!isset($_GET['avanik_ticket_download']))return; if(!is_user_logged_in())wp_die('Unauthorized',403); $token=sanitize_text_field(wp_unslash($_GET['avanik_ticket_download'])); $ticket_id=TicketDocumentService::consume_download_token($token,get_current_user_id()); if(!$ticket_id)wp_die('Invalid or expired download token.',403); $tickets=TicketRepository::find_by_booking($ticket_id); if(!$tickets)wp_die('Ticket not found.',404); wp_die('Secure ticket document endpoint is ready; PDF renderer/storage adapter must provide the file.',501); }
}