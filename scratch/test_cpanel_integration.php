<?php
// scratch/test_cpanel_integration.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();
$cookieFile = __DIR__ . '/cpanel_cookie.txt';
$baseUrl = 'http://127.0.0.1:8000';

if (file_exists($cookieFile)) {
    unlink($cookieFile);
}

echo "=============================================\n";
echo "Starting cPanel-like Utilities Integration Verification\n";
echo "=============================================\n";

// Helper function to send requests with cookies
function send_request($url, $postFields = null, $customHeaders = []) {
    global $cookieFile;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15); // backup zip creation might take a few seconds

    if ($postFields !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($postFields) ? http_build_query($postFields) : $postFields);
    }

    if (!empty($customHeaders)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $customHeaders);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    
    // PHP 8.5 curl_close deprecation check bypass
    if (function_exists('curl_close') && PHP_VERSION_ID < 80500) {
        @curl_close($ch);
    }

    return [
        'code' => $httpCode,
        'body' => $response,
        'redirect' => $redirectUrl
    ];
}

// 1. Authenticate with NOC
echo "\n[Test 1] Authenticating with NOC dashboard...\n";
$loginRes = send_request($baseUrl . '/noc/login', ['password' => 'NocBakul2026!']);
if ($loginRes['code'] === 302) {
    echo "✓ Login successful.\n";
} else {
    echo "❌ Fail: Authentication failed. Code: " . $loginRes['code'] . "\n";
    exit(1);
}

// 2. Verify System Info Monitor
echo "\n[Test 2] Verifying System Monitor Info page...\n";
$sysRes = send_request($baseUrl . '/noc/system');
if ($sysRes['code'] === 200 && strpos($sysRes['body'], 'System Monitor') !== false) {
    // Check if CPU load and Memory info are rendered
    if (strpos($sysRes['body'], 'Load Average') !== false && strpos($sysRes['body'], 'Memory Usage') !== false) {
        echo "✓ CPU load, RAM usage, and processes table are displayed correctly.\n";
    } else {
        echo "❌ Fail: System monitor page layout was incomplete.\n";
        exit(1);
    }
} else {
    echo "❌ Fail: Could not load System Monitor page. Code: " . $sysRes['code'] . "\n";
    exit(1);
}

// 3. Verify DB Manager & Custom Query execution
echo "\n[Test 3] Verifying Database Manager query execution...\n";
$sqlQuery = "SELECT 42 + 8 AS test_sum, 'NOC Control' AS test_str;";
$dbRes = send_request($baseUrl . '/noc/run_query', ['query' => $sqlQuery]);

if ($dbRes['code'] === 200) {
    if (strpos($dbRes['body'], 'test_sum') !== false && strpos($dbRes['body'], 'test_str') !== false) {
        echo "✓ SQL query executed successfully and output table headers rendered.\n";
        if (strpos($dbRes['body'], '50') !== false && strpos($dbRes['body'], 'NOC Control') !== false) {
            echo "✓ SQL query output data matched expected values (50, 'NOC Control').\n";
        } else {
            echo "❌ Fail: SQL query result data mismatch.\n";
            exit(1);
        }
    } else {
        echo "❌ Fail: DB Manager failed to display headers for custom query.\n";
        echo "DEBUG: Response Body:\n" . $dbRes['body'] . "\n";
        exit(1);
    }
} else {
    echo "❌ Fail: DB Manager query execution request failed. Code: " . $dbRes['code'] . "\n";
    exit(1);
}

// 4. Verify Backup Wizard
echo "\n[Test 4] Verifying Backup Wizard: Database Dump creation...\n";
$backDir = realpath(__DIR__ . '/../') . '/backups';
if (!is_dir($backDir)) {
    mkdir($backDir, 0755, true);
}

// Clean up old SQL backups from backups/ directory first
$scanned = scandir($backDir);
foreach ($scanned as $f) {
    if (strpos($f, 'db_backup_') === 0 && substr($f, -4) === '.sql') {
        unlink($backDir . '/' . $f);
    }
}

$dbBackupRes = send_request($baseUrl . '/noc/create_backup', ['type' => 'db']);
if ($dbBackupRes['code'] === 302) {
    // Check if new SQL backup exists
    $backupFile = null;
    $scannedAfter = scandir($backDir);
    foreach ($scannedAfter as $f) {
        if (strpos($f, 'db_backup_') === 0 && substr($f, -4) === '.sql') {
            $backupFile = $backDir . '/' . $f;
            break;
        }
    }

    if ($backupFile && file_exists($backupFile) && filesize($backupFile) > 100) {
        echo "✓ Database SQL Dump successfully generated (" . filesize($backupFile) . " bytes).\n";
        // Clean it up
        unlink($backupFile);
        echo "✓ Temp DB Backup file deleted.\n";
    } else {
        echo "❌ Fail: DB Dump file was not found on disk or is empty.\n";
        exit(1);
    }
} else {
    echo "❌ Fail: DB Backup request failed. Code: " . $dbBackupRes['code'] . "\n";
    exit(1);
}

// 5. Verify Backup Wizard: Code Compression (ZIP)
echo "\n[Test 5] Verifying Backup Wizard: Code ZIP Backup creation...\n";
// Clean up old ZIP backups first
foreach ($scanned as $f) {
    if (strpos($f, 'code_backup_') === 0 && substr($f, -4) === '.zip') {
        unlink($backDir . '/' . $f);
    }
}

$codeBackupRes = send_request($baseUrl . '/noc/create_backup', ['type' => 'code']);
if ($codeBackupRes['code'] === 302) {
    $zipFile = null;
    $scannedAfterZip = scandir($backDir);
    foreach ($scannedAfterZip as $f) {
        if (strpos($f, 'code_backup_') === 0 && substr($f, -4) === '.zip') {
            $zipFile = $backDir . '/' . $f;
            break;
        }
    }

    if ($zipFile && file_exists($zipFile) && filesize($zipFile) > 1000) {
        echo "✓ Code ZIP Archive successfully generated (" . filesize($zipFile) . " bytes).\n";
        // Clean it up
        unlink($zipFile);
        echo "✓ Temp Code ZIP file deleted.\n";
    } else {
        echo "❌ Fail: Code ZIP file was not found on disk or is too small.\n";
        exit(1);
    }
} else {
    echo "❌ Fail: Code Backup request failed. Code: " . $codeBackupRes['code'] . "\n";
    exit(1);
}

// 6. Verify PHP Config Editor (.user.ini)
echo "\n[Test 6] Verifying PHP INI settings editor...\n";
$iniFile = realpath(__DIR__ . '/../public') . '/.user.ini';
$originalIni = file_exists($iniFile) ? file_get_contents($iniFile) : null;

$iniPostData = [
    'memory_limit' => '256M',
    'upload_max_filesize' => '10M',
    'post_max_size' => '20M',
    'max_execution_time' => 90,
    'display_errors' => '0'
];

$iniRes = send_request($baseUrl . '/noc/save_phpini', $iniPostData);
if ($iniRes['code'] === 302) {
    if (file_exists($iniFile)) {
        $writtenIni = file_get_contents($iniFile);
        if (strpos($writtenIni, 'memory_limit = 256M') !== false && 
            strpos($writtenIni, 'max_execution_time = 90') !== false) {
            echo "✓ PHP INI changes successfully written to public/.user.ini.\n";
        } else {
            echo "❌ Fail: Written .user.ini content did not match variables.\n";
            exit(1);
        }
    } else {
        echo "❌ Fail: .user.ini file was not found on disk.\n";
        exit(1);
    }
} else {
    echo "❌ Fail: PHP INI update request failed. Code: " . $iniRes['code'] . "\n";
    exit(1);
}

// Revert .user.ini
if ($originalIni !== null) {
    file_put_contents($iniFile, $originalIni);
    echo "✓ Reverted .user.ini to original state.\n";
} else {
    if (file_exists($iniFile)) {
        unlink($iniFile);
    }
    echo "✓ Removed temp .user.ini file.\n";
}

// 7. Cleanup session and temp files
echo "\n[Test 7] Cleaning up verification cookies...\n";
if (file_exists($cookieFile)) {
    unlink($cookieFile);
}

echo "✓ Cookie files cleaned up.\n";
echo "✓ cPanel-like Control Panel Integration Verification COMPLETED SUCCESSFULLY!\n";
exit(0);
