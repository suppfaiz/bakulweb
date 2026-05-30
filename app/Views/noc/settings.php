<?php // NOC — Settings View ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --white: #111827;
            --bg: #090d16;
            --surface: #111827;
            --surface-2: #1f2937;
            --border: rgba(56, 189, 248, 0.15);
            --border-light: rgba(255, 255, 255, 0.05);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #475569;
            --blue-500: #38bdf8;
            --blue-600: #0284c7;
            --blue-50: rgba(56, 189, 248, 0.08);
            --green-500: #4ade80;
            --green-50: rgba(74, 222, 128, 0.08);
            --sidebar-w: 240px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-primary); display: flex; min-height: 100vh; }

        /* ─── Sidebar ─── */
        .sidebar { width: var(--sidebar-w); min-height: 100vh; background: var(--white); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 100; }
        .sidebar-brand { padding: 20px 20px 16px; border-bottom: 1px solid var(--border-light); }
        .brand-logo { display: flex; align-items: center; gap: 10px; }
        .brand-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .brand-text h2 { font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .brand-text p { font-size: 11px; color: var(--text-muted); }
        .live-badge { display: flex; align-items: center; gap: 5px; margin-top: 10px; font-size: 10.5px; font-weight: 600; color: #22c55e; }
        .live-dot { width: 6px; height: 6px; background: #22c55e; border-radius: 50%; animation: livepulse 1.5s infinite; }
        @keyframes livepulse { 0%, 100% { opacity: 1 } 50% { opacity: .5 } }
        
        .nav-section { padding: 16px 12px 8px; }
        .nav-label { font-size: 10px; font-weight: 700; color: var(--text-muted); letter-spacing: .8px; text-transform: uppercase; padding: 0 8px; margin-bottom: 4px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 9px; font-size: 13.5px; font-weight: 500; color: var(--text-secondary); text-decoration: none; transition: all .15s; margin-bottom: 2px; }
        .nav-item:hover { background: var(--bg); color: var(--text-primary); }
        .nav-item.active { background: var(--blue-50); color: var(--blue-500); font-weight: 600; border: 1px solid var(--border); }
        .nav-item .icon { font-size: 16px; width: 20px; text-align: center; }
        
        .sidebar-footer { margin-top: auto; padding: 16px 12px; border-top: 1px solid var(--border-light); }
        .sidebar-footer a { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); text-decoration: none; padding: 8px 10px; border-radius: 8px; transition: all .15s; margin-bottom: 4px; }
        .sidebar-footer a:hover { background: rgba(239, 68, 68, 0.1); color: #f87171; }

        /* ─── Main ─── */
        .main { margin-left: var(--sidebar-w); flex: 1; min-width: 0; }
        .topbar { background: var(--white); border-bottom: 1px solid var(--border); padding: 14px 28px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar h1 { font-size: 17px; font-weight: 700; }
        .topbar p { font-size: 12.5px; color: var(--text-muted); }
        .content { padding: 24px 28px; max-width: 800px; }

        .card { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 24px; margin-bottom: 24px; }
        .card-title { font-size: 15px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; color: var(--text-primary); border-bottom: 1px solid var(--border-light); padding-bottom: 10px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
        .form-group select, .form-group input[type="text"] { width: 100%; padding: 10px 12px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13.5px; outline: none; transition: all .15s; background: var(--bg); color: var(--text-primary); }
        .form-group select:focus, .form-group input[type="text"]:focus { border-color: var(--blue-500); background: var(--surface-2); box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1); }
        .form-group .help-text { font-size: 12px; color: var(--text-muted); margin-top: 5px; line-height: 1.4; }

        .btn-primary { padding: 10px 20px; background: linear-gradient(135deg, var(--blue-500), var(--blue-600)); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(56,189,248,.2); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(56,189,248,.3); }

        .alert-success { background: var(--green-50); border: 1px solid rgba(74,222,128,.3); color: var(--green-500); border-radius: 8px; padding: 12px 16px; font-size: 13.5px; margin-bottom: 20px; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon">🛡️</div>
            <div class="brand-text"><h2>BAKUL NOC</h2><p>Security Center</p></div>
        </div>
        <div class="live-badge"><div class="live-dot"></div> MONITORING ACTIVE</div>
    </div>
    <nav class="nav-section">
        <div class="nav-label">Monitor</div>
        <a href="<?= BASEURL ?>/noc"          class="nav-item"><span class="icon">📊</span> Dashboard</a>
        <a href="<?= BASEURL ?>/noc/logs"     class="nav-item"><span class="icon">📋</span> Traffic Logs</a>
        <a href="<?= BASEURL ?>/noc/blocked"  class="nav-item"><span class="icon">🚫</span> Blocked IPs</a>
    </nav>
    <nav class="nav-section">
        <div class="nav-label">Security</div>
        <a href="<?= BASEURL ?>/noc/audit" class="nav-item"><span class="icon">🔍</span> Self-Audit</a>
        <a href="<?= BASEURL ?>/noc/siem" class="nav-item"><span class="icon">🛡️</span> SIEM Console</a>
        <a href="<?= BASEURL ?>/noc/settings" class="nav-item active"><span class="icon">⚙️</span> Settings</a>
    </nav>
    <nav class="nav-section">
        <div class="nav-label">Server (cPanel)</div>
        <a href="<?= BASEURL ?>/noc/system"   class="nav-item"><span class="icon">📈</span> System Info</a>
        <a href="<?= BASEURL ?>/noc/database" class="nav-item"><span class="icon">🗄️</span> DB Manager</a>
        <a href="<?= BASEURL ?>/noc/cron"     class="nav-item"><span class="icon">⏱️</span> Cron Jobs</a>
        <a href="<?= BASEURL ?>/noc/backup"   class="nav-item"><span class="icon">💾</span> Backup Wizard</a>
        <a href="<?= BASEURL ?>/noc/phpini"   class="nav-item"><span class="icon">⚙️</span> PHP Config</a>
    </nav>
    <nav class="nav-section">
        <div class="nav-label">Developer</div>
        <a href="<?= BASEURL ?>/noc/filemanager" class="nav-item"><span class="icon">📁</span> File Manager</a>
        <a href="<?= BASEURL ?>/noc/terminal" class="nav-item"><span class="icon">💻</span> Web Console</a>
    </nav>
    <div class="sidebar-footer">
        <a href="<?= BASEURL ?>/admin" target="_blank"><span>⚙️</span> Admin Panel</a>
        <a href="<?= BASEURL ?>/noc/logout"><span>🚪</span> Logout NOC</a>
    </div>
</aside>

<main class="main">
    <div class="topbar">
        <div>
            <h1>NOC Settings</h1>
            <p>Pengaturan kebijakan deteksi ancaman siber dan daftar IP aman</p>
        </div>
    </div>

    <div class="content">
        <?php if (isset($_SESSION['noc_flash'])): ?>
            <div class="alert-success">
                ✅ <?= htmlspecialchars($_SESSION['noc_flash']) ?>
                <?php unset($_SESSION['noc_flash']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASEURL ?>/noc/update_settings">
            <!-- 1. Security Policy -->
            <div class="card">
                <div class="card-title">🛡️ Kebijakan Perlindungan Ancaman</div>
                
                <div class="form-group">
                    <label for="block_sqli">SQL Injection Protection</label>
                    <select id="block_sqli" name="block_sqli">
                        <option value="block" <?= ($data['settings']['block_sqli'] ?? 'block') === 'block' ? 'selected' : '' ?>>🚫 Block IP (Otomatis memblokir IP penyerang)</option>
                        <option value="log"   <?= ($data['settings']['block_sqli'] ?? 'block') === 'log' ? 'selected' : '' ?>>📋 Log Only (Hanya catat ke log ancaman)</option>
                        <option value="off"   <?= ($data['settings']['block_sqli'] ?? 'block') === 'off' ? 'selected' : '' ?>>⚪ Nonaktifkan Proteksi</option>
                    </select>
                    <p class="help-text">Mendeteksi percobaan SQLi seperti regexp `UNION SELECT`, `' OR '1'='1`, dan bypass query database.</p>
                </div>

                <div class="form-group">
                    <label for="block_xss">XSS Protection</label>
                    <select id="block_xss" name="block_xss">
                        <option value="block" <?= ($data['settings']['block_xss'] ?? 'block') === 'block' ? 'selected' : '' ?>>🚫 Block IP (Otomatis memblokir IP penyerang)</option>
                        <option value="log"   <?= ($data['settings']['block_xss'] ?? 'block') === 'log' ? 'selected' : '' ?>>📋 Log Only (Hanya catat ke log ancaman)</option>
                        <option value="off"   <?= ($data['settings']['block_xss'] ?? 'block') === 'off' ? 'selected' : '' ?>>⚪ Nonaktifkan Proteksi</option>
                    </select>
                    <p class="help-text">Mendeteksi script jahat di URL/form input seperti tag `<script>` atau attribute `onerror`/`onload`.</p>
                </div>

                <div class="form-group">
                    <label for="block_traversal">Path Traversal Protection</label>
                    <select id="block_traversal" name="block_traversal">
                        <option value="block" <?= ($data['settings']['block_traversal'] ?? 'block') === 'block' ? 'selected' : '' ?>>🚫 Block IP (Otomatis memblokir IP penyerang)</option>
                        <option value="log"   <?= ($data['settings']['block_traversal'] ?? 'block') === 'log' ? 'selected' : '' ?>>📋 Log Only (Hanya catat ke log ancaman)</option>
                        <option value="off"   <?= ($data['settings']['block_traversal'] ?? 'block') === 'off' ? 'selected' : '' ?>>⚪ Nonaktifkan Proteksi</option>
                    </select>
                    <p class="help-text">Mendeteksi percobaan akses direktori menggunakan payload `../` atau file sistem Linux `/etc/passwd`.</p>
                </div>

                <div class="form-group">
                    <label for="block_path_scan">Path Scan / Sensitive Files Protection</label>
                    <select id="block_path_scan" name="block_path_scan">
                        <option value="block" <?= ($data['settings']['block_path_scan'] ?? 'block') === 'block' ? 'selected' : '' ?>>🚫 Block IP (Otomatis memblokir IP penyerang)</option>
                        <option value="log"   <?= ($data['settings']['block_path_scan'] ?? 'block') === 'log' ? 'selected' : '' ?>>📋 Log Only (Hanya catat ke log ancaman)</option>
                        <option value="off"   <?= ($data['settings']['block_path_scan'] ?? 'block') === 'off' ? 'selected' : '' ?>>⚪ Nonaktifkan Proteksi</option>
                    </select>
                    <p class="help-text">Mendeteksi robot scan/hacker mencari file konfigurasi sensitif seperti `.env`, `.git/config`, `wp-admin`, dsb.</p>
                </div>
            </div>

            <!-- 2. Security Headers -->
            <div class="card">
                <div class="card-title">🔒 HTTP Security Headers</div>
                <div class="form-group">
                    <label for="enable_security_headers">Enforce Security Headers</label>
                    <select id="enable_security_headers" name="enable_security_headers">
                        <option value="1" <?= ($data['settings']['enable_security_headers'] ?? '1') === '1' ? 'selected' : '' ?>>✅ Aktifkan (Kirim X-Frame-Options, CSP, HSTS)</option>
                        <option value="0" <?= ($data['settings']['enable_security_headers'] ?? '1') === '0' ? 'selected' : '' ?>>❌ Matikan</option>
                    </select>
                    <p class="help-text">Mengirim header keamanan HTTP premium pada setiap request untuk mencegah pembajakan Frame (Clickjacking), mitigasi XSS (CSP), dan memaksa HTTPS (HSTS).</p>
                </div>
            </div>

            <!-- 3. Whitelist IPs -->
            <div class="card">
                <div class="card-title">⚪ IP Whitelist (Aman)</div>
                <div class="form-group">
                    <label for="whitelisted_ips">IP yang Dikecualikan dari Blokir</label>
                    <input type="text" id="whitelisted_ips" name="whitelisted_ips" value="<?= htmlspecialchars($data['settings']['whitelisted_ips'] ?? '127.0.0.1,::1') ?>" placeholder="Contoh: 127.0.0.1, 8.8.8.8">
                    <p class="help-text">Pisahkan dengan koma (,) jika ada lebih dari satu IP. IP dalam daftar ini tidak akan pernah diblokir meskipun melakukan request mencurigakan (sangat berguna untuk IP developer lokal/VPS Anda).</p>
                </div>
            </div>

            <button type="submit" class="btn-primary">💾 Simpan Konfigurasi</button>
        </form>
    </div>
</main>
</body>
</html>
