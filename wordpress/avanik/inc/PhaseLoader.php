<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class PhaseLoader {
    private const PHASES = array(
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
        227 => 'Phase227FinalProjectClosure.php'
    );

    public static function boot() {
        foreach (self::PHASES as $file) {
            $path = __DIR__ . '/' . $file;
            if (is_readable($path)) {
                require_once $path;
            }
        }
        foreach (self::PHASES as $number => $_file) {
            $class = __NAMESPACE__ . '\\Phase' . $number . self::classSuffix($number);
            if (class_exists($class) && method_exists($class, 'register')) {
                call_user_func(array($class, 'register'));
            }
        }
    }

    private static function classSuffix($number) {
        switch ((int) $number) {
            case 203: return 'ProjectStateInventory';
            case 204: return 'ProductionSupplierIntegrationReadiness';
            case 205: return 'SupplierConnectivityProbe';
            case 206: return 'SupplierApiContractReadiness';
            case 207: return 'SupplierSandboxProviderMapping';
            case 208: return 'SupplierSandboxContractValidation';
            case 209: return 'PaymentVerificationReadiness';
            case 210: return 'PaymentGatewayVerificationProbe';
            case 211: return 'TicketVoucherIssuanceReadiness';
            case 212: return 'SecurityHardeningReadiness';
            case 213: return 'EndToEndTestReadiness';
            case 214: return 'E2ETestExecution';
            case 215: return 'LoadStressTestReadiness';
            case 216: return 'ControlledLoadStressTest';
            case 217: return 'MonitoringAlertingReadiness';
            case 218: return 'BackupRestoreReadiness';
            case 219: return 'RollbackRecoveryReadiness';
            case 220: return 'StagingDeploymentReadiness';
            case 221: return 'ReleaseCandidate';
            case 222: return 'FinalProductionReadiness';
            case 223: return 'ProductionReleaseAuthorization';
            case 224: return 'ProductionDeploymentGate';
            case 225: return 'PostDeploymentSmokeTest';
            case 226: return 'ProductionMonitoringVerification';
            case 227: return 'FinalProjectClosure';
            default: return '';
        }
    }
}
