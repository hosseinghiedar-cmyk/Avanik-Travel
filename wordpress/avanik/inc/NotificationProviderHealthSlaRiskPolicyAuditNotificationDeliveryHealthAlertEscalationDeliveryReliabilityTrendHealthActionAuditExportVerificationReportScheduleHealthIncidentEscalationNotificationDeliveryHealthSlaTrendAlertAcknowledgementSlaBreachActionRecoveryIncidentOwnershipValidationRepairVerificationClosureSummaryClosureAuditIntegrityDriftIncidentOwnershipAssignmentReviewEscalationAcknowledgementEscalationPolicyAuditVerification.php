<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerification {
    private const OPTION = 'avanik_sla_drift_escalation_ack_policy_audit_verification';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Escalation Policy Audit Verification', 'SLA Drift Escalation Policy Audit Verification', self::CAPABILITY, 'avanik-sla-drift-escalation-policy-audit-verification', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $audit = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAudit::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $fingerprint = (string)$audit['fingerprint'];
        $previous_fp = (string)($audit['previous_fingerprint'] ?? '');
        $valid = $fingerprint !== '' && strlen($fingerprint) === 64 && ctype_xdigit($fingerprint);
        $state = [
            'fingerprint'=>$fingerprint,
            'previous_fingerprint'=>$previous_fp,
            'fingerprint_valid'=>$valid,
            'audit_changed'=>(bool)$audit['changed'],
            'verification_status'=>$valid ? 'verified' : 'invalid',
            'transition'=>$valid && !$audit['changed'] && empty($previous['fingerprint_valid']) ? 'opened' : ($valid && $audit['changed'] ? 'changed' : ($valid ? 'steady' : 'failed')),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Escalation Policy Audit Verification</h1><p>Phase 138 verifies the structural integrity of the Phase 137 policy audit fingerprint.</p><table class="widefat striped"><tbody>';
        foreach (['Fingerprint valid'=>$s['fingerprint_valid']?'YES':'NO','Verification status'=>strtoupper($s['verification_status']),'Audit changed'=>$s['audit_changed']?'YES':'NO','Fingerprint'=>$s['fingerprint'],'Previous fingerprint'=>$s['previous_fingerprint'] ?: '—','Transition'=>strtoupper($s['transition']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
