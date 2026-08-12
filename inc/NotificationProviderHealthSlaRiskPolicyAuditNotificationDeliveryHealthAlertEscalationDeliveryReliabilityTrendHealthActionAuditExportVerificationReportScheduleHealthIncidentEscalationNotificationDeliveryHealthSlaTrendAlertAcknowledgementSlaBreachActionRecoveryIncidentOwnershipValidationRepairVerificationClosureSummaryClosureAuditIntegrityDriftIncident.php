<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncident {
    private const OPTION = 'avanik_sla_closure_integrity_drift_incident';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Closure Integrity Drift Incident', 'SLA Closure Integrity Drift Incident', self::CAPABILITY, 'avanik-sla-closure-integrity-drift-incident', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $drift = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDrift::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $active = !empty($drift['drift_detected']);
        $transition = $active && empty($previous['active']) ? 'opened' : ($active ? 'steady' : (!empty($previous['active']) ? 'resolved' : 'none'));
        $state = [
            'active'=>$active,
            'transition'=>$transition,
            'severity'=>$active ? 'warning' : 'none',
            'fingerprint'=>(string)$drift['current_fingerprint'],
            'opened_at'=>$active && empty($previous['active']) ? time() : (int)($previous['opened_at'] ?? 0),
            'resolved_at'=>!$active && !empty($previous['active']) ? time() : (int)($previous['resolved_at'] ?? 0),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Closure Integrity Drift Incident</h1><p>Phase 130 promotes an active integrity drift into an explicit incident lifecycle.</p><table class="widefat striped"><tbody>';
        foreach (['Active'=>$s['active']?'YES':'NO','Transition'=>strtoupper($s['transition']),'Severity'=>strtoupper($s['severity']),'Fingerprint'=>$s['fingerprint'],'Opened at'=>$s['opened_at']?wp_date('Y-m-d H:i:s',$s['opened_at']):'—','Resolved at'=>$s['resolved_at']?wp_date('Y-m-d H:i:s',$s['resolved_at']):'—','Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
