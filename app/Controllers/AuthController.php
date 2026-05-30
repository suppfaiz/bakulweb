<?php

class AuthController extends Controller {
    public function __construct() {
        // Init CSRF Token if not exists
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        // Load Auth helper
        require_once __DIR__ . '/../Helpers/Auth.php';
    }

    public function index() {
        $this->login();
    }

    public function login() {
        // Jika admin sudah login, arahkan ke dashboard admin
        if (isset($_SESSION['user_id']) && Auth::isAdmin()) {
            header('Location: ' . BASEURL . '/admin');
            exit;
        }
        // Jika customer sudah login, arahkan ke home
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL);
            exit;
        }

        $data['judul'] = 'Login | BAKUL Enterprise';
        $this->view('frontend/auth/login', $data);
    }

    public function process_login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validasi CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                Flasher::setFlash('gagal', 'Token CSRF tidak valid!', 'error');
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            }

            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = $this->model('UserModel');
            $user = $userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Blokir akun admin/staf dari halaman login customer
                if (in_array($user['role'], ['superadmin', 'admin', 'gudang'])) {
                    Flasher::setFlash('Akses Ditolak', 'Akun admin/staf harus login melalui halaman admin.', 'error');
                    header('Location: ' . BASEURL . '/admin/login');
                    exit;
                }

                // Cek verifikasi email
                if (isset($user['is_verified']) && $user['is_verified'] == 0) {
                    $_SESSION['temp_verify_email'] = $user['email'];
                    Flasher::setFlash('Akun Belum Aktif:', 'Silakan lakukan verifikasi email terlebih dahulu.', 'error');
                    header('Location: ' . BASEURL . '/auth/verify');
                    exit;
                }

                // Login Berhasil - Customer
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['login_type'] = 'customer';

                header('Location: ' . BASEURL . '/');
                exit;
            } else {
                Flasher::setFlash('gagal', 'Email atau Password salah!', 'error');
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        // Customer logout selalu ke halaman login customer
        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }

    public function login_ajax() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $email = trim($data['email'] ?? '');
            $password = $data['password'] ?? '';

            if (empty($email) || empty($password)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Email dan password wajib diisi.']);
                exit;
            }

            $userModel = $this->model('UserModel');
            $user = $userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Cek verifikasi email
                if (isset($user['is_verified']) && $user['is_verified'] == 0) {
                    $_SESSION['temp_verify_email'] = $user['email'];
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'requires_verification' => true,
                        'email' => $user['email'],
                        'message' => 'Akun belum terverifikasi. Silakan masukkan kode verifikasi.'
                    ]);
                    exit;
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'user' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role']
                    ]
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Email atau Password salah.']);
            }
            exit;
        }
    }

    public function register_ajax() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $username = trim($data['username'] ?? '');
            $email = trim($data['email'] ?? '');
            $password = $data['password'] ?? '';
            $phone = trim($data['phone'] ?? '');
            $address = trim($data['address'] ?? '');

            if (empty($username) || empty($email) || empty($password)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Semua kolom wajib diisi.']);
                exit;
            }

            $userModel = $this->model('UserModel');
            $existing = $userModel->getUserByEmail($email);
            if ($existing) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar.']);
                exit;
            }

            $db = new Database();
            $db->query("SELECT * FROM users WHERE username = :username");
            $db->bind('username', $username);
            if ($db->single()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Username sudah terdaftar.']);
                exit;
            }

            $verificationCode = strval(mt_rand(100000, 999999));

            $registerData = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'address' => $address,
                'verification_code' => $verificationCode
            ];

            if ($userModel->registerUser($registerData) > 0) {
                // [OTP DINONAKTIFKAN SEMENTARA] - aktifkan langsung tanpa verifikasi email
                $userModel->activateUser($email);

                $user = $userModel->getUserByEmail($email);
                header('Content-Type: application/json');
                echo json_encode([
                    'success'              => true,
                    'requires_verification' => false,
                    'message'              => 'Registrasi berhasil! Akun Anda langsung aktif.',
                    'user'                 => [
                        'id'    => $user['id'],
                        'name'  => $user['username'],
                        'email' => $user['email'],
                        'role'  => $user['role']
                    ]
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal mendaftarkan akun.']);
            }
            exit;
        }
    }

    public function register() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL);
            exit;
        }

        $data['judul'] = 'Daftar Akun | BAKUL Enterprise';
        $this->view('frontend/auth/register', $data);
    }

    public function process_register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validasi CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                Flasher::setFlash('gagal', 'Token CSRF tidak valid!', 'error');
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            if (empty($username) || empty($email) || empty($password)) {
                Flasher::setFlash('Registrasi Gagal:', 'Kolom Username, Email, dan Password wajib diisi.', 'error');
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }

            $userModel = $this->model('UserModel');
            
            // Cek email terdaftar
            if ($userModel->getUserByEmail($email)) {
                Flasher::setFlash('Registrasi Gagal:', 'Email sudah terdaftar.', 'error');
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }

            // Cek username terdaftar
            $db = new Database();
            $db->query("SELECT * FROM users WHERE username = :username");
            $db->bind('username', $username);
            if ($db->single()) {
                Flasher::setFlash('Registrasi Gagal:', 'Username sudah terdaftar.', 'error');
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }

            $verificationCode = strval(mt_rand(100000, 999999));

            $registerData = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'address' => $address,
                'verification_code' => $verificationCode
            ];

            if ($userModel->registerUser($registerData) > 0) {
                // [OTP DINONAKTIFKAN SEMENTARA] - aktifkan langsung tanpa verifikasi email
                $userModel->activateUser($email);

                // Langsung login user
                $user = $userModel->getUserByEmail($email);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = $user['role'];
                $_SESSION['name']    = $user['username'];
                $_SESSION['email']   = $user['email'];

                Flasher::setFlash('Registrasi Berhasil!', 'Selamat datang di BAKUL, ' . htmlspecialchars($username) . '!', 'success');
                header('Location: ' . BASEURL . '/');
                exit;
            } else {
                Flasher::setFlash('Registrasi Gagal:', 'Terjadi kesalahan sistem, silakan coba lagi.', 'error');
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }
        }
    }

    public function verify() {
        if (!isset($_SESSION['temp_verify_email'])) {
            Flasher::setFlash('Akses Ditolak:', 'Silakan registrasi atau masuk terlebih dahulu.', 'error');
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $data['judul'] = 'Verifikasi Akun | BAKUL Enterprise';
        $data['email'] = $_SESSION['temp_verify_email'];
        $this->view('frontend/auth/verify', $data);
    }

    public function process_verify() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['temp_verify_email'])) {
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            }

            $email = $_SESSION['temp_verify_email'];
            $code = trim($_POST['code'] ?? '');

            if (empty($code)) {
                Flasher::setFlash('Verifikasi Gagal:', 'Kode OTP wajib diisi.', 'error');
                header('Location: ' . BASEURL . '/auth/verify');
                exit;
            }

            $userModel = $this->model('UserModel');
            if ($userModel->verifyUser($email, $code)) {
                // Get the user data and log them in
                $user = $userModel->getUserByEmail($email);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                unset($_SESSION['temp_verify_email']);

                Flasher::setFlash('Verifikasi Berhasil!', 'Akun Anda sekarang aktif dan Anda telah masuk.', 'success');
                header('Location: ' . BASEURL . '/');
                exit;
            } else {
                Flasher::setFlash('Verifikasi Gagal:', 'Kode verifikasi tidak valid atau kedaluwarsa.', 'error');
                header('Location: ' . BASEURL . '/auth/verify');
                exit;
            }
        }
    }

    public function resend_code() {
        if (!isset($_SESSION['temp_verify_email'])) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $email = $_SESSION['temp_verify_email'];
        $verificationCode = strval(mt_rand(100000, 999999));

        $userModel = $this->model('UserModel');
        if ($userModel->updateVerificationCode($email, $verificationCode)) {
            require_once __DIR__ . '/../Helpers/EmailHelper.php';
            EmailHelper::sendVerificationCode($email, $verificationCode);
            Flasher::setFlash('Kode Dikirim!', 'Kode verifikasi baru telah dikirim ke email Anda.', 'success');
        } else {
            Flasher::setFlash('Gagal:', 'Gagal mengirim ulang kode, coba lagi.', 'error');
        }
        header('Location: ' . BASEURL . '/auth/verify');
        exit;
    }
}

