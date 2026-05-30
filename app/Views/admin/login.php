<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['judul']) ? $data['judul'] : 'Login Admin | BAKUL Enterprise' ?></title>
    <meta name="description" content="Halaman login khusus admin dan staf BAKUL Enterprise ERP Dashboard.">
    <link rel="icon" type="image/png" href="<?= BASEURL ?>/favicon.png">
    <link rel="apple-touch-icon" href="<?= BASEURL ?>/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #ffffff;
            color: #111827;
        }

        /* ── Left decorative panel ── */
        .left-panel {
            display: none;
            width: 44%;
            background: #111827;
            position: relative;
            overflow: hidden;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 3rem;
        }
        @media (min-width: 900px) { .left-panel { display: flex; } }

        /* subtle dot grid */
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        /* gradient glow bottom-right */
        .left-panel::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            bottom: -120px; right: -120px;
            pointer-events: none;
        }

        .left-content { position: relative; z-index: 2; }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 99px;
            padding: 0.35rem 0.9rem;
            font-size: 0.72rem;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 2rem;
        }

        .left-title {
            font-size: clamp(1.9rem, 3vw, 2.7rem);
            font-weight: 800;
            color: #ffffff;
            line-height: 1.2;
            letter-spacing: -0.5px;
            margin-bottom: 1rem;
        }
        .left-title .accent { color: #9ca3af; }

        .left-desc {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.38);
            line-height: 1.75;
            max-width: 320px;
            margin-bottom: 2.5rem;
        }

        .feature-list { list-style: none; display: flex; flex-direction: column; gap: 0.8rem; }
        .feature-list li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.86rem;
            color: rgba(255,255,255,0.45);
        }
        .feat-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.5);
            flex-shrink: 0;
        }

        /* ── Right login panel ── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            background: #ffffff;
        }

        .login-box {
            width: 100%;
            max-width: 390px;
            animation: fadeUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Logo */
        .logo-row {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 2.25rem;
        }
        .logo-icon {
            width: 40px; height: 40px;
            background: #111827;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-icon i { color: #fff; font-size: 0.95rem; }
        .logo-text { font-size: 1.2rem; font-weight: 800; color: #111827; letter-spacing: -0.3px; }
        .logo-text .sub { font-weight: 400; color: #9ca3af; }

        /* Headings */
        .heading    { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 0.35rem; }
        .subheading { font-size: 0.875rem; color: #6b7280; margin-bottom: 1.75rem; }

        /* Form */
        .form-group { margin-bottom: 1.1rem; }

        .form-label {
            display: block;
            font-size: 0.81rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.45rem;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: #d1d5db;
            font-size: 0.82rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            padding: 0.78rem 1rem 0.78rem 2.5rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            color: #111827;
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input::placeholder { color: #d1d5db; }
        .form-input:focus {
            border-color: #374151;
            box-shadow: 0 0 0 3px rgba(17,24,39,0.08);
        }
        .input-wrap:focus-within .input-icon { color: #374151; }

        .pwd-toggle {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #d1d5db;
            cursor: pointer;
            padding: 0;
            font-size: 0.82rem;
            transition: color 0.2s;
        }
        .pwd-toggle:hover { color: #6b7280; }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: #111827;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.92rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-login:hover   { background: #1f2937; transform: translateY(-1px); }
        .btn-login:active  { transform: translateY(0); }
        .btn-login:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .spinner {
            display: none;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Divider */
        .divider {
            text-align: center;
            position: relative;
            margin: 1.4rem 0;
            font-size: 0.76rem;
            color: #d1d5db;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            height: 1px;
            background: #f3f4f6;
            width: calc(50% - 1.8rem);
        }
        .divider::before { left: 0; }
        .divider::after  { right: 0; }

        /* Back link */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.84rem;
            color: #6b7280;
            text-decoration: none;
            padding: 0.65rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .back-link:hover { border-color: #9ca3af; color: #374151; background: #f9fafb; }

        /* Security note */
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            margin-top: 1.5rem;
            font-size: 0.71rem;
            color: #e5e7eb;
        }
        .security-note i { font-size: 0.68rem; }
    </style>
</head>
<body>

<!-- Left panel -->
<div class="left-panel">
    <div class="left-content">
        <div class="brand-badge">
            <i class="fas fa-shield-alt"></i> Admin Portal
        </div>
        <h1 class="left-title">BAKUL<br><span class="accent">Enterprise ERP</span></h1>
        <p class="left-desc">
            Platform manajemen bisnis terpadu untuk mengelola produk, pesanan, keuangan, dan analisa penjualan.
        </p>
        <ul class="feature-list">
            <li>
                <span class="feat-icon"><i class="fas fa-boxes"></i></span>
                Manajemen Produk &amp; Inventori
            </li>
            <li>
                <span class="feat-icon"><i class="fas fa-chart-line"></i></span>
                Laporan Keuangan &amp; Analisa
            </li>
            <li>
                <span class="feat-icon"><i class="fas fa-undo-alt"></i></span>
                Sistem Refund &amp; Rekonsiliasi
            </li>
            <li>
                <span class="feat-icon"><i class="fas fa-bullhorn"></i></span>
                Auto Promosi &amp; Banner
            </li>
        </ul>
    </div>
</div>

<!-- Right panel -->
<div class="right-panel">
    <div class="login-box">

        <div class="logo-row">
            <div class="logo-icon"><i class="fas fa-layer-group"></i></div>
            <div class="logo-text">BAKUL <span class="sub">ERP</span></div>
        </div>

        <h1 class="heading">Selamat Datang</h1>
        <p class="subheading">Masuk ke dashboard admin &amp; staf</p>

        <!-- Flash message (rendered via Flasher::flash() → showToast) -->
        <?php Flasher::flash(); ?>

        <form id="adminLoginForm" action="<?= BASEURL ?>/admin/process_login" method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope input-icon"></i>
                    <input class="form-input" type="email" id="email" name="email"
                           placeholder="admin@bakul.com" autocomplete="email" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock input-icon"></i>
                    <input class="form-input" type="password" id="password" name="password"
                           placeholder="••••••••" autocomplete="current-password" required>
                    <button type="button" class="pwd-toggle" onclick="togglePwd()">
                        <i class="fas fa-eye" id="pwdIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                <span class="spinner" id="loginSpinner"></span>
                <i class="fas fa-sign-in-alt" id="loginIcon"></i>
                <span id="loginText">Masuk ke Dashboard</span>
            </button>
        </form>

        <div class="divider">atau</div>

        <a href="<?= BASEURL ?>/auth/login" class="back-link">
            <i class="fas fa-store"></i>
            Kembali ke Portal Pelanggan
        </a>

        <div class="security-note">
            <i class="fas fa-lock"></i>
            Dilindungi enkripsi SSL &amp; CSRF token
        </div>

    </div>
</div>

<!-- Toast container -->
<div id="toastContainer" style="position:fixed;top:1.25rem;right:1.25rem;z-index:9999;display:flex;flex-direction:column;gap:0.5rem;max-width:340px;"></div>

<script>
function togglePwd() {
    const pwd  = document.getElementById('password');
    const icon = document.getElementById('pwdIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('fa-eye','fa-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.replace('fa-eye-slash','fa-eye');
    }
}

document.getElementById('adminLoginForm').addEventListener('submit', function() {
    const btn    = document.getElementById('loginBtn');
    const spin   = document.getElementById('loginSpinner');
    const icon   = document.getElementById('loginIcon');
    const text   = document.getElementById('loginText');
    btn.disabled = true;
    spin.style.display = 'inline-block';
    icon.style.display = 'none';
    text.textContent   = 'Memverifikasi...';
});

/* Flasher toast support */
function showToast(message, type) {
    const styles = {
        success: { bg:'#f0fdf4', border:'#bbf7d0', color:'#166534', icon:'fa-check-circle',        ic:'#16a34a' },
        error:   { bg:'#fef2f2', border:'#fecaca', color:'#991b1b', icon:'fa-exclamation-circle',  ic:'#dc2626' },
        warning: { bg:'#fffbeb', border:'#fde68a', color:'#92400e', icon:'fa-exclamation-triangle', ic:'#d97706' },
        info:    { bg:'#f9fafb', border:'#e5e7eb', color:'#374151', icon:'fa-info-circle',          ic:'#6b7280' },
    };
    const s = styles[type] || styles.info;
    const t = document.createElement('div');
    t.style.cssText = `display:flex;align-items:flex-start;gap:0.65rem;background:${s.bg};border:1px solid ${s.border};
        color:${s.color};padding:0.8rem 1rem;border-radius:10px;font-size:0.84rem;font-family:'Inter',sans-serif;
        line-height:1.5;box-shadow:0 4px 16px rgba(0,0,0,0.08);animation:toastIn 0.3s ease;`;
    t.innerHTML = `<i class="fas ${s.icon}" style="color:${s.ic};margin-top:2px;flex-shrink:0"></i><span>${message}</span>`;
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(() => { t.style.transition = 'opacity 0.4s'; t.style.opacity = '0'; setTimeout(() => t.remove(), 400); }, 4500);
}
</script>
<style>
@keyframes toastIn { from { opacity:0; transform:translateX(16px); } to { opacity:1; transform:translateX(0); } }
</style>
</body>
</html>
