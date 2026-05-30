<?php // NOC — Backup Wizard View ?>
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
        .nav-label { font-size: 10px; font-weight: 700; color: var(--text-muted); letter-spacing: .8px; text-transform: uppercase; padding: 0; margin-bottom: 4px; }
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
        .content { padding: 24px 28px; max-width: 1200px; display: flex; flex-direction: column; gap: 24px; }

        /* Flash message */
        .flash-msg { padding: 12px 16px; border-radius: 8px; font-size: 13.5px; }
        .flash-msg.success { background: rgba(74, 222, 128, 0.08); border: 1px solid rgba(74, 222, 128, 0.2); color: #4ade80; }
        .flash-msg.error { background: var(--red-50); border: 1px solid rgba(248, 113, 113, 0.2); color: var(--red-500); }

        /* Wizard Panel */
        .grid-wizards { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .panel { background: var(--white); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border-light); }
        .panel-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }

        .wiz-card { padding: 24px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 12px; }
        .wiz-icon { font-size: 40px; }
        .wiz-title { font-size: 16px; font-weight: 700; color: var(--text-primary); }
        .wiz-desc { font-size: 13px; color: var(--text-secondary); line-height: 1.5; margin-bottom: 8px; }
        .btn-wiz { padding: 10px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: 13.5px; cursor: pointer; color: #fff; transition: all .15s; }
        .btn-wiz.db { background: var(--blue-600); }
        .btn-wiz.db:hover { background: var(--blue-500); }
        .btn-wiz.code { background: var(--green-600); color: #090d16; font-weight: 700; }
        .btn-wiz.code:hover { background: var(--green-500); }

        /* Tables */
        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--bg); }
        th { text-align: left; padding: 11px 16px; font-size: 11px; font-weight: 700; color: var(--text-muted); letter-spacing: .4px; text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 11px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid var(--border-light); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255, 255, 255, 0.02); }
        .mono { font-family: 'JetBrains Mono', monospace; font-size: 12.5px; }

        .act-link { color: var(--blue-500); text-decoration: none; font-weight: 600; font-size: 12.5px; margin-right: 12px; }
        .act-link:hover { text-decoration: underline; }
        .btn-delete { background: none; border: none; color: var(--red-500); font-weight: 600; font-size: 12.5px; cursor: pointer; transition: color 0.15s; }
        .btn-delete:hover { text-decoration: underline; color: #ef4444; }
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
        <a href="<?= BASEURL ?>/noc/database" class="nav-item"><span class="icon">🗄️</span> DB Manager</a>
        <a href="<?= BASEURL ?>/noc/cron"     class="nav-item"><span class="icon">⏱️</span> Cron Jobs</a>
        <a href="<?= BASEURL ?>/noc/backup"   class="nav-item active"><span class="icon">💾</span> Backup Wizard</a>
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
            <h1>Backup Wizard</h1>
            <p>Cadangkan database siber dan arsipkan berkas kode aplikasi secara instan</p>
        </div>
    </div>

    <div class="content">
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['noc_flash'])): ?>
            <div class="flash-msg success">✅ <?= htmlspecialchars($_SESSION['noc_flash']) ?></div>
            <?php unset($_SESSION['noc_flash']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['noc_flash_error'])): ?>
            <div class="flash-msg error">❌ <?= htmlspecialchars($_SESSION['noc_flash_error']) ?></div>
            <?php unset($_SESSION['noc_flash_error']); ?>
        <?php endif; ?>

        <!-- Wizard Selection cards -->
        <div class="grid-wizards">
            <!-- DB Backup Card -->
            <div class="panel">
                <div class="wiz-card">
                    <div class="wiz-icon">🗄️</div>
                    <div class="wiz-title">Backup Database</div>
                    <div class="wiz-desc">Ekspor struktur tabel dan data database toko online bakul_ecommerce ke dalam format SQL dump secara langsung.</div>
                    <form method="POST" action="<?= BASEURL ?>/noc/create_backup">
                        <input type="hidden" name="type" value="db">
                        <button type="submit" class="btn-wiz db">Mulai Backup SQL</button>
                    </form>
                </div>
            </div>

            <!-- Code Backup Card -->
            <div class="panel">
                <div class="wiz-card">
                    <div class="wiz-icon">📁</div>
                    <div class="wiz-title">Backup Berkas Kode</div>
                    <div class="wiz-desc">Kompresi berkas kode aplikasi web ke file ZIP (mengecualikan folder vendor dan backups untuk menghemat ruang).</div>
                    <form method="POST" action="<?= BASEURL ?>/noc/create_backup">
                        <input type="hidden" name="type" value="code">
                        <button type="submit" class="btn-wiz code">Mulai Backup ZIP</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Saved Backups Table -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">📋 Riwayat Arsip Backup Tersimpan</div>
            </div>
            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Arsip Berkas</th>
                            <th>Ukuran</th>
                            <th>Tanggal Pembuatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['files'])): ?>
                        <tr>
                            <td colspan="4" style="text-align:center;padding:30px;color:var(--text-muted)">
                                Belum ada berkas backup tersimpan. Klik tombol di atas untuk membuat.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($data['files'] as $f): ?>
                        <tr>
                            <td class="mono" style="font-weight:600;color:var(--text-primary)"><?= htmlspecialchars($f['name']) ?></td>
                            <td class="mono"><?= number_format($f['size'] / (1024 * 1024), 2) ?> MB</td>
                            <td class="mono" style="font-size:12px;color:var(--text-muted)"><?= htmlspecialchars($f['date']) ?></td>
                            <td>
                                <a class="act-link" href="<?= BASEURL ?>/noc/download_backup?file=<?= urlencode($f['name']) ?>">Unduh (Download)</a>
                                <form method="POST" action="<?= BASEURL ?>/noc/delete_backup" style="display:inline" onsubmit="return confirm('Hapus permanen backup ini?')">
                                    <input type="hidden" name="file" value="<?= htmlspecialchars($f['name']) ?>">
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

</body>
</html>
