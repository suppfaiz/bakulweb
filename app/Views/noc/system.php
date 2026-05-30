<?php // NOC — System Monitor View ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            --green-600: #22c55e;
            --red-500: #f87171;
            --red-50: rgba(248, 113, 113, 0.08);
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
        .content { padding: 24px 28px; max-width: 1200px; display: flex; flex-direction: column; gap: 20px; }

        /* ─── Stat Cards ─── */
        .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .card-stat { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 20px; }
        .stat-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .stat-title { font-size: 12.5px; font-weight: 600; color: var(--text-secondary); }
        .stat-val { font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
        
        .progress-bar-bg { width: 100%; height: 6px; background: var(--border-light); border-radius: 3px; overflow: hidden; }
        .progress-bar-fill { height: 100%; background: var(--blue-500); border-radius: 3px; }
        .progress-bar-fill.warning { background: #fbbf24; }
        .progress-bar-fill.danger { background: var(--red-500); }

        .two-col { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; }
        .panel { background: var(--white); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border-light); }
        .panel-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }

        /* Services List */
        .service-item { display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; border-bottom: 1px solid var(--border-light); }
        .service-item:last-child { border-bottom: none; }
        .service-name { font-size: 13.5px; font-weight: 500; }
        .service-port { font-size: 11px; color: var(--text-muted); font-family: 'JetBrains Mono', monospace; }
        .status-badge { font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; }
        .status-badge.online { background: var(--green-50); color: var(--green-500); }
        .status-badge.offline { background: var(--red-50); color: var(--red-500); }

        /* Tables */
        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--bg); }
        th { text-align: left; padding: 10px 16px; font-size: 11px; font-weight: 700; color: var(--text-muted); letter-spacing: .4px; text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 10px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid var(--border-light); }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255, 255, 255, 0.02); }
        .mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon">🛡️</div>
            <div class="brand-text"><h2>BAKUL NOC</h2><p>Security Center</p></div>
        </div>
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
        <a href="<?= BASEURL ?>/noc/settings" class="nav-item"><span class="icon">⚙️</span> Settings</a>
    </nav>
    <nav class="nav-section">
        <div class="nav-label">Server (cPanel)</div>
        <a href="<?= BASEURL ?>/noc/system"   class="nav-item active"><span class="icon">📈</span> System Info</a>
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
            <h1>System Monitor</h1>
            <p>Utilitas resource server, status service, dan pemantauan proses sistem</p>
        </div>
    </div>

    <div class="content">
        <!-- 3-Column Usage Stats -->
        <div class="grid-stats">
            <!-- CPU -->
            <div class="card-stat">
                <div class="stat-header">
                    <span class="stat-title">💻 Load Average (CPU)</span>
                </div>
                <div class="stat-val"><?= $data['cpu'] ?></div>
                <p style="font-size:12px;color:var(--text-muted)">1 min, 5 min, 15 min avg load</p>
            </div>

            <!-- Memory -->
            <?php
            $ramPercent = $data['ram']['percent'] ?? 0;
            $ramColor = '';
            if ($ramPercent > 85) $ramColor = 'danger';
            elseif ($ramPercent > 60) $ramColor = 'warning';
            ?>
            <div class="card-stat">
                <div class="stat-header">
                    <span class="stat-title">🧠 Memory Usage (RAM)</span>
                    <span class="stat-title" style="font-weight:700"><?= $ramPercent ?>%</span>
                </div>
                <div class="stat-val"><?= $data['ram']['used'] ?> / <?= $data['ram']['total'] ?></div>
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill <?= $ramColor ?>" style="width: <?= $ramPercent ?>%"></div>
                </div>
            </div>

            <!-- Disk -->
            <?php
            $diskPercent = $data['disk']['percent'] ?? 0;
            $diskColor = '';
            if ($diskPercent > 90) $diskColor = 'danger';
            elseif ($diskPercent > 75) $diskColor = 'warning';
            ?>
            <div class="card-stat">
                <div class="stat-header">
                    <span class="stat-title">💾 Disk Storage Usage</span>
                    <span class="stat-title" style="font-weight:700"><?= $diskPercent ?>%</span>
                </div>
                <div class="stat-val"><?= $data['disk']['used'] ?> / <?= $data['disk']['total'] ?></div>
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill <?= $diskColor ?>" style="width: <?= $diskPercent ?>%"></div>
                </div>
            </div>
        </div>

        <div class="two-col">
            <!-- Services Status -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">⚙️ Status Service Utama</div>
                </div>
                <div class="services-list">
                    <?php foreach ($data['services'] as $key => $s): ?>
                    <div class="service-item">
                        <div>
                            <div class="service-name"><?= htmlspecialchars($s['name']) ?></div>
                            <div class="service-port">Port: <?= $s['port'] ?></div>
                        </div>
                        <span class="status-badge <?= $s['status'] ?>"><?= $s['status'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Process Monitor -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">📋 20 Proses Sistem Teratas</div>
                </div>
                <div style="overflow-x:auto">
                    <table>
                        <thead>
                            <tr>
                                <th>PID</th>
                                <th>User</th>
                                <th>CPU</th>
                                <th>Memory</th>
                                <th>Command</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['processes'] as $p): ?>
                            <tr>
                                <td class="mono"><?= htmlspecialchars($p['pid']) ?></td>
                                <td><?= htmlspecialchars($p['user']) ?></td>
                                <td class="mono"><?= htmlspecialchars($p['cpu']) ?></td>
                                <td class="mono"><?= htmlspecialchars($p['mem']) ?></td>
                                <td class="mono" style="font-weight:600;color:var(--text-primary)">
                                    <?= htmlspecialchars($p['command']) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
