<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditIntegrityRecoveryNotification {
    private const EVENT = 'provider_sla_risk_policy_audit_integrity_recovered';
    private const OPTION = 'avanik_provider_sla_risk_policy_audit_integrity_recovery_notification';

    public static function register(): void {
        add_action('avanik_provider_health_sla_risk_notification_policy_audit_integrity_recovered', [self::class, 'notify'], 10, 1);
        add_filter('avanik_notification_recipients', [self::class, 'recipients'], 10, 3);
    }

    public static function notify(array $state): void {
        $previous = get_option(self::OPTION, []);
        if (!is_array($previous)) $previous = [];
        $recoveredAt = absint($state['recovered_at'] ?? 0);
        if ($recoveredAt && absint($previous['last_notified_at'] ?? 0) >= $recoveredAt) return;

        update_option(self::OPTION, [
            'last_notified_at' => $recoveredAt ?: time(),
            'incident_count' => absint($state['incident_count'] ?? 0),
        ], false);

        NotificationCenter::enqueue(self::EVENT, [
            'checked_at' => absint($state['checked_at'] ?? time()),
            'recovered_at' => $recoveredAt ?: time(),
            'incident_count' => absint($state['incident_count'] ?? 0),
            'integrity' => 'recovered',
        ]);
    }

    public static function recipients(array $recipients, string $event, array $payload): array {
        if ($event !== self::EVENT) return $recipients;
        $users = get_users(['role' => 'administrator', 'fields' => ['ID']]);
        foreach ($users as $user) {
            $uid = absint($user->ID ?? 0);
            if ($uid) {
                $recipients['admin_' . $uid] = [
                    'user_id' => $uid,
                    'channels' => ['internal' => true, 'email' => true],
                ];
            }
        }
        return $recipients;
    }
}
