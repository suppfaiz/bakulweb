<?php require_once __DIR__ . '/../../layout/header.php'; ?>
<style>
    /* Kunci scroll di halaman login */
    html, body { overflow: hidden !important; height: 100% !important; }
</style>

<style>
.login-page-wrap {
    height: calc(100vh - 64px);
    max-height: calc(100vh - 64px);
    display: flex;
    align-items: stretch;
    background: #fff;
    overflow: hidden;
}

/* ── Left visual panel ── */
.login-visual {
    display: none;
    flex: 1;
    background: #111827;
    position: relative;
    overflow: hidden;
    align-items: center;
    justify-content: center;
    padding: 3rem;
}
@media (min-width: 768px) { .login-visual { display: flex; } }

/* animated dot grid */
.login-visual::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
    background-size: 26px 26px;
}
/* glow orb */
.login-visual::after {
    content: '';
    position: absolute;
    width: 500px; height: 500px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 65%);
    top: -120px; right: -120px;
}

.visual-inner {
    position: relative;
    z-index: 2;
    max-width: 360px;
}

.visual-brand {
    font-size: 2.5rem;
    font-weight: 900;
    color: #fff;
    letter-spacing: -1px;
    margin-bottom: 0.5rem;
}
.visual-brand span { color: #9ca3af; }

.visual-tagline {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.4);
    line-height: 1.7;
    margin-bottom: 2.5rem;
}

/* Trust badges */
.trust-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}
.trust-card {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 14px;
    padding: 1rem 1.1rem;
}
.trust-card .tc-num {
    font-size: 1.3rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    margin-bottom: 0.25rem;
}
.trust-card .tc-label {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.38);
}

/* ── Right form panel ── */
.login-form-panel {
    width: 100%;
    max-width: 480px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 2.5rem 2rem;
    background: #fff;
}
@media (min-width: 768px) { .login-form-panel { padding: 3rem 3.5rem; } }

.form-panel-inner {
    max-width: 360px;
    width: 100%;
    margin: 0 auto;
    animation: liftIn 0.45s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes liftIn {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Heading */
.form-greeting {
    font-size: 0.8rem;
    font-weight: 600;
    color: #9ca3af;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}
.form-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #111827;
    letter-spacing: -0.5px;
    margin-bottom: 0.35rem;
}
.form-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 2rem;
}
.form-subtitle a {
    color: #111827;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.form-subtitle a:hover { color: #374151; }

/* Social login buttons */
.social-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.6rem;
    margin-bottom: 1.5rem;
}
.social-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    padding: 0.7rem 1rem;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    background: #fff;
    font-size: 0.84rem;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    font-family: 'Inter', sans-serif;
    text-decoration: none;
}
.social-btn:hover { border-color: #9ca3af; background: #f9fafb; }
.social-btn img { width: 18px; height: 18px; }

/* Divider */
.or-divider {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    font-size: 0.78rem;
    color: #d1d5db;
}
.or-divider::before, .or-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f3f4f6;
}

/* Form fields */
.field-group { margin-bottom: 1rem; }
.field-label {
    display: block;
    font-size: 0.81rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.4rem;
}
.field-wrap { position: relative; }
.field-icon {
    position: absolute;
    left: 0.95rem;
    top: 50%;
    transform: translateY(-50%);
    color: #d1d5db;
    font-size: 0.8rem;
    pointer-events: none;
    transition: color 0.2s;
}
.field-input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 2.5rem;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.9rem;
    font-family: 'Inter', sans-serif;
    color: #111827;
    background: #fff;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.field-input::placeholder { color: #d1d5db; }
.field-input:focus {
    border-color: #374151;
    box-shadow: 0 0 0 3px rgba(17,24,39,0.07);
}
.field-wrap:focus-within .field-icon { color: #374151; }

.pwd-toggle-btn {
    position: absolute;
    right: 0.85rem;
    top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    color: #d1d5db; cursor: pointer;
    padding: 0; font-size: 0.8rem;
    transition: color 0.2s;
}
.pwd-toggle-btn:hover { color: #6b7280; }

/* Row: remember + forgot */
.form-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.4rem;
    margin-top: 0.25rem;
}
.remember-label {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.83rem;
    color: #6b7280;
    cursor: pointer;
}
.remember-label input[type=checkbox] {
    width: 15px; height: 15px;
    accent-color: #111827;
    cursor: pointer;
}
.forgot-link {
    font-size: 0.83rem;
    color: #6b7280;
    text-decoration: none;
    font-weight: 500;
}
.forgot-link:hover { color: #111827; }

/* Submit */
.btn-submit {
    width: 100%;
    padding: 0.88rem;
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
    transition: background 0.2s, transform 0.15s;
    position: relative;
    overflow: hidden;
}
.btn-submit:hover { background: #1f2937; transform: translateY(-1px); }
.btn-submit:active { transform: translateY(0); }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
.btn-spinner {
    display: none;
    width: 16px; height: 16px;
    border: 2px solid rgba(255,255,255,0.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Register prompt */
.register-prompt {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.85rem;
    color: #9ca3af;
}
.register-prompt a {
    color: #111827;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}

/* Trust marks at bottom */
.trust-marks {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #f3f4f6;
}
.trust-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.72rem;
    color: #d1d5db;
}
.trust-item i { font-size: 0.85rem; }
</style>

<div class="login-page-wrap">

    <!-- Left visual -->
    <div class="login-visual">
        <div class="visual-inner">
            <div class="visual-brand">BAKUL<span>.</span></div>
            <p class="visual-tagline">
                Toko gadget, smartphone, dan aksesoris terlengkap.<br>
                Harga terbaik, pengiriman cepat, garansi resmi.
            </p>
            <div class="trust-grid">
                <div class="trust-card">
                    <div class="tc-num">10K+</div>
                    <div class="tc-label">Pelanggan Aktif</div>
                </div>
                <div class="trust-card">
                    <div class="tc-num">500+</div>
                    <div class="tc-label">Produk Tersedia</div>
                </div>
                <div class="trust-card">
                    <div class="tc-num">4.9★</div>
                    <div class="tc-label">Rating Toko</div>
                </div>
                <div class="trust-card">
                    <div class="tc-num">24/7</div>
                    <div class="tc-label">Layanan Chat</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right form -->
    <div class="login-form-panel">
        <div class="form-panel-inner">

            <p class="form-greeting">Selamat datang kembali</p>
            <h1 class="form-title">Masuk ke Akun</h1>
            <p class="form-subtitle">
                Belum punya akun?
                <a href="<?= BASEURL ?>/auth/register">Daftar gratis sekarang</a>
            </p>

            <!-- Social login (dekoratif) -->
            <div class="social-row">
                <button class="social-btn" type="button" onclick="showToast('Fitur Google Login segera hadir!','info')">
                    <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Google
                </button>
                <button class="social-btn" type="button" onclick="showToast('Fitur Apple Login segera hadir!','info')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#111827"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                    Apple
                </button>
            </div>

            <div class="or-divider">atau masuk dengan email</div>

            <form id="loginForm" action="<?= BASEURL ?>/auth/process_login" method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <div class="field-group">
                    <label class="field-label" for="email-address">Email Address</label>
                    <div class="field-wrap">
                        <i class="fas fa-envelope field-icon"></i>
                        <input class="field-input" id="email-address" name="email" type="email"
                               autocomplete="email" required placeholder="nama@email.com">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-wrap">
                        <i class="fas fa-lock field-icon"></i>
                        <input class="field-input" id="password" name="password" type="password"
                               autocomplete="current-password" required placeholder="••••••••">
                        <button type="button" class="pwd-toggle-btn" onclick="toggleLoginPwd()">
                            <i class="fas fa-eye" id="loginPwdIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-meta-row">
                    <label class="remember-label">
                        <input type="checkbox" id="remember-me" name="remember-me">
                        Ingat saya
                    </label>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>

                <button type="submit" class="btn-submit" id="loginSubmitBtn">
                    <span class="btn-spinner" id="loginSpinner"></span>
                    <i class="fas fa-sign-in-alt" id="loginBtnIcon"></i>
                    <span id="loginBtnText">Sign in</span>
                </button>
            </form>

            <p class="register-prompt">
                Belum punya akun? <a href="<?= BASEURL ?>/auth/register">Daftar sekarang</a>
            </p>

            <!-- Trust marks -->
            <div class="trust-marks">
                <span class="trust-item"><i class="fas fa-lock"></i> SSL Aman</span>
                <span class="trust-item"><i class="fas fa-shield-alt"></i> Data Terlindungi</span>
                <span class="trust-item"><i class="fas fa-user-shield"></i> Privasi Terjaga</span>
            </div>

        </div>
    </div>

</div>

<script>
function toggleLoginPwd() {
    const pwd  = document.getElementById('password');
    const icon = document.getElementById('loginPwdIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

document.getElementById('loginForm').addEventListener('submit', function() {
    const btn    = document.getElementById('loginSubmitBtn');
    const spinner = document.getElementById('loginSpinner');
    const icon   = document.getElementById('loginBtnIcon');
    const text   = document.getElementById('loginBtnText');
    btn.disabled = true;
    spinner.style.display = 'inline-block';
    icon.style.display    = 'none';
    text.textContent      = 'Memverifikasi...';
});
</script>

<?php /* Footer disembunyikan di halaman login */ ?>
