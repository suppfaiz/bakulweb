<?php
// NOC Login View — Light Premium Theme
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOC Login | BAKUL Security Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }        :root {
            --white: #111827;
            --gray-50: #090d16;
            --gray-100: #1f2937;
            --gray-200: rgba(56, 189, 248, 0.15);
            --gray-400: #94a3b8;
            --gray-600: #64748b;
            --gray-800: #cbd5e1;
            --gray-900: #f8fafc;
            --blue-500: #38bdf8;
            --blue-600: #0284c7;
            --blue-700: #0369a1;
            --blue-50:  rgba(56, 189, 248, 0.08);
            --blue-100: rgba(56, 189, 248, 0.25);
            --red-50:   rgba(248, 113, 113, 0.08);
            --red-500:  #f87171;
            --red-600:  #ef4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-50);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            color: var(--gray-900);
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at 20% 20%, rgba(56,189,248,.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(56,189,248,.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .grid-bg {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(56,189,248,.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(56,189,248,.02) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 24px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .shield-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--blue-500), var(--blue-700));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 28px;
            box-shadow: 0 0 20px rgba(56,189,248,.35);
        }

        .login-header h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -.3px;
        }

        .login-header p {
            font-size: 13.5px;
            color: var(--gray-400);
            margin-top: 6px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--blue-50);
            color: var(--blue-500);
            border: 1px solid var(--blue-100);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-top: 12px;
        }

        .badge::before {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--blue-500);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .4; }
        }

        .card {
            background: rgba(17, 24, 39, 0.75);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            padding: 36px 32px;
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px 0 rgba(0,0,0,0.37);
        }

        .error-box {
            background: var(--red-50);
            border: 1px solid rgba(248,113,113,.2);
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 13.5px;
            color: var(--red-500);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 7px;
            letter-spacing: .1px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 16px;
            pointer-events: none;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid var(--gray-200);
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: var(--gray-900);
            background: #0b0f19;
            transition: all .2s;
            outline: none;
        }

        input[type="password"]:focus {
            border-color: var(--blue-500);
            background: #0f172a;
            box-shadow: 0 0 10px rgba(56,189,248,.25);
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--blue-600), var(--blue-700));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all .2s;
            letter-spacing: .2px;
            box-shadow: 0 4px 16px rgba(56,189,248,.15);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(56,189,248,.25);
        }

        .btn-login:active { transform: translateY(0); }

        .footer-info {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: var(--gray-400);
        }

        .footer-info a {
            color: var(--blue-500);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="grid-bg"></div>
    <div class="login-wrapper">
        <div class="login-header">
            <div class="shield-icon">🛡️</div>
            <h1>BAKUL Security NOC</h1>
            <p>Network Operations Center — Akses terbatas</p>
            <div class="badge">Secure Access</div>
        </div>

        <div class="card">
            <?php if (!empty($data['error'])): ?>
            <div class="error-box">
                <span>⚠️</span>
                <span><?= htmlspecialchars($data['error']) ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASEURL ?>/noc/login">
                <div class="form-group">
                    <label for="noc_password">NOC Password</label>
                    <div class="input-wrap">
                        <span class="icon">🔑</span>
                        <input type="password" id="noc_password" name="password" placeholder="Masukkan password NOC" required autofocus>
                    </div>
                </div>
                <button type="submit" class="btn-login">Masuk ke NOC Dashboard</button>
            </form>
        </div>

        <div class="footer-info">
            <a href="<?= BASEURL ?>">← Kembali ke BAKUL</a>
            &nbsp;·&nbsp; NOC Panel v1.0
        </div>
    </div>
</body>
</html>
