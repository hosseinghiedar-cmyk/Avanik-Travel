<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAudit {
    private const OPTION = 'avanik_sla_drift_ack_verification_audit';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Acknowledgement Verification Audit', 'SLA Drift Acknowledgement Verification Audit', self::CAPABILITY, 'avanik-sla-drift-ack-verification-audit', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $verification = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerification::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $fingerprint = hash('sha256', wp_json_encode([
            'verification_status'=>$verification['verification_status'] ?? 'unknown',
            'acknowledged'=>$verification['acknowledged'] ?? false,
            'acknowledged_by'=>$verification['acknowledged_by'] ?? 0,
            'acknowledged_at'=>$verification['acknowledged_at'] ?? 0,
        ]));
        $changed = !empty($previous['fingerprint']) && $previous['fingerprint'] !== $fingerprint;
        $result = [
            'fingerprint'=>$fingerprint,
            'verification_status'=>(string)($verification['verification_status'] ?? 'unknown'),
            'audit_status'=>$changed ? 'changed' : 'stable',
            'change_detected'=>$changed,
            'previous_fingerprint'=>(string)($previous['fingerprint'] ?? ''),
            'audited_at'=>time(),
        ];
        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Acknowledgement Verification Audit</h1><p>Phase 152 fingerprints the Phase 151 verification state and detects verification-state changes.</p><table class="widefat striped"><tbody>';
        foreach (['Fingerprint'=>$s['fingerprint'],'Verification status'=>strtoupper($s['verification_status']),'Audit status'=>strtoupper($s['audit_status']),'Change detected'=>$s['change_detected']?'YES':'NO','Previous fingerprint'=>$s['previous_fingerprint'] ?: '—','Audited at'=>wp_date('Y-m-d H:i:s',$s['audited_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
