<?php // NOC — Database Manager View ?>
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
            --green-700: #22c55e;
            --red-500: #f87171;
            --red-50: rgba(248, 113, 113, 0.08);
            --red-700: #ef4444;
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
        .content { padding: 24px 28px; max-width: 1300px; display: grid; grid-template-columns: 240px 1fr; gap: 24px; }

        /* Left db tables sidebar */
        .db-sidebar { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 18px; height: calc(100vh - 120px); overflow-y: auto; }
        .db-sidebar-title { font-size: 12.5px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 14px; display: flex; justify-content: space-between; }
        .db-table-link { display: block; font-size: 13px; color: var(--text-secondary); text-decoration: none; padding: 6px 10px; border-radius: 6px; margin-bottom: 3px; font-family: 'JetBrains Mono', monospace; word-break: break-all; }
        .db-table-link:hover { background: var(--bg); color: var(--blue-500); }

        /* Right execution workspace */
        .workspace { display: flex; flex-direction: column; gap: 20px; }
        
        .panel { background: var(--white); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; }
        .panel-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }

        .query-form { padding: 20px; display: flex; flex-direction: column; gap: 14px; }
        .sql-textarea { width: 100%; height: 140px; border: 1.5px solid var(--border); border-radius: 10px; padding: 16px; font-family: 'JetBrains Mono', monospace; font-size: 13.5px; outline: none; background: var(--bg); color: var(--text-primary); resize: vertical; }
        .sql-textarea:focus { border-color: var(--blue-500); background: var(--surface-2); }
        
        .form-actions { display: flex; justify-content: space-between; align-items: center; }
        .btn-run { padding: 9px 20px; background: var(--blue-600); color: #fff; border: none; border-radius: 8px; font-weight: 600; font-size: 13.5px; cursor: pointer; transition: all .15s; }
        .btn-run:hover { background: var(--blue-500); }

        /* Notification Banners */
        .msg-banner { padding: 14px 20px; border-radius: 8px; font-size: 13.5px; margin: 20px; border: 1px solid; }
        .msg-banner.success { background: rgba(74, 222, 128, 0.08); border-color: rgba(74, 222, 128, 0.2); color: #4ade80; }
        .msg-banner.error { background: var(--red-50); border-color: rgba(248, 113, 113, 0.2); color: var(--red-500); font-family: 'JetBrains Mono', monospace; font-size: 12.5px; }

        /* Results table */
        .table-wrap { overflow-x: auto; max-height: 400px; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--bg); position: sticky; top: 0; }
        th { text-align: left; padding: 10px 16px; font-size: 11px; font-weight: 700; color: var(--text-muted); letter-spacing: .4px; text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 10px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid var(--border-light); font-family: 'JetBrains Mono', monospace; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255, 255, 255, 0.02); }
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
        <a href="<?= BASEURL ?>/noc/system"   class="nav-item"><span class="icon">📈</span> System Info</a>
        <a href="<?= BASEURL ?>/noc/database" class="nav-item active"><span class="icon">🗄️</span> DB Manager</a>
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
            <h1>Database Manager</h1>
            <p>Kelola data, lihat struktur tabel, dan jalankan perintah query SQL</p>
        </div>
    </div>

    <div class="content">
        <!-- Tables Sidebar -->
        <aside class="db-sidebar">
            <div class="db-sidebar-title">
                <span>🗄️ Tables (<?= count($data['tables']) ?>)</span>
            </div>
            <?php foreach ($data['tables'] as $tbl): ?>
            <a href="#" class="db-table-link" onclick="fillQuery('SELECT * FROM `<?= addslashes($tbl) ?>` LIMIT 20;')">
                📊 <?= htmlspecialchars($tbl) ?>
            </a>
            <?php endforeach; ?>
        </aside>

        <!-- Execution Workspace -->
        <div class="workspace">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">⚡ Query Editor</div>
                </div>
                
                <form class="query-form" method="POST" action="<?= BASEURL ?>/noc/run_query">
                    <textarea class="sql-textarea" name="query" placeholder="SELECT * FROM users LIMIT 10;" required><?= htmlspecialchars($data['query']) ?></textarea>
                    
                    <div class="form-actions">
                        <span style="font-size:12px;color:var(--text-muted)">Akhiri kueri Anda dengan titik koma (;)</span>
                        <button type="submit" class="btn-run">Jalankan Query (Execute)</button>
                    </div>
                </form>
            </div>

            <!-- Banners & Results -->
            <?php if ($data['error']): ?>
            <div class="msg-banner error">
                <strong>Error Eksekusi SQL:</strong><br>
                <?= htmlspecialchars($data['error']) ?>
            </div>
            <?php endif; ?>

            <?php if ($data['affected'] !== null): ?>
            <div class="msg-banner success">
                ✅ Query berhasil dijalankan. Baris terpengaruh: <strong><?= $data['affected'] ?></strong>
            </div>
            <?php endif; ?>

            <?php if (!empty($data['results'])): ?>
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">📋 Hasil Query (<?= count($data['results']) ?> baris ditemukan)</div>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <?php foreach ($data['headers'] as $h): ?>
                                <th><?= htmlspecialchars($h) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['results'] as $row): ?>
                            <tr>
                                <?php foreach ($data['headers'] as $h): ?>
                                <td>
                                    <?php
                                    if ($row[$h] === null) {
                                        echo '<span style="color:var(--text-muted);font-style:italic">NULL</span>';
                                    } else {
                                        echo htmlspecialchars(substr($row[$h], 0, 100)) . (strlen($row[$h]) > 100 ? '...' : '');
                                    }
                                    ?>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php elseif (empty($data['error']) && !empty($data['query']) && $data['affected'] === null): ?>
            <div class="panel" style="padding: 24px; text-align: center; color: var(--text-muted)">
                Query tidak menghasilkan baris output.
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
    function fillQuery(sql) {
        document.querySelector('.sql-textarea').value = sql;
        document.querySelector('.sql-textarea').focus();
    }
</script>

</body>
</html>
