<?php // NOC — Web Terminal View ?>
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
        .content { padding: 24px 28px; display: flex; gap: 20px; max-width: 1400px; height: calc(100vh - 65px); }

        /* ─── Terminal Layout ─── */
        .console-container { flex: 1; display: flex; flex-direction: column; background: #0b0f19; border-radius: 14px; overflow: hidden; box-shadow: 0 0 25px rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.25); }
        .console-header { background: var(--white); padding: 12px 16px; display: flex; align-items: center; border-bottom: 1px solid var(--border-light); }
        .window-dots { display: flex; gap: 6px; }
        .dot { width: 11px; height: 11px; border-radius: 50%; }
        .dot.red { background: #ef4444; }
        .dot.yellow { background: #f59e0b; }
        .dot.green { background: #10b981; }
        .window-title { margin: 0 auto; color: var(--text-secondary); font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 500; }
        
        .console-body { flex: 1; padding: 20px; overflow-y: auto; font-family: 'JetBrains Mono', monospace; font-size: 13.5px; color: #f8fafc; line-height: 1.5; }
        .console-body::-webkit-scrollbar { width: 6px; }
        .console-body::-webkit-scrollbar-track { background: transparent; }
        .console-body::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
        
        .output-line { white-space: pre-wrap; word-break: break-all; margin-bottom: 10px; }
        .input-line { display: flex; align-items: center; gap: 8px; margin-top: 10px; }
        .prompt { color: #38bdf8; font-weight: 600; white-space: nowrap; }
        .input-cmd { flex: 1; background: transparent; border: none; outline: none; color: #f8fafc; font-family: 'JetBrains Mono', monospace; font-size: 13.5px; }

        /* ─── Sidebar Quick Actions ─── */
        .sidebar-actions { width: 280px; display: flex; flex-direction: column; gap: 16px; }
        .panel { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 20px; }
        .panel-title { font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .actions-list { display: flex; flex-direction: column; gap: 8px; }
        
        .act-btn { padding: 9px 12px; background: var(--bg); border: 1.5px solid var(--border); border-radius: 8px; color: var(--text-secondary); font-size: 13px; font-weight: 600; text-align: left; cursor: pointer; transition: all .15s; font-family: 'JetBrains Mono', monospace; display: flex; justify-content: space-between; align-items: center; }
        .act-btn:hover { background: var(--blue-50); border-color: var(--blue-500); color: var(--blue-500); }
        .act-btn span.cmd-preview { font-size: 11px; color: var(--text-muted); font-weight: 400; }
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
        <a href="<?= BASEURL ?>/noc/filemanager" class="nav-item"><span class="icon">📁</span> File Manager</a>
        <a href="<?= BASEURL ?>/noc/terminal" class="nav-item active"><span class="icon">💻</span> Web Console</a>
    </nav>
    <div class="sidebar-footer">
        <a href="<?= BASEURL ?>/admin" target="_blank"><span>⚙️</span> Admin Panel</a>
        <a href="<?= BASEURL ?>/noc/logout"><span>🚪</span> Logout NOC</a>
    </div>
</aside>

<main class="main">
    <div class="topbar">
        <div>
            <h1>Web Console</h1>
            <p>Jalankan command-line administrasi server secara interaktif</p>
        </div>
    </div>

    <div class="content">
        <!-- Terminal Body -->
        <div class="console-container" onclick="focusInput()">
            <div class="console-header">
                <div class="window-dots">
                    <div class="dot red"></div>
                    <div class="dot yellow"></div>
                    <div class="dot green"></div>
                </div>
                <div class="window-title">console@bakul: <span id="path-indicator">~</span></div>
            </div>
            
            <div class="console-body" id="consoleBody">
                <div class="output-line" style="color: #94a3b8;">Welcome to BAKUL Security Web Console v1.0.
Ketik perintah dan tekan Enter. Gunakan tombol di samping untuk aksi cepat.
---</div>
                <div id="output-history"></div>
                
                <div class="input-line">
                    <span class="prompt" id="prompt-prefix">bakul@server:~$</span>
                    <input type="text" class="input-cmd" id="inputCmd" autofocus autocomplete="off" spellcheck="false">
                </div>
            </div>
        </div>

        <!-- Sidebar Quick Commands -->
        <div class="sidebar-actions">
            <div class="panel">
                <div class="panel-title">⚡ Perintah Cepat</div>
                <div class="actions-list">
                    <button class="act-btn" onclick="sendQuickCmd('ls -la')">📋 List Files <span class="cmd-preview">ls -la</span></button>
                    <button class="act-btn" onclick="sendQuickCmd('df -h')">💾 Disk Space <span class="cmd-preview">df -h</span></button>
                    <button class="act-btn" onclick="sendQuickCmd('free -m')">🧠 Memory Free <span class="cmd-preview">free -m</span></button>
                    <button class="act-btn" onclick="sendQuickCmd('php -v')">⚙️ PHP Version <span class="cmd-preview">php -v</span></button>
                    <button class="act-btn" onclick="sendQuickCmd('git status')">🌿 Git Status <span class="cmd-preview">git status</span></button>
                    <button class="act-btn" onclick="sendQuickCmd('uptime')">🕒 Up Time <span class="cmd-preview">uptime</span></button>
                    <button class="act-btn" onclick="sendQuickCmd('whoami')">👤 Current User <span class="cmd-preview">whoami</span></button>
                    <button class="act-btn" onclick="clearConsole()" style="background:rgba(239, 68, 68, 0.1); border-color:rgba(239, 68, 68, 0.3); color:#f87171;">🗑️ Bersihkan Screen</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    let currentCwd = '<?= addslashes($data['initial_cwd']) ?>';
    const history = [];
    let historyIdx = -1;

    const consoleBody = document.getElementById('consoleBody');
    const outputHistory = document.getElementById('output-history');
    const inputCmd = document.getElementById('inputCmd');
    const promptPrefix = document.getElementById('prompt-prefix');
    const pathIndicator = document.getElementById('path-indicator');

    // Update prompt text and header
    function updatePrompt() {
        // Show last part of directory for prompt, full path in header
        let parts = currentCwd.split('/');
        let folderName = parts[parts.length - 1] || '/';
        promptPrefix.textContent = `bakul@server:${folderName}$`;
        pathIndicator.textContent = currentCwd;
    }
    updatePrompt();

    // Auto-focus input when clicking inside console
    function focusInput() {
        inputCmd.focus();
    }

    // Input command listener
    inputCmd.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const cmd = inputCmd.value.trim();
            if (cmd) {
                executeCommand(cmd);
                history.push(cmd);
                historyIdx = history.length;
            }
            inputCmd.value = '';
        } else if (e.key === 'ArrowUp') {
            if (historyIdx > 0) {
                historyIdx--;
                inputCmd.value = history[historyIdx];
            }
            e.preventDefault();
        } else if (e.key === 'ArrowDown') {
            if (historyIdx < history.length - 1) {
                historyIdx++;
                inputCmd.value = history[historyIdx];
            } else {
                historyIdx = history.length;
                inputCmd.value = '';
            }
            e.preventDefault();
        }
    });

    // Execute command via Ajax
    function executeCommand(cmd) {
        // Append input line to history
        appendLine(`<span style="color: #38bdf8; font-weight: 600;">${promptPrefix.textContent}</span> <span style="color: #f8fafc;">${escapeHtml(cmd)}</span>`);
        
        if (cmd.toLowerCase() === 'clear') {
            clearConsole();
            return;
        }

        appendLine(`<span style="color: #64748b;">Running command...</span>`, 'status-temp');

        const formData = new FormData();
        formData.append('command', cmd);
        formData.append('cwd', currentCwd);

        fetch('<?= BASEURL ?>/noc/execute_command', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            // Remove status temp line
            const temp = document.getElementById('status-temp');
            if (temp) temp.remove();

            if (data.output) {
                appendLine(escapeHtml(data.output));
            } else if (data.output === '') {
                appendLine('<span style="color:#64748b;">(no output)</span>');
            }
            
            if (data.cwd) {
                currentCwd = data.cwd;
                updatePrompt();
            }
            scrollToBottom();
        })
        .catch(err => {
            const temp = document.getElementById('status-temp');
            if (temp) temp.remove();
            appendLine(`<span style="color: #ef4444;">Error: Gagal terhubung ke command runner API.</span>`);
            scrollToBottom();
        });
    }

    function sendQuickCmd(cmd) {
        executeCommand(cmd);
    }

    function clearConsole() {
        outputHistory.innerHTML = '';
        inputCmd.value = '';
        focusInput();
    }

    function appendLine(html, id = '') {
        const line = document.createElement('div');
        line.className = 'output-line';
        if (id) line.id = id;
        line.innerHTML = html;
        outputHistory.appendChild(line);
        scrollToBottom();
    }

    function scrollToBottom() {
        consoleBody.scrollTop = consoleBody.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
</body>
</html>
