<?php // NOC — Live Code Editor View ?>
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
        .content { padding: 24px 28px; max-width: 1300px; display: flex; flex-direction: column; gap: 16px; height: calc(100vh - 65px); }

        .flash-msg { padding: 10px 16px; border-radius: 8px; font-size: 13px; }
        .flash-msg.success { background: rgba(74, 222, 128, 0.08); border: 1px solid rgba(74, 222, 128, 0.2); color: #4ade80; }
        .flash-msg.error { background: var(--red-50); border: 1px solid rgba(248, 113, 113, 0.2); color: var(--red-500); }

        /* ─── Editor Window ─── */
        .editor-container { flex: 1; display: flex; flex-direction: column; background: #0b0f19; border-radius: 12px; overflow: hidden; border: 1px solid rgba(56, 189, 248, 0.25); box-shadow: 0 0 25px rgba(56, 189, 248, 0.1); }
        .editor-header { background: var(--white); padding: 12px 18px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-light); }
        .editor-title { color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px; }
        .editor-tag { background: var(--blue-500); color: #090d16; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; }
        
        .editor-body { flex: 1; display: flex; position: relative; }
        .editor-textarea { flex: 1; width: 100%; border: none; outline: none; background: transparent; color: #f8fafc; font-family: 'JetBrains Mono', monospace; font-size: 13.5px; padding: 20px; line-height: 1.6; resize: none; overflow-y: auto; tab-size: 4; }
        .editor-textarea::-webkit-scrollbar { width: 8px; }
        .editor-textarea::-webkit-scrollbar-track { background: transparent; }
        .editor-textarea::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }

        .editor-footer { background: var(--white); padding: 12px 18px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-light); }
        
        .btn-save { padding: 8px 18px; background: var(--green-500); color: #090d16; border: none; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(74, 222, 128, .3); }
        .btn-save:hover { background: #22c55e; }
        .btn-cancel { color: var(--text-secondary); text-decoration: none; font-size: 13px; font-weight: 600; font-family: 'Inter', sans-serif; transition: color .15s; }
        .btn-cancel:hover { color: var(--text-primary); }
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
            <h1>Kode Editor Live</h1>
            <p>Ubah berkas aplikasi Anda secara langsung di server secara terisolasi</p>
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

        <!-- Editor Form -->
        <form class="editor-container" method="POST" action="<?= BASEURL ?>/noc/save_file">
            <input type="hidden" name="file" value="<?= htmlspecialchars($data['file_path']) ?>">
            
            <div class="editor-header">
                <div class="editor-title">
                    <span class="editor-tag"><?= strtoupper(pathinfo($data['file_name'], PATHINFO_EXTENSION)) ?></span>
                    <span><?= htmlspecialchars($data['file_name']) ?></span>
                </div>
                <div style="font-size:11.5px;color:#94a3b8;font-family:'JetBrains Mono',monospace;">
                    <?= htmlspecialchars(str_replace(dirname($data['file_path'], 2), '..', $data['file_path'])) ?>
                </div>
            </div>
            
            <div class="editor-body">
                <textarea class="editor-textarea" name="content" autofocus spellcheck="false" placeholder="Ketik kode di sini..."><?= htmlspecialchars($data['content']) ?></textarea>
            </div>
            
            <div class="editor-footer">
                <a class="btn-cancel" href="<?= BASEURL ?>/noc/filemanager?dir=<?= urlencode($data['current_dir']) ?>">← Batal & Kembali</a>
                <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</main>

<script>
    // Tab key indentation support in textarea
    const textarea = document.querySelector('.editor-textarea');
    if (textarea) {
        textarea.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                e.preventDefault();
                const start = this.selectionStart;
                const end = this.selectionEnd;
                this.value = this.value.substring(0, start) + "\t" + this.value.substring(end);
                this.selectionStart = this.selectionEnd = start + 1;
            }
        });
    }
</script>
</body>
</html>
