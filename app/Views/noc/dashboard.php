<?php
// NOC Dashboard View — White/Light Premium Theme
$hourlyLabels  = array_column($data['hourly_traffic'] ?? [], 'hour_label');
$hourlyReqs    = array_column($data['hourly_traffic'] ?? [], 'requests');
$hourlyThreats = array_column($data['hourly_traffic'] ?? [], 'threats');
$threatTypeLabels  = array_column($data['threat_types'] ?? [], 'threat_type');
$threatTypeCounts  = array_column($data['threat_types'] ?? [], 'cnt');

function nocBadge($level) {
    $map = [
        'critical' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'color' => '#f87171', 'border' => 'rgba(239, 68, 68, 0.3)', 'label' => 'CRITICAL'],
        'high'     => ['bg' => 'rgba(249, 115, 22, 0.1)', 'color' => '#fb923c', 'border' => 'rgba(249, 115, 22, 0.3)', 'label' => 'HIGH'],
        'medium'   => ['bg' => 'rgba(234, 179, 8, 0.1)', 'color' => '#facc15', 'border' => 'rgba(234, 179, 8, 0.3)', 'label' => 'MEDIUM'],
        'low'      => ['bg' => 'rgba(34, 197, 94, 0.1)', 'color' => '#4ade80', 'border' => 'rgba(34, 197, 94, 0.3)', 'label' => 'LOW'],
        'none'     => ['bg' => 'rgba(100, 116, 139, 0.1)', 'color' => '#cbd5e1', 'border' => 'rgba(100, 116, 139, 0.3)', 'label' => 'CLEAN'],
    ];
    $m = $map[$level] ?? $map['none'];
    return "<span style=\"background:{$m['bg']};color:{$m['color']};border:1px solid {$m['border']};border-radius:4px;padding:2px 7px;font-size:10.5px;font-weight:700;letter-spacing:.5px\">{$m['label']}</span>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($data['title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
            --blue-100: rgba(56, 189, 248, 0.25);
            --red-500: #f87171;
            --red-50: rgba(248, 113, 113, 0.08);
            --orange-500: #fbbf24;
            --orange-50: rgba(251, 191, 36, 0.08);
            --green-500: #4ade80;
            --green-50: rgba(74, 222, 128, 0.08);
            --indigo-500: #818cf8;
            --sidebar-w: 240px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
        }

        /* ─── Sidebar ─── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--white);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border-light);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .brand-text h2 {
            font-size: 14px; font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -.2px;
        }

        .brand-text p {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .live-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
            font-size: 10.5px;
            font-weight: 600;
            color: var(--green-500);
            letter-spacing: .3px;
        }

        .live-dot {
            width: 6px; height: 6px;
            background: var(--green-500);
            border-radius: 50%;
            animation: livepulse 1.5s infinite;
        }

        @keyframes livepulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .6; transform: scale(.85); }
        }

        .nav-section {
            padding: 16px 12px 8px;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: .8px;
            text-transform: uppercase;
            padding: 0 8px;
            margin-bottom: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all .15s;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: var(--bg);
            color: var(--text-primary);
        }

        .nav-item.active {
            background: var(--blue-50);
            color: var(--blue-600);
            font-weight: 600;
        }

        .nav-item .icon { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 12px;
            border-top: 1px solid var(--border-light);
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            text-decoration: none;
            padding: 8px 10px;
            border-radius: 8px;
            transition: all .15s;
        }

        .sidebar-footer a:hover {
            background: var(--red-50);
            color: var(--red-500);
        }

        /* ─── Main ─── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-width: 0;
        }

        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-title h1 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -.2px;
        }

        .topbar-title p {
            font-size: 12.5px;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .time-display {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: var(--text-secondary);
            background: var(--bg);
            border: 1px solid var(--border);
            padding: 6px 12px;
            border-radius: 8px;
        }

        .content {
            padding: 28px;
            max-width: 1400px;
        }

        /* ─── Stat Cards ─── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            transition: all .2s;
        }

        .stat-card:hover {
            border-color: var(--blue-500);
            box-shadow: 0 4px 16px rgba(59,130,246,.1);
            transform: translateY(-1px);
        }

        .stat-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }

        .stat-trend {
            font-size: 11.5px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .stat-trend.up   { background: #f0fdf4; color: #16a34a; }
        .stat-trend.down { background: #fef2f2; color: #dc2626; }
        .stat-trend.neu  { background: #f8fafc; color: #64748b; }

        .stat-value {
            font-size: 30px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -1px;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12.5px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ─── Chart cards ─── */
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .panel {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .panel-body { padding: 20px; }

        /* ─── Threat Feed ─── */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .feed-list {
            max-height: 340px;
            overflow-y: auto;
        }

        .feed-list::-webkit-scrollbar { width: 4px; }
        .feed-list::-webkit-scrollbar-track { background: transparent; }
        .feed-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .feed-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-light);
            font-size: 12.5px;
        }

        .feed-item:last-child { border-bottom: none; }

        .feed-ip {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px;
            color: var(--text-secondary);
            white-space: nowrap;
            min-width: 110px;
        }

        .feed-info { flex: 1; min-width: 0; }

        .feed-type {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 12px;
        }

        .feed-detail {
            color: var(--text-muted);
            font-size: 11.5px;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .feed-time {
            font-size: 11px;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* ─── Top Attackers ─── */
        .attacker-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 0;
            border-bottom: 1px solid var(--border-light);
            font-size: 13px;
        }

        .attacker-row:last-child { border-bottom: none; }

        .attacker-ip {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            color: var(--text-primary);
            font-weight: 500;
            flex: 1;
        }

        .attacker-bar-wrap {
            flex: 2;
            height: 6px;
            background: var(--border-light);
            border-radius: 3px;
            overflow: hidden;
        }

        .attacker-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--blue-500), var(--indigo-500));
            border-radius: 3px;
            transition: width .6s ease;
        }

        .attacker-count {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-secondary);
            width: 28px;
            text-align: right;
        }

        /* ─── Radar Canvas ─── */
        .radar-wrap {
            position: relative;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #radarCanvas { width: 100%; height: 100%; }
        
        .act-btn {
            padding: 3px 6px; border-radius: 5px; font-size: 10px; font-weight: 600;
            border: 1px solid var(--border); background: var(--white); cursor: pointer;
            transition: all .15s; font-family: 'Inter', sans-serif;
        }
        .btn-resolve { color: #16a34a; border-color: #bbf7d0; background: #f0fdf4; }
        .btn-resolve:hover { background: #dcfce7; }
        .btn-whitelist { color: #2563eb; border-color: #dbeafe; background: #eff6ff; }
        .btn-whitelist:hover { background: #dbeafe; }
    </style>
</head>
<body>

<!-- ─── Sidebar ─── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon">🛡️</div>
            <div class="brand-text">
                <h2>BAKUL NOC</h2>
                <p>Security Center</p>
            </div>
        </div>
        <div class="live-badge"><div class="live-dot"></div> MONITORING ACTIVE</div>
    </div>

    <nav class="nav-section">
        <div class="nav-label">Monitor</div>
        <a href="<?= BASEURL ?>/noc" class="nav-item active">
            <span class="icon">📊</span> Dashboard
        </a>
        <a href="<?= BASEURL ?>/noc/logs" class="nav-item">
            <span class="icon">📋</span> Traffic Logs
        </a>
        <a href="<?= BASEURL ?>/noc/blocked" class="nav-item">
            <span class="icon">🚫</span> Blocked IPs
        </a>
    </nav>

    <nav class="nav-section">
        <div class="nav-label">Security</div>
        <a href="<?= BASEURL ?>/noc/audit" class="nav-item">
            <span class="icon">🔍</span> Self-Audit
        </a>
        <a href="<?= BASEURL ?>/noc/siem" class="nav-item"><span class="icon">🛡️</span> SIEM Console</a>
        <a href="<?= BASEURL ?>/noc/settings" class="nav-item">
            <span class="icon">⚙️</span> Settings
        </a>
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
        <a href="<?= BASEURL ?>/noc/terminal" class="nav-item">
            <span class="icon">💻</span> Web Console
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= BASEURL ?>/admin" target="_blank">
            <span>⚙️</span> Admin Panel
        </a>
        <a href="<?= BASEURL ?>/noc/logout" style="margin-top:4px">
            <span>🚪</span> Logout NOC
        </a>
    </div>
</aside>

<!-- ─── Main Content ─── -->
<main class="main">
    <div class="topbar">
        <div class="topbar-title">
            <h1>Network Operations Center</h1>
            <p>Real-time traffic monitoring & cyber threat detection</p>
        </div>
        <div class="topbar-right">
            <div class="time-display" id="liveClock">--:--:--</div>
        </div>
    </div>

    <div class="content">

        <!-- Stat Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon" style="background:var(--blue-50)">🌐</div>
                    <span class="stat-trend neu">24h</span>
                </div>
                <div class="stat-value"><?= number_format($data['total_requests_24h']) ?></div>
                <div class="stat-label">Total Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon" style="background:var(--red-50)">⚠️</div>
                    <span class="stat-trend <?= $data['total_threats_24h'] > 0 ? 'down' : 'up' ?>">
                        <?= $data['total_threats_24h'] > 0 ? '🔴' : '✅' ?>
                    </span>
                </div>
                <div class="stat-value" style="color:<?= $data['total_threats_24h'] > 0 ? 'var(--red-500)' : 'var(--green-500)' ?>">
                    <?= number_format($data['total_threats_24h']) ?>
                </div>
                <div class="stat-label">Threats Detected (24h)</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon" style="background:var(--orange-50)">🚫</div>
                    <span class="stat-trend <?= $data['total_blocked_ips'] > 0 ? 'down' : 'up' ?>">
                        IPs
                    </span>
                </div>
                <div class="stat-value" style="color:<?= $data['total_blocked_ips'] > 0 ? 'var(--orange-500)' : 'var(--green-500)' ?>">
                    <?= number_format($data['total_blocked_ips']) ?>
                </div>
                <div class="stat-label">Blocked IPs</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon" style="background:var(--green-50)">⚡</div>
                    <span class="stat-trend <?= $data['avg_response_ms'] < 200 ? 'up' : 'down' ?>">
                        avg
                    </span>
                </div>
                <div class="stat-value"><?= $data['avg_response_ms'] ?></div>
                <div class="stat-label">Avg Response (ms)</div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">📈 Traffic vs Threats — 24 Jam Terakhir</div>
                </div>
                <div class="panel-body">
                    <canvas id="trafficChart" style="max-height:220px"></canvas>
                </div>
            </div>
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">🎯 Threat Type Distribution</div>
                </div>
                <div class="panel-body">
                    <?php if (empty($data['threat_types'])): ?>
                        <div style="text-align:center;padding:40px 0;color:var(--text-muted);font-size:13px">
                            ✅ Tidak ada ancaman terdeteksi
                        </div>
                    <?php else: ?>
                    <canvas id="threatPieChart" style="max-height:200px"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="bottom-grid">
            <!-- Recent Threats Feed -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <span style="color:#ef4444">🔴</span> Live Threat Feed
                    </div>
                    <a href="<?= BASEURL ?>/noc/logs?filter=threats" style="font-size:12px;color:var(--blue-500);text-decoration:none">Lihat Semua →</a>
                </div>
                <div class="panel-body" style="padding:0 20px">
                    <div class="feed-list">
                        <?php if (empty($data['recent_threats'])): ?>
                        <div style="text-align:center;padding:40px 0;color:var(--text-muted);font-size:13px">
                            ✅ Tidak ada ancaman terdeteksi
                        </div>
                        <?php else: ?>
                        <?php foreach ($data['recent_threats'] as $t): ?>
                        <div class="feed-item">
                            <div>
                                <?= nocBadge($t['threat_level']) ?>
                            </div>
                            <div class="feed-info">
                                <div class="feed-type"><?= htmlspecialchars($t['threat_type'] ?? 'Unknown') ?></div>
                                <div class="feed-ip"><?= htmlspecialchars($t['ip']) ?></div>
                                <div class="feed-detail"><?= htmlspecialchars($t['threat_detail'] ?? $t['uri']) ?></div>
                                <div style="display:flex; gap:4px; margin-top:5px;">
                                    <form method="POST" action="<?= BASEURL ?>/noc/resolve_threat" style="display:inline">
                                        <input type="hidden" name="id" value="<?= $t['id'] ?? 0 ?>">
                                        <button type="submit" class="act-btn btn-resolve" title="Tandai selesai">Resolve</button>
                                    </form>
                                    <?php if (!NocGuard::isIPWhitelisted($t['ip'])): ?>
                                        <form method="POST" action="<?= BASEURL ?>/noc/whitelist_ip" style="display:inline">
                                            <input type="hidden" name="ip" value="<?= $t['ip'] ?>">
                                            <button type="submit" class="act-btn btn-whitelist" title="Whitelist IP">Whitelist</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="font-size:9.5px;color:var(--text-muted);font-weight:600;padding:2px 6px;border:1px dashed var(--border);border-radius:5px;background:rgba(255,255,255,0.05)">🛡️ Whitelisted</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="feed-time"><?= date('H:i', strtotime($t['created_at'])) ?></div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Top Attackers -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">🎯 Top Attacker IPs</div>
                    <a href="<?= BASEURL ?>/noc/blocked" style="font-size:12px;color:var(--blue-500);text-decoration:none">Manage Blocks →</a>
                </div>
                <div class="panel-body">
                    <?php if (empty($data['top_attackers'])): ?>
                    <div style="text-align:center;padding:40px 0;color:var(--text-muted);font-size:13px">
                        ✅ Tidak ada IP mencurigakan
                    </div>
                    <?php else: ?>
                    <?php $maxAttempts = max(array_column($data['top_attackers'], 'attempts') ?: [1]); ?>
                    <?php foreach ($data['top_attackers'] as $a): ?>
                    <div class="attacker-row">
                        <?= nocBadge($a['max_level']) ?>
                        <div class="attacker-ip"><?= htmlspecialchars($a['ip']) ?></div>
                        <div class="attacker-bar-wrap">
                            <div class="attacker-bar" style="width:<?= round(($a['attempts'] / $maxAttempts) * 100) ?>%"></div>
                        </div>
                        <div class="attacker-count"><?= $a['attempts'] ?></div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div><!-- /content -->
</main>

<script>
// Live clock
function updateClock() {
    const now = new Date();
    document.getElementById('liveClock').textContent =
        now.toTimeString().split(' ')[0];
}
updateClock();
setInterval(updateClock, 1000);

// Traffic Chart
const trafficCtx = document.getElementById('trafficChart');
if (trafficCtx) {
    new Chart(trafficCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_values($hourlyLabels)) ?>,
            datasets: [
                {
                    label: 'Total Requests',
                    data: <?= json_encode(array_values($hourlyReqs)) ?>,
                    backgroundColor: 'rgba(59,130,246,.15)',
                    borderColor: '#3b82f6',
                    borderWidth: 1.5,
                    borderRadius: 5,
                    order: 2,
                },
                {
                    label: 'Threats',
                    data: <?= json_encode(array_values($hourlyThreats)) ?>,
                    backgroundColor: 'rgba(239,68,68,.8)',
                    borderColor: '#dc2626',
                    borderWidth: 1,
                    borderRadius: 5,
                    order: 1,
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: { legend: { position: 'top', labels: { color: '#94a3b8', font: { size: 12 }, boxWidth: 12 } } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
                y: { grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#94a3b8', font: { size: 11 } }, beginAtZero: true }
            }
        }
    });
}

// Threat Pie Chart
const pieCtx = document.getElementById('threatPieChart');
if (pieCtx && <?= count($data['threat_types']) ?> > 0) {
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_values($threatTypeLabels)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($threatTypeCounts)) ?>,
                backgroundColor: ['#ef4444','#f97316','#eab308','#3b82f6','#6366f1','#8b5cf6'],
                borderWidth: 2,
                borderColor: '#111827',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#94a3b8', font: { size: 11 }, boxWidth: 10 } }
            },
            cutout: '60%'
        }
    });
}
</script>
</body>
</html>
