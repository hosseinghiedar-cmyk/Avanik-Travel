<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryAction {
    private const OPTION = 'avanik_sla_drift_policy_audit_evidence_post_expiry_action';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Evidence Post-Expiry Action', 'SLA Drift Evidence Post-Expiry Action', self::CAPABILITY, 'avanik-sla-drift-policy-evidence-post-expiry', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $expiry = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheck::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $expired = !empty($expiry['expired']);
        $state = [
            'evidence_hash'=>(string)$expiry['evidence_hash'],
            'expired'=>$expired,
            'action'=>$expired ? 'review_required' : 'none',
            'automatic_deletion'=>false,
            'status'=>$expired ? 'pending_review' : 'not_applicable',
            'transition'=>$expired && empty($previous['expired']) ? 'opened' : ($expired ? 'steady' : (!empty($previous['expired']) ? 'resolved' : 'none')),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Evidence Post-Expiry Action</h1><p>Phase 142 defines a safe post-expiry action: manual review is required and automatic deletion remains disabled.</p><table class="widefat striped"><tbody>';
        foreach (['Evidence hash'=>$s['evidence_hash'],'Expired'=>$s['expired']?'YES':'NO','Action'=>strtoupper(str_replace('_',' ',$s['action'])),'Automatic deletion'=>$s['automatic_deletion']?'ENABLED':'DISABLED','Status'=>strtoupper(str_replace('_',' ',$s['status'])),'Transition'=>strtoupper($s['transition']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
