<?php // NOC — PHP INI Configuration View ?>
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

        .two-col { display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px; }
        .panel { background: var(--white); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border-light); }
        .panel-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }

        /* Form styling */
        .ini-form { padding: 20px; display: flex; flex-direction: column; gap: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label { font-size: 13px; font-weight: 600; color: var(--text-secondary); }
        .form-group input, .form-group select { padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 6px; font-size: 13px; outline: none; background: var(--bg); color: var(--text-primary); }
        .form-group input:focus, .form-group select:focus { border-color: var(--blue-500); background: var(--surface-2); box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1); }
        
        .btn-save { padding: 10px 24px; background: var(--blue-600); color: #fff; border: none; border-radius: 8px; font-weight: 600; font-size: 13.5px; cursor: pointer; transition: all .15s; align-self: flex-start; }
        .btn-save:hover { background: var(--blue-500); }

        /* Raw ini viewer */
        .raw-viewer { padding: 20px; background: #0b0f19; border: 1px solid var(--border); border-top: none; color: #f8fafc; font-family: 'JetBrains Mono', monospace; font-size: 13px; white-space: pre-wrap; line-height: 1.6; border-radius: 0 0 14px 14px; overflow-y: auto; height: 350px; }
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
        <a href="<?= BASEURL ?>/noc/backup"   class="nav-item"><span class="icon">💾</span> Backup Wizard</a>
        <a href="<?= BASEURL ?>/noc/phpini"   class="nav-item active"><span class="icon">⚙️</span> PHP Config</a>
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
            <h1>PHP Configuration</h1>
            <p>Atur setelan batas PHP VPS (MultiPHP INI Editor) secara dinamis menggunakan .user.ini</p>
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

        <div class="two-col">
            <!-- Form Editor -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">⚙️ Konfigurasi PHP Dinamis (.user.ini)</div>
                </div>
                
                <form class="ini-form" method="POST" action="<?= BASEURL ?>/noc/save_phpini">
                    <div class="form-group">
                        <label>Batas Memori (memory_limit)</label>
                        <input type="text" name="memory_limit" value="<?= htmlspecialchars($data['settings']['memory_limit']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Ukuran Maksimal Upload File (upload_max_filesize)</label>
                        <input type="text" name="upload_max_filesize" value="<?= htmlspecialchars($data['settings']['upload_max_filesize']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Ukuran Maksimal Post Data (post_max_size)</label>
                        <input type="text" name="post_max_size" value="<?= htmlspecialchars($data['settings']['post_max_size']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Batas Waktu Eksekusi (max_execution_time dalam detik)</label>
                        <input type="number" name="max_execution_time" value="<?= (int)$data['settings']['max_execution_time'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Tampilkan Pesan Error (display_errors)</label>
                        <select name="display_errors">
                            <option value="0" <?= $data['settings']['display_errors'] == '0' ? 'selected' : '' ?>>Nonaktifkan (0 - Rekomendasi Production)</option>
                            <option value="1" <?= $data['settings']['display_errors'] == '1' ? 'selected' : '' ?>>Aktifkan (1 - Development/Debug)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-save">Simpan Setelan PHP</button>
                </form>
            </div>

            <!-- Current File Output -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">📄 File .user.ini Terkini</div>
                </div>
                <div class="raw-viewer"><?= empty($data['content']) ? '; Belum ada file .user.ini dibuat.' : htmlspecialchars($data['content']) ?></div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
