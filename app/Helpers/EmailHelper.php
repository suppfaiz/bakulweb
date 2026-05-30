<?php
// app/Helpers/EmailHelper.php

class EmailHelper {
    public static function sendVerificationCode($email, $code) {
        $subject = "Verifikasi Akun - BAKUL Enterprise";
        $message = "Halo,\n\nTerima kasih telah mendaftar di BAKUL Enterprise.\n\nBerikut adalah kode verifikasi akun Anda: $code\n\nSilakan masukkan kode ini pada halaman verifikasi untuk mengaktifkan akun Anda.\n\nSalam,\nBAKUL Enterprise";
        
        // Log to file for local testing
        $logDir = __DIR__ . '/../../public/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . '/email_verification.log';
        $logEntry = "[" . date('Y-m-d H:i:s') . "] To: $email\nSubject: $subject\nMessage:\n$message\n----------------------------------------\n\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        // Send actual email (using native mail function)
        $headers = "From: no-reply@bakul.com\r\n" .
                   "Reply-To: no-reply@bakul.com\r\n" .
                   "X-Mailer: PHP/" . phpversion();
        
        @mail($email, $subject, $message, $headers);
        
        return true;
    }
}
