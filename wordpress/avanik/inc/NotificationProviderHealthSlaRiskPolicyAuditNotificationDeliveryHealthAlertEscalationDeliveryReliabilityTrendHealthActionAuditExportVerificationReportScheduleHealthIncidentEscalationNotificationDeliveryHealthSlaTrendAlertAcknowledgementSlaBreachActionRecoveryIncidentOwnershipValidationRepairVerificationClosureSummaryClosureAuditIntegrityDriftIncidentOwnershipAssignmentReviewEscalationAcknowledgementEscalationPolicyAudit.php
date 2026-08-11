<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAudit {
    private const OPTION = 'avanik_sla_drift_escalation_ack_policy_audit';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Escalation Policy Audit', 'SLA Drift Escalation Policy Audit', self::CAPABILITY, 'avanik-sla-drift-escalation-policy-audit', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $policy = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicy::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $snapshot = [
            'acknowledgement_required'=>(bool)$policy['acknowledgement_required'],
            'policy'=>(string)$policy['policy'],
            'grace_state'=>(string)$policy['grace_state'],
            'transition'=>(string)$policy['transition'],
        ];
        $fingerprint = hash('sha256', wp_json_encode($snapshot));
        $state = [
            'fingerprint'=>$fingerprint,
            'previous_fingerprint'=>(string)($previous['fingerprint'] ?? ''),
            'changed'=>$fingerprint !== (string)($previous['fingerprint'] ?? ''),
            'snapshot'=>$snapshot,
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Escalation Policy Audit</h1><p>Phase 137 fingerprints the acknowledgement policy metadata for integrity auditing.</p><table class="widefat striped"><tbody>';
        foreach (['Changed'=>$s['changed']?'YES':'NO','Fingerprint'=>$s['fingerprint'],'Previous fingerprint'=>$s['previous_fingerprint'] ?: '—','Policy'=>$s['snapshot']['policy'],'Grace state'=>$s['snapshot']['grace_state'],'Transition'=>$s['snapshot']['transition'],'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
