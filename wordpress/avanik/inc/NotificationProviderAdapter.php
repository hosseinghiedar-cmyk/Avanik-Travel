<?php
namespace Avanik;
defined('ABSPATH') || exit;
interface NotificationProviderAdapterInterface {
 public function provider(): string;
 public function supports(string $channel): bool;
 public function send(string $channel,string $event,array $payload,int $user_id): array;
}
final class NotificationProviderAdapterRegistry {
 public static function register(): void { add_filter('avanik_notification_provider_adapters',fn($a)=>is_array($a)?$a:[]); }
 public static function adapters(): array { return (array)apply_filters('avanik_notification_provider_adapters',[]); }
 public static function resolve(string $channel,string $event,array $payload,int $user_id): ?NotificationProviderAdapterInterface { foreach(self::adapters() as $adapter){if($adapter instanceof NotificationProviderAdapterInterface&&$adapter->supports($channel))return $adapter;} return null; }
}
