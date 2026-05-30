<?php

class Auth {

    // ─── Customer / Frontend ──────────────────────────────────────────────────

    /**
     * Pastikan user sudah login sebagai customer (bukan admin/staf/gudang).
     * Digunakan di halaman frontend.
     */
    public static function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            Flasher::setFlash('Akses', 'ditolak. Silakan login terlebih dahulu.', 'red');
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
    }

    /**
     * Cek role tertentu (untuk keperluan umum).
     */
    public static function requireRole($roles = []) {
        self::requireLogin();
        if (!in_array($_SESSION['role'], $roles)) {
            header('HTTP/1.0 403 Forbidden');
            echo "403 Forbidden - You don't have permission to access this resource.";
            exit;
        }
    }

    // ─── Admin / Dashboard ────────────────────────────────────────────────────

    /**
     * Pastikan pengguna sudah login sebagai admin/superadmin/gudang.
     * Digunakan di semua halaman dashboard admin.
     */
    public static function requireAdminLogin() {
        $adminRoles = ['superadmin', 'admin', 'gudang'];

        if (!isset($_SESSION['user_id'])) {
            Flasher::setFlash('Akses Ditolak', 'Silakan login ke dashboard admin.', 'red');
            header('Location: ' . BASEURL . '/admin/login');
            exit;
        }

        if (!in_array($_SESSION['role'], $adminRoles)) {
            // Customer mencoba akses admin → tolak
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki hak akses ke dashboard admin.', 'red');
            header('Location: ' . BASEURL . '/');
            exit;
        }
    }

    /**
     * Cek apakah session saat ini adalah role admin.
     */
    public static function isAdmin() {
        return isset($_SESSION['role']) && in_array($_SESSION['role'], ['superadmin', 'admin', 'gudang']);
    }

    /**
     * Cek apakah session saat ini adalah customer biasa.
     */
    public static function isCustomer() {
        return isset($_SESSION['user_id']) && !self::isAdmin();
    }
}
