<?php // NOC — Blocked IPs View ?>
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
            --sidebar-w: 240px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-primary); display: flex; min-height: 100vh; }
        .sidebar { width: var(--sidebar-w); min-height: 100vh; background: var(--white); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 100; }
        .sidebar-brand { padding: 20px 20px 16px; border-bottom: 1px solid var(--border-light); }
        .brand-logo { display: flex; align-items: center; gap: 10px; }
        .brand-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .brand-text h2 { font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .brand-text p { font-size: 11px; color: var(--text-muted); }
        .live-badge { display: flex; align-items: center; gap: 5px; margin-top: 10px; font-size: 10.5px; font-weight: 600; color: #22c55e; }
        .live-dot { width: 6px; height: 6px; background: #22c55e; border-radius: 50%; animation: livepulse 1.5s infinite; }
        @keyframes livepulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
        .nav-section { padding: 16px 12px 8px; }
        .nav-label { font-size: 10px; font-weight: 700; color: var(--text-muted); letter-spacing: .8px; text-transform: uppercase; padding: 0 8px; margin-bottom: 4px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 9px; font-size: 13.5px; font-weight: 500; color: var(--text-secondary); text-decoration: none; transition: all .15s; margin-bottom: 2px; }
        .nav-item:hover { background: var(--bg); color: var(--text-primary); }
        .nav-item.active { background: var(--blue-50); color: var(--blue-500); font-weight: 600; border: 1px solid var(--border); }
        .nav-item .icon { font-size: 16px; width: 20px; text-align: center; }
        .sidebar-footer { margin-top: auto; padding: 16px 12px; border-top: 1px solid var(--border-light); }
        .sidebar-footer a { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); text-decoration: none; padding: 8px 10px; border-radius: 8px; transition: all .15s; margin-bottom: 4px; }
        .sidebar-footer a:hover { background: rgba(239, 68, 68, 0.1); color: #f87171; }
        .main { margin-left: var(--sidebar-w); flex: 1; min-width: 0; }
        .topbar { background: var(--white); border-bottom: 1px solid var(--border); padding: 14px 28px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar h1 { font-size: 17px; font-weight: 700; }
        .topbar p { font-size: 12.5px; color: var(--text-muted); }
        .content { padding: 24px 28px; }

        .two-col { display: grid; grid-template-columns: 1.6fr 1fr; gap: 20px; align-items: start; }

        .panel { background: var(--white); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; }
        .panel-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }

        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--bg); }
        th { text-align: left; padding: 10px 16px; font-size: 11px; font-weight: 700; color: var(--text-muted); letter-spacing: .4px; text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid var(--border-light); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255, 255, 255, 0.02); }
        .mono { font-family: 'JetBrains Mono', monospace; font-size: 12.5px; }

        .auto-tag { font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 4px; }
        .auto-tag.auto { background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .auto-tag.manual { background: rgba(56, 189, 248, 0.1); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }

        .btn-unblock {
            padding: 5px 12px; border-radius: 7px; border: 1px solid rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.1);
            color: #f87171; font-size: 12px; font-weight: 600; cursor: pointer; font-family: 'Inter', sans-serif;
            transition: all .15s;
        }
        .btn-unblock:hover { background: rgba(239, 68, 68, 0.2); border-color: rgba(239, 68, 68, 0.5); }

        .block-form { padding: 20px; }
        .block-form label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text-secondary); margin-bottom: 5px; margin-top: 14px; }
        .block-form label:first-child { margin-top: 0; }
        .block-form input, .block-form textarea {
            width: 100%; padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 8px;
            font-size: 13px; font-family: 'Inter', sans-serif; color: var(--text-primary); background: var(--bg); outline: none; transition: all .15s;
        }
        .block-form input:focus, .block-form textarea:focus { border-color: var(--blue-500); background: var(--surface-2); box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1); }
        .btn-block {
            width: 100%; margin-top: 16px; padding: 10px; background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff; border: none; border-radius: 9px; font-size: 13.5px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer;
            transition: all .2s; box-shadow: 0 3px 12px rgba(239, 68, 68, .25);
        }
        .btn-block:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(239, 68, 68, .35); }

        .empty-state { text-align: center; padding: 48px 20px; color: var(--text-muted); font-size: 13px; }
        .empty-state .icon { font-size: 40px; display: block; margin-bottom: 12px; }
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
        <a href="<?= BASEURL ?>/noc" class="nav-item"><span class="icon">📊</span> Dashboard</a>
        <a href="<?= BASEURL ?>/noc/logs" class="nav-item"><span class="icon">📋</span> Traffic Logs</a>
        <a href="<?= BASEURL ?>/noc/blocked" class="nav-item active"><span class="icon">🚫</span> Blocked IPs</a>
    </nav>
    <nav class="nav-section">
        <div class="nav-label">Security</div>
        <a href="<?= BASEURL ?>/noc/audit" class="nav-item"><span class="icon">🔍</span> Self-Audit</a>
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
            <h1>Blocked IPs</h1>
            <p>Daftar IP yang diblokir — otomatis & manual oleh operator NOC</p>
        </div>
    </div>
    <div class="content">
        <div class="two-col">
            <!-- Table -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">🚫 IP Terblokir (<?= count($data['blocked_ips']) ?>)</div>
                </div>
                <?php if (empty($data['blocked_ips'])): ?>
                <div class="empty-state">
                    <span class="icon">✅</span>
                    Tidak ada IP yang diblokir saat ini
                </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th>Alasan</th>
                            <th>Tipe</th>
                            <th>Blokir</th>
                            <th>Hit</th>
                            <th>Kedaluwarsa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data['blocked_ips'] as $b): ?>
                    <tr>
                        <td class="mono" style="font-weight:600;color:var(--text-primary)"><?= htmlspecialchars($b['ip']) ?></td>
                        <td style="max-width:200px;font-size:12px"><?= htmlspecialchars($b['reason']) ?></td>
                        <td>
                            <span class="auto-tag <?= $b['auto_blocked'] ? 'auto' : 'manual' ?>">
                                <?= $b['auto_blocked'] ? 'AUTO' : 'MANUAL' ?>
                            </span>
                        </td>
                        <td style="font-size:12px"><?= date('d M H:i', strtotime($b['blocked_at'])) ?></td>
                        <td style="font-weight:700;color:#f87171"><?= $b['block_count'] ?>×</td>
                        <td style="font-size:12px;color:var(--text-muted)">
                            <?= $b['expires_at'] ? date('d M Y', strtotime($b['expires_at'])) : '∞' ?>
                        </td>
                        <td>
                            <form method="POST" action="<?= BASEURL ?>/noc/toggle_block" onsubmit="return confirm('Unblock IP <?= htmlspecialchars($b['ip']) ?>?')">
                                <input type="hidden" name="action" value="unblock">
                                <input type="hidden" name="ip" value="<?= htmlspecialchars($b['ip']) ?>">
                                <button type="submit" class="btn-unblock">Unblock</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>

            <!-- Manual Block Form -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">🔒 Block IP Manual</div>
                </div>
                <div class="block-form">
                    <form method="POST" action="<?= BASEURL ?>/noc/toggle_block">
                        <input type="hidden" name="action" value="block">
                        <label for="block_ip">IP Address</label>
                        <input type="text" id="block_ip" name="ip" placeholder="Contoh: 192.168.1.100" required pattern="^[\d\.\:a-fA-F]+$">
                        <label for="block_reason">Alasan Pemblokiran</label>
                        <textarea id="block_reason" name="reason" rows="3" placeholder="Contoh: Aktivitas spam mencurigakan dari IP ini"></textarea>
                        <button type="submit" class="btn-block">🚫 Blokir IP Sekarang</button>
                    </form>
                    <p style="margin-top:14px;font-size:11.5px;color:var(--text-muted);line-height:1.5">
                        IP akan diblokir selama 30 hari dan setiap request dari IP tersebut akan mendapat respons 403 Forbidden.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
