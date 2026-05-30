<?php
// NOC — Traffic Logs View
function logBadge($level) {
    $map = [
        'critical' => ['rgba(239,68,68,0.1)','#f87171','rgba(239,68,68,0.3)','CRITICAL'],
        'high'     => ['rgba(249,115,22,0.1)','#fb923c','rgba(249,115,22,0.3)','HIGH'],
        'medium'   => ['rgba(234,179,8,0.1)','#facc15','rgba(234,179,8,0.3)','MEDIUM'],
        'low'      => ['rgba(34,197,94,0.1)','#4ade80','rgba(34,197,94,0.3)','LOW'],
        'none'     => ['rgba(100,116,139,0.1)','#cbd5e1','rgba(100,116,139,0.3)','CLEAN'],
    ];
    $m = $map[$level] ?? $map['none'];
    return "<span style=\"background:{$m[0]};color:{$m[1]};border:1px solid {$m[2]};border-radius:4px;padding:2px 7px;font-size:10px;font-weight:700;letter-spacing:.4px\">{$m[3]}</span>";
}
?>
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

        .filters {
            display: flex; gap: 8px; margin-bottom: 20px; align-items: center; flex-wrap: wrap;
        }

        .filter-btn {
            padding: 7px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; border: 1.5px solid var(--border);
            background: var(--white); color: var(--text-secondary); text-decoration: none; transition: all .15s;
        }
        .filter-btn.active, .filter-btn:hover {
            background: var(--blue-50); border-color: var(--blue-500); color: var(--blue-500);
        }

        .total-label { font-size: 13px; color: var(--text-muted); margin-left: auto; }

        .table-wrap {
            background: var(--white); border: 1px solid var(--border); border-radius: 14px; overflow: hidden;
        }

        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--bg); }
        th {
            text-align: left; padding: 11px 14px; font-size: 11.5px; font-weight: 700;
            color: var(--text-muted); letter-spacing: .4px; text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }
        td { padding: 11px 14px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid var(--border-light); vertical-align: top; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255, 255, 255, 0.02); }

        .mono { font-family: 'JetBrains Mono', monospace; font-size: 12px; }

        .uri-cell { max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        .payload-toggle { cursor: pointer; color: var(--blue-500); font-size: 11px; text-decoration: underline; white-space: nowrap; }

        .payload-box {
            display: none; background: #0b0f19; border: 1px solid var(--border); border-radius: 6px;
            padding: 8px 10px; margin-top: 6px; font-family: 'JetBrains Mono', monospace; font-size: 11px;
            color: var(--text-secondary); white-space: pre-wrap; word-break: break-all; max-height: 120px; overflow-y: auto;
        }

        .pagination {
            display: flex; align-items: center; justify-content: center; gap: 6px; padding: 16px;
            border-top: 1px solid var(--border);
        }
        .pag-btn {
            padding: 6px 12px; border-radius: 7px; border: 1px solid var(--border); background: var(--white);
            font-size: 13px; text-decoration: none; color: var(--text-secondary); transition: all .15s;
        }
        .pag-btn.active { background: var(--blue-50); border-color: var(--blue-500); color: var(--blue-500); font-weight: 700; }
        .pag-btn:hover:not(.active) { background: var(--bg); }

        .clear-btn {
            padding: 7px 16px; border-radius: 8px; border: 1.5px solid rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.1);
            color: #f87171; font-size: 13px; font-weight: 500; cursor: pointer; font-family: 'Inter', sans-serif;
            transition: all 0.15s;
        }
        .clear-btn:hover { background: rgba(239, 68, 68, 0.2); }
        .act-btn {
            padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;
            border: 1px solid var(--border); background: var(--white); cursor: pointer;
            transition: all .15s; font-family: 'Inter', sans-serif;
        }
        .btn-resolve { color: #4ade80; border-color: rgba(74, 222, 128, 0.3); background: rgba(74, 222, 128, 0.1); }
        .btn-resolve:hover { background: rgba(74, 222, 128, 0.2); }
        .btn-whitelist { color: #38bdf8; border-color: rgba(56, 189, 248, 0.3); background: rgba(56, 189, 248, 0.1); }
        .btn-whitelist:hover { background: rgba(56, 189, 248, 0.2); }
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
        <a href="<?= BASEURL ?>/noc/logs" class="nav-item active"><span class="icon">📋</span> Traffic Logs</a>
        <a href="<?= BASEURL ?>/noc/blocked" class="nav-item"><span class="icon">🚫</span> Blocked IPs</a>
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
            <h1>Traffic Logs</h1>
            <p>Log semua request yang masuk ke aplikasi BAKUL</p>
        </div>
    </div>
    <div class="content">
        <div class="filters">
            <a href="?filter=all"     class="filter-btn <?= $data['filter']==='all'     ? 'active':'' ?>">🌐 Semua</a>
            <a href="?filter=threats" class="filter-btn <?= $data['filter']==='threats' ? 'active':'' ?>">⚠️ Threats Only</a>
            <a href="?filter=blocked" class="filter-btn <?= $data['filter']==='blocked' ? 'active':'' ?>">🚫 Diblokir</a>
            <a href="?filter=clean"   class="filter-btn <?= $data['filter']==='clean'   ? 'active':'' ?>">✅ Clean</a>
            <span class="total-label">Total: <?= number_format($data['total']) ?> log</span>
            <form method="POST" action="<?= BASEURL ?>/noc/clear_logs" onsubmit="return confirm('Hapus log clean yg lebih dari 7 hari?')">
                <button type="submit" class="clear-btn">🗑️ Bersihkan Log Lama</button>
            </form>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>IP</th>
                        <th>Method</th>
                        <th>URI</th>
                        <th>Status</th>
                        <th>Resp (ms)</th>
                        <th>Level</th>
                        <th>Threat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['logs'])): ?>
                    <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">Tidak ada log ditemukan.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($data['logs'] as $log): ?>
                    <tr>
                        <td class="mono" style="white-space:nowrap">
                            <?= date('d/m H:i:s', strtotime($log['created_at'])) ?>
                        </td>
                        <td class="mono"><?= htmlspecialchars($log['ip']) ?></td>
                        <td>
                            <span style="font-weight:600;font-size:11.5px;color:<?= $log['method']==='POST'?'var(--blue-500)':'var(--text-secondary)' ?>">
                                <?= htmlspecialchars($log['method']) ?>
                            </span>
                        </td>
                        <td class="uri-cell">
                            <div title="<?= htmlspecialchars($log['uri']) ?>"><?= htmlspecialchars($log['uri']) ?></div>
                            <?php if (!empty($log['post_data']) && $log['post_data'] !== '[]'): ?>
                            <span class="payload-toggle" onclick="togglePayload(<?= $log['id'] ?>)">Lihat POST data</span>
                            <pre class="payload-box" id="payload-<?= $log['id'] ?>"><?= htmlspecialchars($log['post_data']) ?></pre>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="font-weight:700;color:<?= $log['response_code'] >= 400 ? '#f87171' : '#4ade80' ?>">
                                <?= htmlspecialchars($log['response_code'] ?? '-') ?>
                            </span>
                        </td>
                        <td><?= $log['exec_time_ms'] ? round($log['exec_time_ms'], 1).'ms' : '-' ?></td>
                        <td><?= logBadge($log['threat_level']) ?></td>
                        <td>
                            <?php if ($log['threat_type']): ?>
                            <div style="font-weight:600;font-size:12px"><?= htmlspecialchars($log['threat_type']) ?></div>
                            <?php if ($log['threat_detail']): ?>
                            <div style="font-size:11px;color:var(--text-muted);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= htmlspecialchars($log['threat_detail']) ?>">
                                <?= htmlspecialchars($log['threat_detail']) ?>
                            </div>
                            <?php endif; ?>
                            <?php else: ?>
                            <span style="color:var(--text-muted);font-size:12px">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display:flex; gap:4px; align-items:center;">
                                <?php if ($log['threat_level'] !== 'none'): ?>
                                    <form method="POST" action="<?= BASEURL ?>/noc/resolve_threat" style="display:inline">
                                        <input type="hidden" name="id" value="<?= $log['id'] ?>">
                                        <button type="submit" class="act-btn btn-resolve" title="Tandai sudah diatasi">✅ Resolve</button>
                                    </form>
                                <?php endif; ?>
                                
                                <?php if (!NocGuard::isIPWhitelisted($log['ip'])): ?>
                                    <form method="POST" action="<?= BASEURL ?>/noc/whitelist_ip" style="display:inline">
                                        <input type="hidden" name="ip" value="<?= $log['ip'] ?>">
                                        <button type="submit" class="act-btn btn-whitelist" title="Whitelist IP ini agar tidak terblokir">⚪ Whitelist</button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-size:10.5px;color:var(--text-secondary);font-weight:600;padding:4px 8px;border:1px dashed var(--border);border-radius:6px;background:var(--surface-2)">🛡️ Whitelisted</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if ($data['pages'] > 1): ?>
            <div class="pagination">
                <?php for ($p = max(1, $data['page']-2); $p <= min($data['pages'], $data['page']+2); $p++): ?>
                <a href="?filter=<?= $data['filter'] ?>&page=<?= $p ?>"
                   class="pag-btn <?= $p === $data['page'] ? 'active' : '' ?>"><?= $p ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
function togglePayload(id) {
    const el = document.getElementById('payload-' + id);
    el.style.display = el.style.display === 'block' ? 'none' : 'block';
}
</script>
</body>
</html>
