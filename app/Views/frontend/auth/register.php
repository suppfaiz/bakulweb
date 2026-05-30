<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<style>
    /* Kunci scroll halaman register */
    html, body { overflow: hidden !important; height: 100% !important; }

    .reg-wrap {
        height: calc(100vh - 64px);
        max-height: calc(100vh - 64px);
        display: flex;
        align-items: stretch;
        overflow: hidden;
    }

    /* ── Left panel ── */
    .reg-visual {
        display: none;
        width: 42%;
        background: #111827;
        position: relative;
        overflow: hidden;
        flex-direction: column;
        justify-content: center;
        padding: 3rem;
    }
    @media (min-width: 768px) { .reg-visual { display: flex; } }

    .reg-visual::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
        background-size: 26px 26px;
    }
    .reg-visual::after {
        content: '';
        position: absolute;
        width: 500px; height: 500px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 65%);
        bottom: -160px; left: -100px;
    }

    .rv-inner { position: relative; z-index: 2; max-width: 340px; }

    .rv-brand { font-size: 2.5rem; font-weight: 900; color: #fff; letter-spacing: -1px; margin-bottom: 0.5rem; }
    .rv-brand span { color: #9ca3af; }

    .rv-tagline { font-size: 0.88rem; color: rgba(255,255,255,0.38); line-height: 1.75; margin-bottom: 2rem; }

    .rv-steps { display: flex; flex-direction: column; gap: 1rem; }
    .rv-step {
        display: flex;
        align-items: flex-start;
        gap: 0.9rem;
    }
    .step-num {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.72rem;
        font-weight: 700;
        color: rgba(255,255,255,0.5);
        flex-shrink: 0;
        margin-top: 1px;
    }
    .step-body .step-title { font-size: 0.86rem; font-weight: 600; color: rgba(255,255,255,0.75); margin-bottom: 0.15rem; }
    .step-body .step-desc  { font-size: 0.77rem; color: rgba(255,255,255,0.3); }

    /* ── Right form panel ── */
    .reg-form-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        background: #fff;
    }
    /* custom scrollbar */
    .reg-form-panel::-webkit-scrollbar { width: 4px; }
    .reg-form-panel::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

    .reg-form-inner {
        max-width: 480px;
        width: 100%;
        margin: 0 auto;
        padding: 2rem 1.5rem;
        animation: liftIn 0.4s cubic-bezier(0.16,1,0.3,1);
    }
    @media (min-width: 768px) { .reg-form-inner { padding: 2.5rem 3rem; } }

    @keyframes liftIn {
        from { opacity:0; transform:translateY(14px); }
        to   { opacity:1; transform:translateY(0); }
    }

    /* Progress bar */
    .progress-bar-wrap {
        display: flex;
        gap: 0.4rem;
        margin-bottom: 2rem;
    }
    .progress-seg {
        flex: 1;
        height: 4px;
        border-radius: 99px;
        background: #f3f4f6;
        transition: background 0.3s;
    }
    .progress-seg.active { background: #111827; }
    .progress-seg.done   { background: #374151; }

    /* Step labels */
    .step-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.35rem;
    }

    .form-title   { font-size: 1.6rem; font-weight: 800; color: #111827; letter-spacing: -0.4px; margin-bottom: 0.25rem; }
    .form-caption { font-size: 0.87rem; color: #6b7280; margin-bottom: 1.75rem; }
    .form-caption a { color: #111827; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }

    /* Fields */
    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .field-group { margin-bottom: 0.9rem; }
    .field-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
    }
    .field-label .req { color: #ef4444; margin-left: 2px; }
    .field-label .hint { font-weight: 400; color: #9ca3af; font-size: 0.72rem; }

    .field-wrap { position: relative; }
    .field-icon {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: #d1d5db;
        font-size: 0.8rem;
        pointer-events: none;
        transition: color 0.2s;
    }
    .field-icon.top { top: 1rem; transform: none; }

    .field-input, .field-textarea {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.45rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        color: #111827;
        background: #fff;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .field-textarea {
        padding-left: 2.45rem;
        resize: none;
        height: 72px;
    }
    .field-input::placeholder, .field-textarea::placeholder { color: #d1d5db; }

    .field-input:focus, .field-textarea:focus {
        border-color: #374151;
        box-shadow: 0 0 0 3px rgba(17,24,39,0.07);
    }
    .field-input.is-error, .field-textarea.is-error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.08) !important;
    }
    .field-input.is-valid { border-color: #22c55e; }

    .field-wrap:focus-within .field-icon { color: #374151; }

    .pwd-eye {
        position: absolute;
        right: 0.8rem; top: 50%;
        transform: translateY(-50%);
        background: none; border: none;
        color: #d1d5db; cursor: pointer;
        font-size: 0.8rem; transition: color 0.2s;
    }
    .pwd-eye:hover { color: #6b7280; }

    /* Password strength */
    .pwd-strength-bar {
        display: flex;
        gap: 3px;
        margin-top: 0.4rem;
    }
    .ps-seg {
        flex: 1; height: 3px;
        border-radius: 99px;
        background: #f3f4f6;
        transition: background 0.3s;
    }
    .pwd-strength-label { font-size: 0.7rem; color: #9ca3af; margin-top: 0.25rem; }

    .field-error { font-size: 0.75rem; color: #ef4444; margin-top: 0.3rem; display: none; }
    .field-error.show { display: block; }

    /* Confirm field valid icon */
    .valid-icon {
        position: absolute;
        right: 0.8rem; top: 50%;
        transform: translateY(-50%);
        color: #22c55e;
        font-size: 0.8rem;
        display: none;
    }

    /* Checkbox */
    .tos-row {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        margin-bottom: 1rem;
        font-size: 0.83rem;
        color: #6b7280;
    }
    .tos-row input[type=checkbox] { margin-top: 2px; width: 15px; height: 15px; accent-color: #111827; flex-shrink: 0; }
    .tos-row a { color: #111827; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }

    /* Submit btn */
    .btn-reg {
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
        transition: background 0.2s, transform 0.15s;
    }
    .btn-reg:hover { background: #1f2937; transform: translateY(-1px); }
    .btn-reg:active { transform: translateY(0); }
    .btn-reg:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

    .btn-spinner {
        display: none;
        width: 16px; height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .login-link { text-align: center; margin-top: 1.25rem; font-size: 0.84rem; color: #9ca3af; }
    .login-link a { color: #111827; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
</style>

<div class="reg-wrap">

    <!-- Left visual -->
    <div class="reg-visual">
        <div class="rv-inner">
            <div class="rv-brand">BAKUL<span>.</span></div>
            <p class="rv-tagline">
                Bergabung dengan ribuan pelanggan BAKUL.<br>
                Nikmati belanja gadget premium dengan mudah.
            </p>
            <div class="rv-steps">
                <div class="rv-step">
                    <div class="step-num">1</div>
                    <div class="step-body">
                        <div class="step-title">Isi Data Diri</div>
                        <div class="step-desc">Username, email, dan password akun Anda</div>
                    </div>
                </div>
                <div class="rv-step">
                    <div class="step-num">2</div>
                    <div class="step-body">
                        <div class="step-title">Verifikasi Email</div>
                        <div class="step-desc">Kode OTP dikirim ke email untuk aktivasi</div>
                    </div>
                </div>
                <div class="rv-step">
                    <div class="step-num">3</div>
                    <div class="step-body">
                        <div class="step-title">Mulai Belanja</div>
                        <div class="step-desc">Akun aktif, langsung bisa checkout & order</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right form -->
    <div class="reg-form-panel">
        <div class="reg-form-inner">

            <!-- Progress -->
            <div class="progress-bar-wrap">
                <div class="progress-seg active" id="ps1"></div>
                <div class="progress-seg" id="ps2"></div>
                <div class="progress-seg" id="ps3"></div>
            </div>

            <p class="step-label" id="stepLabel">Langkah 1 dari 2 — Informasi Akun</p>
            <h1 class="form-title">Buat Akun Baru</h1>
            <p class="form-caption">
                Sudah punya akun? <a href="<?= BASEURL ?>/auth/login">Masuk di sini</a>
            </p>

            <form id="regForm" action="<?= BASEURL ?>/auth/process_register" method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                <!-- Step 1 fields -->
                <div id="step1">
                    <div class="field-row">
                        <!-- Username -->
                        <div class="field-group">
                            <div class="field-label">
                                Username <span class="req">*</span>
                            </div>
                            <div class="field-wrap">
                                <i class="fas fa-user field-icon"></i>
                                <input class="field-input" type="text" id="username" name="username"
                                       placeholder="john_doe" autocomplete="username">
                            </div>
                            <div class="field-error" id="err-username">Username minimal 3 karakter, hanya huruf, angka, & underscore.</div>
                        </div>
                        <!-- Email -->
                        <div class="field-group">
                            <div class="field-label">Email <span class="req">*</span></div>
                            <div class="field-wrap">
                                <i class="fas fa-envelope field-icon"></i>
                                <input class="field-input" type="email" id="email" name="email"
                                       placeholder="nama@email.com" autocomplete="email">
                            </div>
                            <div class="field-error" id="err-email">Format email tidak valid.</div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="field-group">
                        <div class="field-label">
                            Password <span class="req">*</span>
                            <span class="hint">Min. 8 karakter</span>
                        </div>
                        <div class="field-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input class="field-input" type="password" id="password" name="password"
                                   placeholder="••••••••" autocomplete="new-password">
                            <button type="button" class="pwd-eye" onclick="togglePwd('password','pwdIcon1')">
                                <i class="fas fa-eye" id="pwdIcon1"></i>
                            </button>
                        </div>
                        <div class="pwd-strength-bar" id="pwdStrengthBar">
                            <div class="ps-seg" id="ps-s1"></div>
                            <div class="ps-seg" id="ps-s2"></div>
                            <div class="ps-seg" id="ps-s3"></div>
                            <div class="ps-seg" id="ps-s4"></div>
                        </div>
                        <div class="pwd-strength-label" id="pwdStrengthLabel"></div>
                        <div class="field-error" id="err-password">Password minimal 8 karakter.</div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="field-group">
                        <div class="field-label">Konfirmasi Password <span class="req">*</span></div>
                        <div class="field-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input class="field-input" type="password" id="confirm_password"
                                   placeholder="••••••••" autocomplete="new-password">
                            <button type="button" class="pwd-eye" onclick="togglePwd('confirm_password','pwdIcon2')">
                                <i class="fas fa-eye" id="pwdIcon2"></i>
                            </button>
                            <i class="fas fa-check valid-icon" id="confirmCheck"></i>
                        </div>
                        <div class="field-error" id="err-confirm">Password tidak cocok.</div>
                    </div>

                    <button type="button" class="btn-reg" id="nextBtn" onclick="goStep2()">
                        Lanjut <i class="fas fa-arrow-right" style="font-size:0.8rem"></i>
                    </button>
                </div>

                <!-- Step 2 fields -->
                <div id="step2" style="display:none">
                    <!-- Phone -->
                    <div class="field-group">
                        <div class="field-label">
                            Nomor WhatsApp <span class="req">*</span>
                            <span class="hint">Diawali 08 atau +62</span>
                        </div>
                        <div class="field-wrap">
                            <i class="fas fa-mobile-alt field-icon"></i>
                            <input class="field-input" type="tel" id="phone" name="phone"
                                   placeholder="081234567890" autocomplete="tel">
                        </div>
                        <div class="field-error" id="err-phone">Nomor telepon tidak valid (min 10 digit).</div>
                    </div>

                    <!-- Alamat -->
                    <div class="field-group">
                        <div class="field-label">
                            Alamat Pengiriman <span class="req">*</span>
                            <span class="hint">Lengkap & jelas</span>
                        </div>
                        <div class="field-wrap">
                            <i class="fas fa-map-marker-alt field-icon top"></i>
                            <textarea class="field-textarea" id="address" name="address"
                                      placeholder="Jl. Merdeka No. 1, RT 01/RW 02, Kel. Gambir, Kec. Gambir, Jakarta Pusat 10110"></textarea>
                        </div>
                        <div class="field-error" id="err-address">Alamat minimal 20 karakter.</div>
                    </div>

                    <!-- TOS -->
                    <div class="tos-row">
                        <input type="checkbox" id="tos" name="tos">
                        <label for="tos">
                            Saya setuju dengan <a href="<?= BASEURL ?>/help/terms" target="_blank">Syarat &amp; Ketentuan</a>
                            dan <a href="<?= BASEURL ?>/help/privacy" target="_blank">Kebijakan Privasi</a> BAKUL.
                        </label>
                    </div>
                    <div class="field-error" id="err-tos">Anda harus menyetujui syarat & ketentuan.</div>

                    <div style="display:flex;gap:0.6rem">
                        <button type="button" onclick="goStep1()"
                                style="flex:0 0 auto;padding:0.85rem 1.25rem;border:1.5px solid #e5e7eb;border-radius:10px;background:#fff;font-family:'Inter',sans-serif;font-size:0.9rem;color:#374151;cursor:pointer;transition:all 0.2s"
                                onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                            <i class="fas fa-arrow-left" style="font-size:0.8rem"></i> Kembali
                        </button>
                        <button type="submit" class="btn-reg" id="submitBtn" style="flex:1">
                            <span class="btn-spinner" id="regSpinner"></span>
                            <i class="fas fa-user-plus" id="regIcon"></i>
                            <span id="regText">&nbsp;Daftar Sekarang</span>
                        </button>
                    </div>
                </div>

            </form>

            <p class="login-link">
                Sudah punya akun? <a href="<?= BASEURL ?>/auth/login">Masuk sekarang</a>
            </p>

        </div>
    </div>
</div>

<script>
/* ── Helpers ── */
function togglePwd(id, iconId) {
    const el = document.getElementById(id);
    const ic = document.getElementById(iconId);
    if (el.type === 'password') { el.type = 'text'; ic.classList.replace('fa-eye','fa-eye-slash'); }
    else                        { el.type = 'password'; ic.classList.replace('fa-eye-slash','fa-eye'); }
}

function setError(inputEl, errId, show) {
    const err = document.getElementById(errId);
    if (show) {
        inputEl.classList.add('is-error');
        inputEl.classList.remove('is-valid');
        err.classList.add('show');
    } else {
        inputEl.classList.remove('is-error');
        err.classList.remove('show');
    }
}

/* ── Password strength ── */
const pwdInput = document.getElementById('password');
pwdInput.addEventListener('input', function() {
    const v = this.value;
    let score = 0;
    if (v.length >= 8)             score++;
    if (/[A-Z]/.test(v))          score++;
    if (/[0-9]/.test(v))          score++;
    if (/[^A-Za-z0-9]/.test(v))   score++;

    const segs  = ['ps-s1','ps-s2','ps-s3','ps-s4'];
    const colors = ['#ef4444','#f59e0b','#22c55e','#16a34a'];
    const labels = ['','Lemah','Sedang','Kuat','Sangat Kuat'];

    segs.forEach((id, i) => {
        document.getElementById(id).style.background = i < score ? colors[score-1] : '#f3f4f6';
    });
    document.getElementById('pwdStrengthLabel').textContent = v.length ? labels[score] : '';
    document.getElementById('pwdStrengthLabel').style.color = score > 0 ? colors[score-1] : '#9ca3af';

    // live confirm check
    checkConfirm();
});

/* ── Confirm password ── */
const confirmInput = document.getElementById('confirm_password');
function checkConfirm() {
    const match = confirmInput.value && confirmInput.value === pwdInput.value;
    const checkIcon = document.getElementById('confirmCheck');
    checkIcon.style.display = match ? 'block' : 'none';
    if (confirmInput.value) {
        confirmInput.classList.toggle('is-valid', match);
        confirmInput.classList.toggle('is-error', !match);
    }
}
confirmInput.addEventListener('input', checkConfirm);

/* ── Step navigation ── */
function goStep2() {
    let valid = true;

    // Username
    const username = document.getElementById('username');
    const uVal = username.value.trim();
    const uOk = /^[a-zA-Z0-9_]{3,}$/.test(uVal);
    setError(username, 'err-username', !uOk);
    if (!uOk) valid = false;

    // Email
    const email = document.getElementById('email');
    const eOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim());
    setError(email, 'err-email', !eOk);
    if (!eOk) valid = false;

    // Password
    const pwd = document.getElementById('password');
    const pOk = pwd.value.length >= 8;
    setError(pwd, 'err-password', !pOk);
    if (!pOk) valid = false;

    // Confirm
    const conf = document.getElementById('confirm_password');
    const cOk = conf.value === pwd.value && conf.value.length > 0;
    setError(conf, 'err-confirm', !cOk);
    if (!cOk) valid = false;

    if (!valid) return;

    // Go step 2
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
    document.getElementById('ps1').classList.add('done');
    document.getElementById('ps2').classList.add('active');
    document.getElementById('stepLabel').textContent = 'Langkah 2 dari 2 — Kontak & Alamat';
}

function goStep1() {
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1').style.display = 'block';
    document.getElementById('ps2').classList.remove('active');
    document.getElementById('ps1').classList.remove('done');
    document.getElementById('ps1').classList.add('active');
    document.getElementById('stepLabel').textContent = 'Langkah 1 dari 2 — Informasi Akun';
}

/* ── Submit validation ── */
document.getElementById('regForm').addEventListener('submit', function(e) {
    let valid = true;

    // Phone
    const phone = document.getElementById('phone');
    const phoneVal = phone.value.trim().replace(/\s+/g,'');
    const phoneOk = /^(\+62|0)[0-9]{9,13}$/.test(phoneVal);
    setError(phone, 'err-phone', !phoneOk);
    if (!phoneOk) valid = false;

    // Address
    const addr = document.getElementById('address');
    const addrOk = addr.value.trim().length >= 20;
    setError(addr, 'err-address', !addrOk);
    if (!addrOk) valid = false;

    // TOS
    const tos = document.getElementById('tos');
    const tosErr = document.getElementById('err-tos');
    if (!tos.checked) {
        tosErr.classList.add('show');
        valid = false;
    } else {
        tosErr.classList.remove('show');
    }

    if (!valid) { e.preventDefault(); return; }

    // Loading state
    const btn = document.getElementById('submitBtn');
    const sp  = document.getElementById('regSpinner');
    const ic  = document.getElementById('regIcon');
    const tx  = document.getElementById('regText');
    btn.disabled = true;
    sp.style.display = 'inline-block';
    ic.style.display = 'none';
    tx.textContent   = ' Mendaftarkan...';
});

/* ── Live validation on blur ── */
document.getElementById('username').addEventListener('blur', function() {
    const ok = /^[a-zA-Z0-9_]{3,}$/.test(this.value.trim());
    setError(this, 'err-username', !ok && this.value.length > 0);
});
document.getElementById('email').addEventListener('blur', function() {
    const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value.trim());
    setError(this, 'err-email', !ok && this.value.length > 0);
});
document.getElementById('phone').addEventListener('blur', function() {
    const v = this.value.trim().replace(/\s+/g,'');
    const ok = /^(\+62|0)[0-9]{9,13}$/.test(v);
    setError(this, 'err-phone', !ok && this.value.length > 0);
});
document.getElementById('address').addEventListener('blur', function() {
    const ok = this.value.trim().length >= 20;
    setError(this, 'err-address', !ok && this.value.length > 0);
});
</script>

<?php /* Footer disembunyikan di halaman register */ ?>
