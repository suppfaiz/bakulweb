<?php // NOC — File Manager View ?>
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
        .content { padding: 24px 28px; max-width: 1200px; }

        .flash-msg { padding: 12px 16px; border-radius: 8px; font-size: 13.5px; margin-bottom: 20px; border: 1px solid; }
        .flash-msg.success { background: rgba(74, 222, 128, 0.08); border-color: rgba(74, 222, 128, 0.2); color: #4ade80; }
        .flash-msg.error { background: var(--red-50); border-color: rgba(248, 113, 113, 0.2); color: var(--red-500); }

        /* ─── Path Bar ─── */
        .path-bar { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 500; color: var(--text-secondary); }
        .path-link { color: var(--blue-500); text-decoration: none; font-weight: 600; }
        .path-link:hover { text-decoration: underline; }
        
        /* ─── Toolbar ─── */
        .toolbar { display: flex; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
        .tool-panel { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 18px; flex: 1; min-width: 280px; }
        .tool-title { font-size: 12.5px; font-weight: 700; color: var(--text-primary); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 8px; }
        .tool-form { display: flex; gap: 8px; }
        .tool-form input { flex: 1; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 6px; font-size: 13px; outline: none; background: var(--bg); color: var(--text-primary); }
        .tool-form input:focus { border-color: var(--blue-500); background: var(--surface-2); box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1); }
        .tool-form select { padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 6px; font-size: 13px; outline: none; background: var(--bg); color: var(--text-primary); }
        .tool-form select:focus { border-color: var(--blue-500); background: var(--surface-2); }
        .tool-btn { padding: 8px 16px; background: var(--blue-600); color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s; }
        .tool-btn:hover { background: var(--blue-500); }

        /* ─── Table ─── */
        .table-wrap { background: var(--white); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--bg); }
        th { text-align: left; padding: 11px 16px; font-size: 11px; font-weight: 700; color: var(--text-muted); letter-spacing: .4px; text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 11px 16px; font-size: 13.5px; color: var(--text-secondary); border-bottom: 1px solid var(--border-light); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255, 255, 255, 0.02); }

        .file-name { display: flex; align-items: center; gap: 10px; color: var(--text-primary); text-decoration: none; font-weight: 500; }
        .file-name:hover { color: var(--blue-500); }
        .icon-file { font-size: 16px; }

        .mono { font-family: 'JetBrains Mono', monospace; font-size: 12.5px; }
        
        .act-link { color: var(--blue-500); text-decoration: none; font-weight: 600; font-size: 12.5px; margin-right: 12px; }
        .act-link:hover { text-decoration: underline; }
        .act-delete { background: none; border: none; color: var(--red-500); font-weight: 600; font-size: 12.5px; cursor: pointer; font-family: inherit; transition: color 0.15s; }
        .act-delete:hover { text-decoration: underline; color: #ef4444; }
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
        <a href="<?= BASEURL ?>/noc/filemanager" class="nav-item active"><span class="icon">📁</span> File Manager</a>
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
            <h1>File Manager</h1>
            <p>Kelola dan edit langsung berkas php kode aplikasi BAKUL</p>
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

        <!-- Path Bar -->
        <div class="path-bar">
            <span>📁 Path:</span>
            <?php
            $rel = str_replace($data['project_root'], '', $data['current_dir']);
            $rel = trim($rel, '/');
            echo '<a class="path-link" href="?dir=' . urlencode($data['project_root']) . '">BAKUL</a>';
            if ($rel) {
                $accum = $data['project_root'];
                $segments = explode('/', $rel);
                foreach ($segments as $segment) {
                    $accum .= '/' . $segment;
                    echo ' &nbsp;/&nbsp; <a class="path-link" href="?dir=' . urlencode($accum) . '">' . htmlspecialchars($segment) . '</a>';
                }
            }
            ?>
        </div>

        <!-- Toolbar Panel -->
        <div class="toolbar">
            <div class="tool-panel">
                <div class="tool-title">📝 Buat Berkas Baru</div>
                <form class="tool-form" method="POST" action="<?= BASEURL ?>/noc/create_file">
                    <input type="hidden" name="dir" value="<?= htmlspecialchars($data['current_dir']) ?>">
                    <input type="text" name="name" placeholder="Nama file/folder (contoh: test.php)" required>
                    <select name="type">
                        <option value="file">File</option>
                        <option value="folder">Folder</option>
                    </select>
                    <button type="submit" class="tool-btn">Buat</button>
                </form>
            </div>
        </div>

        <!-- File Table -->
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Berkas</th>
                        <th>Ukuran</th>
                        <th>Terakhir Diubah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Up Directory -->
                    <?php if ($data['current_dir'] !== $data['project_root']): ?>
                    <tr>
                        <td colspan="4">
                            <a class="file-name" href="?dir=<?= urlencode(dirname($data['current_dir'])) ?>">
                                <span class="icon-file">📁</span>
                                <strong>.. (Direktori Induk)</strong>
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if (empty($data['items'])): ?>
                    <tr>
                        <td colspan="4" style="text-align:center;padding:30px;color:var(--text-muted)">Folder ini kosong.</td>
                    </tr>
                    <?php endif; ?>

                    <?php foreach ($data['items'] as $item): ?>
                    <tr>
                        <td>
                            <?php if ($item['is_dir']): ?>
                            <a class="file-name" href="?dir=<?= urlencode($item['path']) ?>">
                                <span class="icon-file">📁</span>
                                <strong><?= htmlspecialchars($item['name']) ?></strong>
                            </a>
                            <?php else: ?>
                            <?php
                            $ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
                            $editable = in_array($ext, ['php', 'js', 'json', 'css', 'htaccess', 'html', 'txt', 'md', 'env']);
                            if ($editable):
                            ?>
                            <a class="file-name" href="<?= BASEURL ?>/noc/edit_file?file=<?= urlencode($item['path']) ?>">
                                <span class="icon-file">📄</span>
                                <span><?= htmlspecialchars($item['name']) ?></span>
                            </a>
                            <?php else: ?>
                            <div class="file-name" style="cursor:default; hover:none;">
                                <span class="icon-file">⚙️</span>
                                <span style="color:var(--text-muted)"><?= htmlspecialchars($item['name']) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="mono">
                            <?= $item['is_dir'] ? '—' : number_format($item['size']) . ' B' ?>
                        </td>
                        <td class="mono" style="font-size:12px;color:var(--text-muted)">
                            <?= date('Y-m-d H:i:s', $item['modified']) ?>
                        </td>
                        <td>
                            <?php if ($item['is_dir']): ?>
                            <a class="act-link" href="?dir=<?= urlencode($item['path']) ?>">Buka</a>
                            <?php else: ?>
                            <?php if (in_array(strtolower(pathinfo($item['name'], PATHINFO_EXTENSION)), ['php', 'js', 'json', 'css', 'htaccess', 'html', 'txt', 'md', 'env'])): ?>
                            <a class="act-link" href="<?= BASEURL ?>/noc/edit_file?file=<?= urlencode($item['path']) ?>">Edit</a>
                            <?php endif; ?>
                            <?php endif; ?>

                            <!-- Delete Form -->
                            <form method="POST" action="<?= BASEURL ?>/noc/delete_file" style="display:inline" onsubmit="return confirm('Hapus <?= htmlspecialchars($item['name']) ?> permanen?')">
                                <input type="hidden" name="target" value="<?= htmlspecialchars($item['path']) ?>">
                                <button type="submit" class="act-delete">Hapus</button>
                            </form>
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
