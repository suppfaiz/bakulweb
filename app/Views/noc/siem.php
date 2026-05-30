<?php // NOC — SIEM View ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            --orange-500: #fbbf24;
            --orange-50: rgba(251, 191, 36, 0.08);
            --orange-700: #f57c00;
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
        .content { padding: 24px 28px; max-width: 1300px; }

        /* ─── Status Banner ─── */
        .siem-banner { border-radius: 14px; padding: 24px; margin-bottom: 24px; display: flex; align-items: center; gap: 20px; border: 1px solid; }
        .siem-banner.safe { background: var(--green-50); border-color: rgba(74, 222, 128, 0.2); color: var(--green-500); }
        .siem-banner.warn { background: var(--orange-50); border-color: rgba(251, 191, 36, 0.2); color: var(--orange-500); }
        .siem-banner.crit { background: var(--red-50); border-color: rgba(248, 113, 113, 0.2); color: var(--red-500); }
        
        .banner-icon { font-size: 40px; }
        .banner-info h2 { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
        .banner-info p { font-size: 13px; opacity: .85; line-height: 1.5; }

        /* ─── Stats Grid ─── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; padding: 18px; }
        .stat-val { font-size: 26px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; }
        .stat-lbl { font-size: 12px; color: var(--text-secondary); font-weight: 500; }

        /* ─── Table Panel ─── */
        .panel { background: var(--white); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; }
        .panel-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }
        
        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--bg); }
        th { text-align: left; padding: 11px 16px; font-size: 11px; font-weight: 700; color: var(--text-muted); letter-spacing: .4px; text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid var(--border-light); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255, 255, 255, 0.02); }

        .mono { font-family: 'JetBrains Mono', monospace; font-size: 12px; }
        .sev-badge { font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 4px; text-transform: uppercase; border: 1px solid; }
        .sev-badge.critical { background: rgba(239, 68, 68, 0.1); color: #f87171; border-color: rgba(239, 68, 68, 0.3); }
        .sev-badge.high     { background: rgba(251, 191, 36, 0.1); color: #fbbf24; border-color: rgba(251, 191, 36, 0.3); }
        .sev-badge.medium   { background: rgba(251, 191, 36, 0.05); color: #facc15; border-color: rgba(251, 191, 36, 0.2); }
        .sev-badge.low      { background: rgba(74, 222, 128, 0.1); color: #4ade80; border-color: rgba(74, 222, 128, 0.3); }
        
        .status-badge { font-size: 10.5px; font-weight: 600; padding: 3px 8px; border-radius: 20px; }
        .status-badge.open     { background: rgba(56, 189, 248, 0.15); color: #38bdf8; }
        .status-badge.resolved { background: rgba(74, 222, 128, 0.15); color: #4ade80; }
        .status-badge.ignored  { background: rgba(148, 163, 184, 0.15); color: #94a3b8; }

        .act-btn { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; border: 1px solid var(--border); background: var(--white); cursor: pointer; transition: all .15s; font-family: 'Inter', sans-serif; }
        .btn-resolve { color: #4ade80; border-color: rgba(74, 222, 128, 0.3); background: rgba(74, 222, 128, 0.1); }
        .btn-resolve:hover { background: rgba(74, 222, 128, 0.2); }
        .btn-ignore { color: #94a3b8; border-color: var(--border); background: var(--white); }
        .btn-ignore:hover { background: var(--surface-2); color: var(--text-primary); }
        .btn-block { color: #f87171; border-color: rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.1); }
        .btn-block:hover { background: rgba(239, 68, 68, 0.2); }
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
        <a href="<?= BASEURL ?>/noc/siem" class="nav-item active"><span class="icon">🛡️</span> SIEM Console</a>
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
            <h1>SIEM Security Console</h1>
            <p>Pusat agregasi log keamanan, korelasi ancaman, dan respon insiden siber</p>
        </div>
    </div>

    <div class="content">
        <?php
        // Calculate dynamic values
        $openAlerts = 0; $critCount = 0; $highCount = 0;
        $uniqueIps = [];
        foreach ($data['alerts'] as $a) {
            if ($a['status'] === 'open') {
                $openAlerts++;
                $uniqueIps[$a['ip']] = true;
                if ($a['severity'] === 'critical') $critCount++;
                if ($a['severity'] === 'high') $highCount++;
            }
        }
        $ipCount = count($uniqueIps);
        
        $statusClass = 'safe';
        $statusTitle = 'Sistem Terpantau Aman';
        $statusDesc = 'Tidak ada insiden keamanan kritis atau tinggi yang aktif saat ini. Semua proteksi berjalan optimal.';
        $statusIcon = '✅';
        
        if ($openAlerts > 0) {
            if ($critCount > 0) {
                $statusClass = 'crit';
                $statusTitle = 'CRITICAL STATUS: SERANGAN DITENTUKAN';
                $statusDesc = 'Terdeteksi insiden keamanan dengan tingkat bahaya kritis (Critical). Diperlukan tindakan mitigasi segera pada IP penyerang.';
                $statusIcon = '🚨';
            } else {
                $statusClass = 'warn';
                $statusTitle = 'PERINGATAN KEAMANAN AKTIF';
                $statusDesc = 'Terdapat beberapa anomali/serangan sedang hingga tinggi yang terdeteksi di log. Monitor aktivitas IP penyerang.';
                $statusIcon = '⚠️';
            }
        }
        ?>

        <!-- SIEM Banner -->
        <div class="siem-banner <?= $statusClass ?>">
            <div class="banner-icon"><?= $statusIcon ?></div>
            <div class="banner-info">
                <h2><?= $statusTitle ?></h2>
                <p><?= $statusDesc ?></p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-val"><?= $openAlerts ?></div>
                <div class="stat-lbl">Insiden Terbuka (Open)</div>
            </div>
            <div class="stat-card">
                <div class="stat-val" style="color:var(--red-500)"><?= $critCount ?></div>
                <div class="stat-lbl">Insiden Kritis (Critical)</div>
            </div>
            <div class="stat-card">
                <div class="stat-val"><?= $ipCount ?></div>
                <div class="stat-lbl">Sumber IP Penyerang Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-val">4</div>
                <div class="stat-lbl">Korelasi Rules Aktif</div>
            </div>
        </div>

        <!-- Incidents Table -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">🛡️ Log Korelasi Kejadian Keamanan (SIEM Alerts)</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Waktu Kejadian</th>
                        <th>IP Address</th>
                        <th>Kategori Insiden</th>
                        <th>Tingkat</th>
                        <th>Deskripsi Analisis Korelasi</th>
                        <th>Count</th>
                        <th>Status</th>
                        <th>Tindakan Mitigasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['alerts'])): ?>
                    <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">Tidak ada alert SIEM yang terpicu.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($data['alerts'] as $a): ?>
                    <tr>
                        <td class="mono"><?= date('d M H:i:s', strtotime($a['created_at'])) ?></td>
                        <td class="mono" style="font-weight:600;color:var(--text-primary)"><?= htmlspecialchars($a['ip']) ?></td>
                        <td style="font-weight:700"><?= htmlspecialchars($a['rule_name']) ?></td>
                        <td><span class="sev-badge <?= $a['severity'] ?>"><?= $a['severity'] ?></span></td>
                        <td style="font-size:12.5px;max-width:320px;"><?= htmlspecialchars($a['description']) ?></td>
                        <td style="font-weight:700;color:var(--text-primary)"><?= $a['event_count'] ?>×</td>
                        <td><span class="status-badge <?= $a['status'] ?>"><?= strtoupper($a['status']) ?></span></td>
                        <td>
                            <div style="display:flex; gap:6px; align-items:center;">
                                <?php if ($a['status'] === 'open'): ?>
                                    <form method="POST" action="<?= BASEURL ?>/noc/toggle_siem_alert" style="display:inline">
                                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                        <input type="hidden" name="action" value="resolve">
                                        <button type="submit" class="act-btn btn-resolve" title="Tandai selesai">Resolve</button>
                                    </form>
                                    
                                    <form method="POST" action="<?= BASEURL ?>/noc/toggle_siem_alert" style="display:inline">
                                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                        <input type="hidden" name="action" value="ignore">
                                        <button type="submit" class="act-btn btn-ignore" title="Abaikan / False Positive">Ignore</button>
                                    </form>
                                    
                                    <!-- Direct Block IP Action -->
                                    <form method="POST" action="<?= BASEURL ?>/noc/toggle_block" style="display:inline" onsubmit="return confirm('Blokir IP <?= htmlspecialchars($a['ip']) ?>?')">
                                        <input type="hidden" name="action" value="block">
                                        <input type="hidden" name="ip" value="<?= htmlspecialchars($a['ip']) ?>">
                                        <input type="hidden" name="reason" value="SIEM Mitigation: <?= htmlspecialchars($a['rule_name']) ?>">
                                        <button type="submit" class="act-btn btn-block" title="Blokir IP Sekarang">🚫 Block IP</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color:var(--text-muted);font-size:12px">—</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>
