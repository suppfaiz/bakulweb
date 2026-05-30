<?php
// scratch/test_noc_integration.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();
$cookieFile = __DIR__ . '/noc_cookie.txt';
$baseUrl = 'http://127.0.0.1:8000';

if (file_exists($cookieFile)) {
    unlink($cookieFile);
}

echo "=============================================\n";
echo "Starting NOC Dashboard & Developer Tools Verification\n";
echo "=============================================\n";

// Helper function to send requests with cookies
function send_request($url, $postFields = null, $customHeaders = []) {
    global $cookieFile;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // do not follow redirects automatically so we can assert status codes
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

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
    curl_close($ch);

    return [
        'code' => $httpCode,
        'body' => $response,
        'redirect' => $redirectUrl
    ];
}

// 1. Verify that guest gets redirected to Login
echo "\n[Test 1] Verifying guest access redirection...\n";
$res = send_request($baseUrl . '/noc');
if ($res['code'] === 302 && strpos($res['redirect'], '/noc/login') !== false) {
    echo "✓ Guest successfully redirected to login page.\n";
} else {
    echo "❌ Fail: Guest was not redirected to login page. Code: " . $res['code'] . ", Redirect: " . $res['redirect'] . "\n";
    exit(1);
}

// 2. Perform NOC Login
echo "\n[Test 2] Performing NOC login...\n";
$loginRes = send_request($baseUrl . '/noc/login', ['password' => 'NocBakul2026!']);
if ($loginRes['code'] === 302 && strpos($loginRes['redirect'], '/noc') !== false) {
    echo "✓ Login request successful, redirecting to dashboard.\n";
} else {
    echo "❌ Fail: Login failed. Code: " . $loginRes['code'] . ", Body: " . substr($loginRes['body'], 0, 200) . "\n";
    exit(1);
}

// 3. Verify dashboard access after login
echo "\n[Test 3] Verifying dashboard access with session cookie...\n";
$dashRes = send_request($baseUrl . '/noc');
if ($dashRes['code'] === 200 && strpos($dashRes['body'], 'BAKUL NOC') !== false) {
    echo "✓ Dashboard page loaded successfully (authenticated).\n";
} else {
    echo "❌ Fail: Could not access dashboard. Code: " . $dashRes['code'] . "\n";
    exit(1);
}

// 4. Verify File Manager page and sidebar link structure
echo "\n[Test 4] Verifying File Manager access and sidebar links...\n";
$fmRes = send_request($baseUrl . '/noc/filemanager');
if ($fmRes['code'] === 200) {
    echo "✓ File Manager page loaded successfully.\n";
    
    // Check if sidebar has File Manager and SIEM Console links
    if (strpos($fmRes['body'], '/noc/filemanager') !== false && strpos($fmRes['body'], '/noc/siem') !== false) {
         echo "✓ Sidebar navigation is fully synchronized on File Manager page.\n";
    } else {
         echo "❌ Warning: Sidebar is missing critical links in File Manager page!\n";
    }
} else {
    echo "❌ Fail: File Manager failed to load. Code: " . $fmRes['code'] . "\n";
    exit(1);
}

// 5. Test File Manager: Create new file
echo "\n[Test 5] Testing file creation via File Manager...\n";
$testFilePath = realpath(__DIR__ . '/../scratch');
$testFileName = 'test_noc_editor.php';
$targetFile = $testFilePath . '/' . $testFileName;

if (file_exists($targetFile)) {
    unlink($targetFile);
}

$createRes = send_request($baseUrl . '/noc/create_file', [
    'dir' => $testFilePath,
    'name' => $testFileName,
    'type' => 'file'
]);

if ($createRes['code'] === 302 && file_exists($targetFile)) {
    echo "✓ File '$testFileName' successfully created on disk.\n";
} else {
    echo "❌ Fail: File creation failed or file not found on disk. Code: " . $createRes['code'] . "\n";
    exit(1);
}

// 6. Test File Manager: Edit and save file content
echo "\n[Test 6] Testing code editing and saving via Live Editor...\n";
$testContent = "<?php\n// NOC Live Editor verification file\necho 'Verification Success!';\n";
$saveRes = send_request($baseUrl . '/noc/save_file', [
    'file' => $targetFile,
    'content' => $testContent
]);

if ($saveRes['code'] === 302) {
    $writtenContent = file_get_contents($targetFile);
    if ($writtenContent === $testContent) {
        echo "✓ Code successfully written to disk and verified.\n";
    } else {
        echo "❌ Fail: File content mismatch. Expected: '$testContent', got: '$writtenContent'\n";
        exit(1);
    }
} else {
    echo "❌ Fail: File saving request failed. Code: " . $saveRes['code'] . "\n";
    exit(1);
}

// 7. Test File Manager: Delete file
echo "\n[Test 7] Testing file deletion via File Manager...\n";
$deleteRes = send_request($baseUrl . '/noc/delete_file', [
    'target' => $targetFile
]);

if ($deleteRes['code'] === 302 && !file_exists($targetFile)) {
    echo "✓ File '$testFileName' successfully deleted from disk.\n";
} else {
    echo "❌ Fail: File deletion failed. Code: " . $deleteRes['code'] . ", File exists: " . (file_exists($targetFile) ? 'YES' : 'NO') . "\n";
    exit(1);
}

// 8. Test Self-Audit and Auto-Fix: Security Headers
echo "\n[Test 8] Testing Self-Audit & Auto-Fix Security Headers...\n";
// Set setting to 0 first to simulate failing state
$db->query("INSERT INTO noc_settings (key_name, value_text) VALUES ('enable_security_headers', '0') ON DUPLICATE KEY UPDATE value_text = '0'");
$db->execute();

// Check audit report before fix
$auditResBefore = send_request($baseUrl . '/noc/audit');
if (strpos($auditResBefore['body'], 'Header tidak ada') !== false) {
    echo "✓ Audit successfully detected missing security headers.\n";
} else {
    echo "❌ Fail: Missing headers not caught by audit or template mismatch.\n";
}

// Trigger Auto-Fix
echo "Triggering headers Auto-Fix...\n";
$fixRes1 = send_request($baseUrl . '/noc/audit_fix?type=headers');
if ($fixRes1['code'] === 302) {
    echo "✓ Auto-Fix redirect received.\n";
    
    // Check DB value
    $db->query("SELECT value_text FROM noc_settings WHERE key_name = 'enable_security_headers'");
    $settingVal = $db->single()['value_text'] ?? '0';
    if ($settingVal === '1') {
        echo "✓ Database updated: enable_security_headers is now '1'.\n";
    } else {
        echo "❌ Fail: Database setting was not updated to '1'. Got: '$settingVal'\n";
        exit(1);
    }
    
    // Check audit report after fix
    $auditResAfter = send_request($baseUrl . '/noc/audit');
    if (strpos($auditResAfter['body'], 'Header tidak ada') === false && strpos($auditResAfter['body'], 'Header ditemukan') !== false) {
        echo "✓ Audit report updated: Security Headers are now LULUS/green.\n";
    } else {
        echo "❌ Fail: Audit report still shows headers as missing or failed.\n";
        exit(1);
    }
} else {
    echo "❌ Fail: Auto-Fix headers request failed. Code: " . $fixRes1['code'] . "\n";
    exit(1);
}

// 9. Test Self-Audit and Auto-Fix: File Exposure
echo "\n[Test 9] Testing Self-Audit & Auto-Fix File Exposure...\n";
// Set block_path_scan to off first to simulate fail
$db->query("INSERT INTO noc_settings (key_name, value_text) VALUES ('block_path_scan', 'off') ON DUPLICATE KEY UPDATE value_text = 'off'");
$db->execute();

// Check audit report before fix
$auditResBefore2 = send_request($baseUrl . '/noc/audit');
if (strpos($auditResBefore2['body'], 'File dapat diakses publik') !== false || strpos($auditResBefore2['body'], 'BAHAYA') !== false) {
    echo "✓ Audit successfully detected file exposure vulnerability.\n";
} else {
    echo "❌ Fail: File exposure not caught by audit.\n";
}

// Trigger Auto-Fix
echo "Triggering exposure Auto-Fix...\n";
$fixRes2 = send_request($baseUrl . '/noc/audit_fix?type=exposure');
if ($fixRes2['code'] === 302) {
    echo "✓ Auto-Fix redirect received.\n";
    
    // Check DB value
    $db->query("SELECT value_text FROM noc_settings WHERE key_name = 'block_path_scan'");
    $settingVal2 = $db->single()['value_text'] ?? 'off';
    if ($settingVal2 === 'block') {
        echo "✓ Database updated: block_path_scan is now 'block'.\n";
    } else {
        echo "❌ Fail: Database setting was not updated to 'block'. Got: '$settingVal2'\n";
        exit(1);
    }
    
    // Check audit report after fix
    $auditResAfter2 = send_request($baseUrl . '/noc/audit');
    if (strpos($auditResAfter2['body'], 'File dapat diakses publik') === false && strpos($auditResAfter2['body'], 'Tidak dapat diakses') !== false) {
        echo "✓ Audit report updated: Sensitive Files are now TERPROTEKSI/green.\n";
    } else {
        echo "❌ Fail: Audit report still shows files as exposed.\n";
        exit(1);
    }
} else {
    echo "❌ Fail: Auto-Fix exposure request failed. Code: " . $fixRes2['code'] . "\n";
    exit(1);
}

// 10. Clean up setting changes and temp file
echo "\n[Test 10] Cleaning up database and session files...\n";
if (file_exists($cookieFile)) {
    unlink($cookieFile);
}
// Revert settings to default
$db->query("UPDATE noc_settings SET value_text = '1' WHERE key_name = 'enable_security_headers'");
$db->execute();
$db->query("UPDATE noc_settings SET value_text = 'block' WHERE key_name = 'block_path_scan'");
$db->execute();

echo "✓ Settings successfully reset to secure defaults.\n";
echo "✓ NOC Dashboard Integration Verification COMPLETED SUCCESSFULLY!\n";
exit(0);
