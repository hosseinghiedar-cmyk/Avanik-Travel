<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidence {
    private const OPTION = 'avanik_sla_drift_policy_audit_verification_evidence';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Policy Audit Verification Evidence', 'SLA Drift Policy Audit Verification Evidence', self::CAPABILITY, 'avanik-sla-drift-policy-audit-evidence', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $verification = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerification::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $evidence = [
            'fingerprint'=>(string)$verification['fingerprint'],
            'verification_status'=>(string)$verification['verification_status'],
            'fingerprint_valid'=>(bool)$verification['fingerprint_valid'],
            'audit_changed'=>(bool)$verification['audit_changed'],
            'transition'=>(string)$verification['transition'],
        ];
        $evidence_hash = hash('sha256', wp_json_encode($evidence));
        $state = [
            'evidence_hash'=>$evidence_hash,
            'previous_evidence_hash'=>(string)($previous['evidence_hash'] ?? ''),
            'evidence_changed'=>$evidence_hash !== (string)($previous['evidence_hash'] ?? ''),
            'evidence'=>$evidence,
            'status'=>$verification['verification_status'] === 'verified' ? 'evidence_valid' : 'evidence_invalid',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Policy Audit Verification Evidence</h1><p>Phase 139 creates a tamper-evident evidence fingerprint from the Phase 138 verification result.</p><table class="widefat striped"><tbody>';
        foreach (['Evidence status'=>strtoupper(str_replace('_',' ',$s['status'])),'Evidence changed'=>$s['evidence_changed']?'YES':'NO','Evidence hash'=>$s['evidence_hash'],'Previous evidence hash'=>$s['previous_evidence_hash'] ?: '—','Verification status'=>$s['evidence']['verification_status'],'Audit changed'=>$s['evidence']['audit_changed']?'YES':'NO','Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
