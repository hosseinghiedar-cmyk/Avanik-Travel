<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAudit {
    private const OPTION = 'avanik_sla_drift_execution_authorization_audit';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Execution Authorization Audit', 'SLA Drift Execution Authorization Audit', self::CAPABILITY, 'avanik-sla-drift-execution-authorization-audit', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $authorization = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorization::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $fingerprint = hash('sha256', wp_json_encode([
            'evidence_hash'=>$authorization['evidence_hash'],
            'decision'=>$authorization['decision'],
            'authorized'=>$authorization['authorized'],
            'execution_allowed'=>$authorization['execution_allowed'],
            'status'=>$authorization['authorization_status'],
        ]));
        $state = [
            'fingerprint'=>$fingerprint,
            'evidence_hash'=>(string)$authorization['evidence_hash'],
            'authorization_status'=>(string)$authorization['authorization_status'],
            'authorized'=>(bool)$authorization['authorized'],
            'execution_allowed'=>(bool)$authorization['execution_allowed'],
            'audit_changed'=>!empty($previous['fingerprint']) && (string)$previous['fingerprint'] !== $fingerprint,
            'previous_fingerprint'=>(string)($previous['fingerprint'] ?? ''),
            'audited_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Execution Authorization Audit</h1><p>Phase 147 fingerprints the Phase 146 authorization state so authorization changes can be detected without executing any action.</p><table class="widefat striped"><tbody>';
        foreach (['Fingerprint'=>$s['fingerprint'],'Evidence hash'=>$s['evidence_hash'],'Authorization status'=>strtoupper($s['authorization_status']),'Authorized'=>$s['authorized']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Audit changed'=>$s['audit_changed']?'YES':'NO','Previous fingerprint'=>$s['previous_fingerprint'] ?: '—','Audited at'=>wp_date('Y-m-d H:i:s',$s['audited_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
