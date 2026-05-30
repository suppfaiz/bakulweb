<?php

class HelpController extends Controller {

    public function __construct() {
        // Auto-create warranty_claims table if not exists
        $db = new Database;
        $db->query("CREATE TABLE IF NOT EXISTS `warranty_claims` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL,
          `invoice` varchar(50) NOT NULL,
          `device_name` varchar(255) NOT NULL,
          `reason` text NOT NULL,
          `attachment_path` varchar(255) NOT NULL,
          `status` enum('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`),
          FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $db->execute();
    }
    
    // GET /help - Pusat Bantuan
    public function index() {
        $data['judul'] = 'Pusat Bantuan | BAKUL';
        $this->view('frontend/help/index', $data);
    }

    // GET /help/terms - Syarat & Ketentuan
    public function terms() {
        $data['judul'] = 'Syarat & Ketentuan | BAKUL';
        $this->view('frontend/help/terms', $data);
    }

    // GET /help/privacy - Kebijakan Privasi
    public function privacy() {
        $data['judul'] = 'Kebijakan Privasi | BAKUL';
        $this->view('frontend/help/privacy', $data);
    }

    // GET /help/refund - Kebijakan Pengembalian Dana
    public function refund() {
        $data['judul'] = 'Kebijakan Pengembalian Dana | BAKUL';
        $this->view('frontend/help/refund', $data);
    }

    // POST /help/submit_claim - Proses form klaim garansi
    public function submit_claim() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/help/refund');
            exit;
        }

        require_once __DIR__ . '/../Helpers/Auth.php';
        if (!Auth::isLoggedIn()) {
            Flasher::setFlash('gagal', 'Anda harus login terlebih dahulu untuk mengajukan klaim garansi.', 'error');
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $user_id     = $_SESSION['user_id'];
        $invoice     = trim($_POST['invoice'] ?? '');
        $device_name = trim($_POST['device_name'] ?? '');
        $reason      = trim($_POST['reason'] ?? '');

        if (!$invoice || !$device_name || !$reason || empty($_FILES['attachment']['name'])) {
            Flasher::setFlash('gagal', 'Harap isi semua formulir termasuk melampirkan bukti kendala (foto/video).', 'warning');
            header('Location: ' . BASEURL . '/help/refund');
            exit;
        }

        // Upload attachment
        $file = $_FILES['attachment'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileError = $file['error'];
        
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'mp4', 'mov', 'avi'];

        if (!in_array($fileExt, $allowed)) {
            Flasher::setFlash('gagal', 'Format file bukti harus berupa foto (JPG, JPEG, PNG) atau video (MP4, MOV, AVI).', 'error');
            header('Location: ' . BASEURL . '/help/refund');
            exit;
        }

        if ($fileError !== 0) {
            Flasher::setFlash('gagal', 'Terjadi kesalahan saat mengunggah file bukti.', 'error');
            header('Location: ' . BASEURL . '/help/refund');
            exit;
        }

        // Create directory if not exists
        $uploadDir = __DIR__ . '/../../public/uploads/claims/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newFileName = 'claim_' . time() . '_' . uniqid('', true) . '.' . $fileExt;
        $fileDestination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpName, $fileDestination)) {
            $attachment_path = 'uploads/claims/' . $newFileName;

            // Save to DB
            $db = new Database;
            $db->query("INSERT INTO warranty_claims (user_id, invoice, device_name, reason, attachment_path, status) 
                        VALUES (:user_id, :invoice, :device_name, :reason, :attachment_path, 'pending')");
            $db->bind('user_id', $user_id);
            $db->bind('invoice', $invoice);
            $db->bind('device_name', $device_name);
            $db->bind('reason', $reason);
            $db->bind('attachment_path', $attachment_path);
            $db->execute();

            // Create notification for user
            $notificationModel = $this->model('NotificationModel');
            $notificationModel->createNotification(
                $user_id,
                'Klaim Garansi Diajukan',
                'Klaim garansi untuk invoice ' . $invoice . ' (' . $device_name . ') berhasil diajukan dan sedang ditinjau.'
            );

            Flasher::setFlash('berhasil', 'Klaim garansi berhasil diajukan. Tim kami akan segera meninjau bukti kendala Anda.', 'success');
        } else {
            Flasher::setFlash('gagal', 'Gagal memindahkan file bukti ke server.', 'error');
        }

        header('Location: ' . BASEURL . '/help/refund');
        exit;
    }
}
