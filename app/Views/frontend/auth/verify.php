<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-white dark:bg-darkbg py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-darkcard p-10 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-800">
        <div>
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 text-primary">
                <i class="fas fa-envelope-open-text text-xl"></i>
            </div>
            <h2 class="mt-4 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                Verifikasi Email Anda
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Kami telah mengirimkan kode OTP 6-digit ke alamat email:
            </p>
            <p class="mt-1 text-center text-sm font-semibold text-primary">
                <?= htmlspecialchars($data['email']); ?>
            </p>
        </div>

        <form class="mt-8 space-y-6" action="<?= BASEURL; ?>/auth/process_verify" method="POST">
            <div class="rounded-md space-y-5">
                <div>
                    <label for="verification-code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-center">Masukkan 6-Digit Kode Verifikasi</label>
                    <input id="verification-code" name="code" type="text" pattern="[0-9]{6}" maxlength="6" required autocomplete="one-time-code" class="appearance-none rounded-xl relative block w-full px-4 py-4 border border-gray-300 dark:border-gray-600 placeholder-gray-300 text-gray-900 dark:text-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-center text-3xl font-bold tracking-[0.5em] sm:text-3xl transition-all" placeholder="000000">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-medium rounded-full text-white bg-primary hover:bg-sky-700 shadow hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all">
                    Verifikasi Akun
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Tidak menerima kode? 
                <a href="<?= BASEURL; ?>/auth/resend_code" class="font-medium text-primary hover:text-sky-500 transition ml-1 inline-flex items-center">
                    <i class="fas fa-sync-alt mr-1 text-xs"></i> Kirim Ulang Kode
                </a>
            </p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-4 italic">
                *Tip: Untuk pengujian lokal, Anda dapat melihat kode OTP di file log <code class="bg-gray-100 dark:bg-gray-750 px-1.5 py-0.5 rounded font-mono text-[10px] text-gray-700 dark:text-gray-300">public/logs/email_verification.log</code>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
