<?php // NOC — Security Self-Audit View ?>
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
            --green-700: #22c55e;
            --red-500: #f87171;
            --red-50: rgba(248, 113, 113, 0.08);
            --red-700: #ef4444;
            --yellow-500: #fbbf24;
            --yellow-50: rgba(251, 191, 36, 0.08);
            --yellow-700: #f57c00;
            --sidebar-w: 240px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-primary); display: flex; min-height: 100vh; }

        /* ─── Sidebar (shared) ─── */
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
        .content { padding: 24px 28px; max-width: 1100px; }

        /* ─── Score Banner ─── */
        .score-banner {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px 28px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 28px;
        }
        .score-circle {
            width: 90px; height: 90px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-direction: column;
            font-size: 28px; font-weight: 800;
            flex-shrink: 0;
            border: 4px solid;
        }
        .score-circle .sub { font-size: 11px; font-weight: 500; }
        .score-info h2 { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
        .score-info p { font-size: 13.5px; color: var(--text-secondary); line-height: 1.5; }
        .score-pills { display: flex; gap: 8px; margin-top: 12px; flex-wrap: wrap; }
        .pill { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1px solid transparent; }
        .pill.pass   { background: var(--green-50); color: var(--green-500); border-color: rgba(74, 222, 128, 0.15); }
        .pill.fail   { background: var(--red-50);   color: var(--red-500); border-color: rgba(248, 113, 113, 0.15); }
        .pill.warn   { background: var(--yellow-50); color: var(--yellow-500); border-color: rgba(251, 191, 36, 0.15); }

        /* ─── Sections ─── */
        .section { margin-bottom: 24px; }
        .section-title {
            font-size: 14px; font-weight: 700; color: var(--text-primary);
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 12px;
        }

        .check-list { display: flex; flex-direction: column; gap: 8px; }

        .check-item {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: all .15s;
        }
        .check-item:hover { border-color: var(--blue-500); }
        .check-item.pass { border-left: 4px solid var(--green-500); }
        .check-item.fail { border-left: 4px solid var(--red-500); }
        .check-item.warn { border-left: 4px solid var(--yellow-500); }
        .check-item.info { border-left: 4px solid var(--blue-500); }

        .check-status {
            width: 28px; height: 28px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; flex-shrink: 0;
        }
        .check-status.pass { background: var(--green-50); }
        .check-status.fail { background: var(--red-50); }
        .check-status.warn { background: var(--yellow-50); }
        .check-status.info { background: var(--blue-50); }

        .check-body { flex: 1; }
        .check-key   { font-size: 13.5px; font-weight: 600; color: var(--text-primary); font-family: 'JetBrains Mono', monospace; }
        .check-desc  { font-size: 12.5px; color: var(--text-muted); margin-top: 2px; }
        .check-note  { font-size: 12px; color: var(--text-secondary); margin-top: 5px; background: var(--bg); padding: 5px 10px; border-radius: 6px; border: 1px solid var(--border-light); }

        .check-badge {
            font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 5px;
            letter-spacing: .4px; white-space: nowrap; align-self: flex-start;
        }
        .check-badge.pass { background: var(--green-50); color: var(--green-500); }
        .check-badge.fail { background: var(--red-50);   color: var(--red-500); }
        .check-badge.warn { background: var(--yellow-50); color: var(--yellow-500); }
        .check-badge.info { background: var(--blue-50);  color: var(--blue-500); }

        .btn-rerun {
            padding: 8px 18px; border-radius: 9px; border: 1.5px solid var(--border);
            background: var(--white); color: var(--blue-500); font-size: 13.5px;
            font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer;
            transition: all .15s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-rerun:hover { background: var(--blue-50); border-color: var(--blue-500); }

        .base-info {
            font-size: 12px; color: var(--text-muted);
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 8px; padding: 8px 14px;
            font-family: 'JetBrains Mono', monospace;
            margin-bottom: 20px;
        }
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
        <a href="<?= BASEURL ?>/noc/audit" class="nav-item active"><span class="icon">🔍</span> Self-Audit</a>
        <a href="<?= BASEURL ?>/noc/siem" class="nav-item"><span class="icon">🛡️</span> SIEM Console</a>
        <a href="<?= BASEURL ?>/noc/settings" class="nav-item"><span class="icon">⚙️</span> Settings</a>
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
            <h1>Security Self-Audit</h1>
            <p>Pemeriksaan otomatis kerentanan keamanan aplikasi BAKUL</p>
        </div>
        <a href="<?= BASEURL ?>/noc/audit" class="btn-rerun">🔄 Jalankan Ulang</a>
    </div>

    <div class="content">

        <?php
        // Hitung skor keseluruhan
        $totalChecks = 0; $passCount = 0; $failCount = 0; $warnCount = 0;
        foreach (($data['results']['headers'] ?? []) as $r) {
            $totalChecks++;
            if ($r['status'] === 'pass') $passCount++;
            else $failCount++;
        }
        foreach (($data['results']['files'] ?? []) as $r) {
            $totalChecks++;
            if ($r['status'] === 'pass') $passCount++;
            else $failCount++;
        }
        foreach (($data['results']['auth'] ?? []) as $r) {
            $totalChecks++;
            if ($r['status'] === 'pass') $passCount++;
            elseif ($r['status'] === 'fail') $failCount++;
            else $warnCount++;
        }
        foreach (($data['results']['php'] ?? []) as $r) {
            $totalChecks++;
            if ($r['safe']) $passCount++;
            else $warnCount++;
        }
        $score = $totalChecks > 0 ? round(($passCount / $totalChecks) * 100) : 0;
        $scoreColor = $score >= 80 ? '#4ade80' : ($score >= 55 ? '#fbbf24' : '#f87171');
        $scoreLabel = $score >= 80 ? 'AMAN' : ($score >= 55 ? 'PERLU PERHATIAN' : 'PERLU TINDAKAN');
        ?>

        <!-- Score Banner -->
        <div class="score-banner">
            <div class="score-circle" style="color:<?= $scoreColor ?>;border-color:<?= $scoreColor ?>">
                <span><?= $score ?></span>
                <span class="sub">/ 100</span>
            </div>
            <div class="score-info">
                <h2 style="color:<?= $scoreColor ?>">Security Score: <?= $scoreLabel ?></h2>
                <p>Audit ini menguji <?= $totalChecks ?> titik pemeriksaan keamanan pada aplikasi BAKUL — termasuk
                   response header, file sensitif, autentikasi route, dan konfigurasi PHP.</p>
                <div class="score-pills">
                    <span class="pill pass">✅ <?= $passCount ?> Lulus</span>
                    <span class="pill fail">❌ <?= $failCount ?> Gagal</span>
                    <?php if ($warnCount): ?>
                    <span class="pill warn">⚠️ <?= $warnCount ?> Perhatian</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="base-info">🌐 Target: <?= htmlspecialchars($data['base']) ?> &nbsp;|&nbsp; 🕐 <?= date('d M Y H:i:s') ?></div>

        <!-- 1. Security Headers -->
        <div class="section">
            <div class="section-title">🔒 HTTP Security Headers</div>
            <?php 
            $hasFailedHeaders = false;
            foreach ($data['results']['headers'] ?? [] as $r) {
                if ($r['status'] !== 'pass') {
                    $hasFailedHeaders = true;
                    break;
                }
            }
            if ($hasFailedHeaders): ?>
            <div style="background:var(--blue-50); border:1px solid var(--border); border-radius:10px; padding:12px 18px; margin-bottom:14px; display:flex; align-items:center; justify-content:space-between;">
                <div style="font-size:13px; color:var(--blue-500);">
                    <strong>💡 Rekomendasi NOC:</strong> Terapkan header keamanan otomatis melalui middleware NocGuard.
                </div>
                <a href="<?= BASEURL ?>/noc/audit_fix?type=headers" style="padding:6px 14px; background:linear-gradient(135deg, var(--blue-500), var(--blue-600)); color:#fff; text-decoration:none; border-radius:6px; font-size:12px; font-weight:600; transition: all .15s; box-shadow: 0 4px 12px rgba(56, 189, 248, 0.2);">🛠️ Auto-Fix Headers</a>
            </div>
            <?php endif; ?>
            <div class="check-list">
                <?php foreach ($data['results']['headers'] ?? [] as $r):
                    $cls = $r['status'] === 'pass' ? 'pass' : 'fail';
                    $ico = $r['status'] === 'pass' ? '✅' : '❌';
                ?>
                <div class="check-item <?= $cls ?>">
                    <div class="check-status <?= $cls ?>"><?= $ico ?></div>
                    <div class="check-body">
                        <div class="check-key"><?= htmlspecialchars($r['header']) ?></div>
                        <div class="check-desc"><?= htmlspecialchars($r['desc']) ?></div>
                        <div class="check-note"><?= htmlspecialchars($r['note']) ?></div>
                    </div>
                    <span class="check-badge <?= $cls ?>"><?= strtoupper($r['status']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 2. Sensitive File Exposure -->
        <div class="section">
            <div class="section-title">📁 Paparan File Sensitif</div>
            <?php 
            $hasFailedFiles = false;
            foreach ($data['results']['files'] ?? [] as $r) {
                if ($r['status'] !== 'pass') {
                    $hasFailedFiles = true;
                    break;
                }
            }
            if ($hasFailedFiles): ?>
            <div style="background:var(--yellow-50); border:1px solid rgba(251, 191, 36, 0.2); border-radius:10px; padding:12px 18px; margin-bottom:14px; display:flex; align-items:center; justify-content:space-between;">
                <div style="font-size:13px; color:var(--yellow-500);">
                    <strong>⚠️ Kerentanan Terdeteksi:</strong> Beberapa file penting terekspos ke publik. Aktifkan firewall pencarian path.
                </div>
                <a href="<?= BASEURL ?>/noc/audit_fix?type=exposure" style="padding:6px 14px; background:linear-gradient(135deg, #fbbf24, #f57c00); color:#fff; text-decoration:none; border-radius:6px; font-size:12px; font-weight:600; transition: all .15s; box-shadow: 0 4px 12px rgba(251, 191, 36, 0.2);">🛠️ Auto-Fix Exposure</a>
            </div>
            <?php endif; ?>
            <div class="check-list">
                <?php foreach ($data['results']['files'] ?? [] as $r):
                    $cls = $r['status'] === 'pass' ? 'pass' : 'fail';
                    $ico = $r['status'] === 'pass' ? '✅' : '🚨';
                ?>
                <div class="check-item <?= $cls ?>">
                    <div class="check-status <?= $cls ?>"><?= $ico ?></div>
                    <div class="check-body">
                        <div class="check-key"><?= htmlspecialchars($r['path']) ?></div>
                        <div class="check-desc"><?= htmlspecialchars($r['desc']) ?></div>
                        <div class="check-note"><?= htmlspecialchars($r['note']) ?></div>
                    </div>
                    <span class="check-badge <?= $cls ?>">HTTP <?= $r['code'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 3. Admin Auth Routes -->
        <div class="section">
            <div class="section-title">🔑 Proteksi Route Admin</div>
            <div class="check-list">
                <?php foreach ($data['results']['auth'] ?? [] as $r):
                    $cls = $r['status'] === 'pass' ? 'pass' : ($r['status'] === 'fail' ? 'fail' : 'info');
                    $ico = $r['status'] === 'pass' ? '✅' : ($r['status'] === 'fail' ? '❌' : 'ℹ️');
                ?>
                <div class="check-item <?= $cls ?>">
                    <div class="check-status <?= $cls ?>"><?= $ico ?></div>
                    <div class="check-body">
                        <div class="check-key"><?= htmlspecialchars($r['route']) ?></div>
                        <div class="check-desc"><?= htmlspecialchars($r['desc']) ?></div>
                        <div class="check-note"><?= htmlspecialchars($r['note']) ?></div>
                    </div>
                    <span class="check-badge <?= $cls ?>">HTTP <?= $r['code'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 4. PHP Config -->
        <div class="section">
            <div class="section-title">⚙️ Konfigurasi PHP</div>
            <div class="check-list">
                <?php foreach ($data['results']['php'] ?? [] as $r):
                    $cls  = $r['safe'] ? 'pass' : 'warn';
                    $ico  = $r['safe'] ? '✅' : '⚠️';
                    $val  = $r['value'] === '' ? '(kosong/default)' : $r['value'];
                ?>
                <div class="check-item <?= $cls ?>">
                    <div class="check-status <?= $cls ?>"><?= $ico ?></div>
                    <div class="check-body">
                        <div class="check-key"><?= htmlspecialchars($r['key']) ?></div>
                        <div class="check-desc"><?= htmlspecialchars($r['note']) ?></div>
                        <div class="check-note">
                            Nilai saat ini: <strong style="font-family:'JetBrains Mono',monospace"><?= htmlspecialchars($val) ?></strong>
                        </div>
                    </div>
                    <span class="check-badge <?= $cls ?>"><?= $r['safe'] ? 'OK' : 'PERHATIAN' ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</main>
</body>
</html>
