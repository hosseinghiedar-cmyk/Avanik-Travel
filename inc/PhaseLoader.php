<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class PhaseLoader {
    private const PHASES = [
        203 => 'Phase203ProjectStateInventory.php',
        204 => 'Phase204ProductionSupplierIntegrationReadiness.php',
        205 => 'Phase205SupplierConnectivityProbe.php',
        206 => 'Phase206SupplierApiContractReadiness.php',
        207 => 'Phase207SupplierSandboxProviderMapping.php',
        208 => 'Phase208SupplierSandboxContractValidation.php',
        209 => 'Phase209PaymentVerificationReadiness.php',
        210 => 'Phase210PaymentGatewayVerificationProbe.php',
        211 => 'Phase211TicketVoucherIssuanceReadiness.php',
        212 => 'Phase212SecurityHardeningReadiness.php',
        213 => 'Phase213EndToEndTestReadiness.php',
        214 => 'Phase214E2ETestExecution.php',
        215 => 'Phase215LoadStressTestReadiness.php',
        216 => 'Phase216ControlledLoadStressTest.php',
        217 => 'Phase217MonitoringAlertingReadiness.php',
        218 => 'Phase218BackupRestoreReadiness.php',
        219 => 'Phase219RollbackRecoveryReadiness.php',
        220 => 'Phase220StagingDeploymentReadiness.php',
        221 => 'Phase221ReleaseCandidate.php',
        222 => 'Phase222FinalProductionReadiness.php',
        223 => 'Phase223ProductionReleaseAuthorization.php',
        224 => 'Phase224ProductionDeploymentGate.php',
        225 => 'Phase225PostDeploymentSmokeTest.php',
        226 => 'Phase226ProductionMonitoringVerification.php',
        227 => 'Phase227FinalProjectClosure.php',
    ];

    public static function boot(): void {
        foreach (self::PHASES as $file) {
            $path = __DIR__ . '/' . $file;
            if (is_readable($path)) {
                require_once $path;
            }
        }
        foreach (self::PHASES as $number => $_file) {
            $class = __NAMESPACE__ . '\\Phase' . $number . self::classSuffix($number);
            if (class_exists($class) && method_exists($class, 'register')) {
                $class::register();
            }
        }
    }

    private static function classSuffix(int $number): string {
        return match ($number) {
            203 => 'ProjectStateInventory', 204 => 'ProductionSupplierIntegrationReadiness',
            205 => 'SupplierConnectivityProbe', 206 => 'SupplierApiContractReadiness',
            207 => 'SupplierSandboxProviderMapping', 208 => 'SupplierSandboxContractValidation',
            209 => 'PaymentVerificationReadiness', 210 => 'PaymentGatewayVerificationProbe',
            211 => 'TicketVoucherIssuanceReadiness', 212 => 'SecurityHardeningReadiness',
            213 => 'EndToEndTestReadiness', 214 => 'E2ETestExecution',
            215 => 'LoadStressTestReadiness', 216 => 'ControlledLoadStressTest',
            217 => 'MonitoringAlertingReadiness', 218 => 'BackupRestoreReadiness',
            219 => 'RollbackRecoveryReadiness', 220 => 'StagingDeploymentReadiness',
            221 => 'ReleaseCandidate', 222 => 'FinalProductionReadiness',
            223 => 'ProductionReleaseAuthorization', 224 => 'ProductionDeploymentGate',
            225 => 'PostDeploymentSmokeTest', 226 => 'ProductionMonitoringVerification',
            227 => 'FinalProjectClosure', default => '',
        };
    }
}
