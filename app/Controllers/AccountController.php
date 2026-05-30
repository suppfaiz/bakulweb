<?php

class AccountController extends Controller {
    public function __construct() {
        require_once __DIR__ . '/../Helpers/Auth.php';
        Auth::requireLogin();
    }

    public function index() {
        $data['judul'] = 'Akun Saya | BAKUL E-Commerce';
        $orderModel = $this->model('OrderModel');
        $productModel = $this->model('ProductModel');
        $userModel = $this->model('UserModel');
        $refundModel = $this->model('RefundModel');
        
        $orders = $orderModel->getOrdersByUser($_SESSION['user_id']);
        foreach ($orders as &$order) {
            $order['items'] = $orderModel->getOrderItems($order['id']);
            foreach ($order['items'] as &$item) {
                $item['is_reviewed'] = $productModel->hasUserReviewedProduct($_SESSION['user_id'], $item['product_id'], $order['id']);
            }
            $order['refund'] = $refundModel->getRefundRequestByOrderId($order['id']);
        }
        $data['orders'] = $orders;
        $data['tab'] = $_GET['tab'] ?? 'orders';
        $data['user'] = $userModel->getUserById($_SESSION['user_id']);
        
        // Load refund & withdrawal history for the user
        $data['refund_requests'] = $refundModel->getRefundRequestsByUser($_SESSION['user_id']);
        $data['withdrawals'] = $refundModel->getWithdrawalsByUser($_SESSION['user_id']);

        // Load warranty claims for the user
        $warrantyModel = $this->model('WarrantyModel');
        $data['warranty_claims'] = $warrantyModel->getClaimsByUser($_SESSION['user_id']);

        // Load chat history (semua pesan user dengan CS/admin)
        $db = new Database();
        $db->query("SELECT c.*, p.name as product_name, p.slug as product_slug
                    FROM chats c
                    LEFT JOIN products p ON c.product_id = p.id
                    WHERE c.user_id = :user_id
                    ORDER BY c.created_at ASC");
        $db->bind('user_id', $_SESSION['user_id']);
        $data['chat_messages'] = $db->resultSet() ?: [];

        // Hitung unread dari admin
        $db->query("SELECT COUNT(*) as cnt FROM chats WHERE user_id = :user_id AND is_admin = 1 AND is_read = 0");
        $db->bind('user_id', $_SESSION['user_id']);
        $unreadRow = $db->single();
        $data['chat_unread'] = $unreadRow['cnt'] ?? 0;
        
        $this->view('frontend/account/index', $data);
    }

    
    public function update_profile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('UserModel');
            $user = $userModel->getUserById($_SESSION['user_id']);
            
            $username         = trim($_POST['username']         ?? '');
            $phone            = trim($_POST['phone']            ?? '');
            $address          = trim($_POST['address']          ?? '');
            $current_password = $_POST['current_password']      ?? '';
            $new_password     = $_POST['new_password']          ?? '';
            $confirm_password = $_POST['confirm_password']      ?? '';
            
            if (empty($username)) {
                Flasher::setFlash('gagal', 'Nama tidak boleh kosong.', 'error');
                header('Location: ' . BASEURL . '/account?tab=settings');
                exit;
            }
            
            // Update profile (username + phone + address)
            $userModel->updateProfile($_SESSION['user_id'], $username, $phone, $address);
            $_SESSION['name'] = $username;
            
            // Update password jika diisi
            if (!empty($new_password)) {
                if (empty($current_password)) {
                    Flasher::setFlash('gagal', 'Masukkan password lama untuk mengubah password.', 'error');
                    header('Location: ' . BASEURL . '/account?tab=settings');
                    exit;
                }
                if (!password_verify($current_password, $user['password'])) {
                    Flasher::setFlash('gagal', 'Password lama tidak cocok.', 'error');
                    header('Location: ' . BASEURL . '/account?tab=settings');
                    exit;
                }
                if (strlen($new_password) < 6) {
                    Flasher::setFlash('gagal', 'Password baru minimal 6 karakter.', 'error');
                    header('Location: ' . BASEURL . '/account?tab=settings');
                    exit;
                }
                if ($new_password !== $confirm_password) {
                    Flasher::setFlash('gagal', 'Konfirmasi password tidak cocok.', 'error');
                    header('Location: ' . BASEURL . '/account?tab=settings');
                    exit;
                }
                $userModel->updatePassword($_SESSION['user_id'], $new_password);
            }
            
            Flasher::setFlash('berhasil', 'Profil berhasil diperbarui!', 'success');
            header('Location: ' . BASEURL . '/account?tab=settings');
            exit;
        }
    }
    
    public function invoice($id) {
        $orderModel = $this->model('OrderModel');
        $order = $orderModel->getOrderById($id);
        
        if(!$order || ($order['user_id'] != $_SESSION['user_id'] && !in_array($_SESSION['role'], ['superadmin', 'admin']))) {
            Flasher::setFlash('gagal', 'Invoice tidak ditemukan atau bukan milik Anda', 'error');
            header('Location: ' . BASEURL . '/account');
            exit;
        }
        
        $data['judul'] = 'Invoice ' . $order['invoice'];
        $data['order'] = $order;
        $data['items'] = $orderModel->getOrderItems($id);
        
        require_once __DIR__ . '/../Views/frontend/account/invoice.php';
    }

    public function review() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = $_POST['order_id'];
            $ratings = $_POST['rating'] ?? [];
            $comments = $_POST['comment'] ?? [];
            
            $orderModel = $this->model('OrderModel');
            $productModel = $this->model('ProductModel');
            
            $order = $orderModel->getOrderById($orderId);
            
            if (!$order || $order['user_id'] != $_SESSION['user_id'] || !in_array($order['status'], ['delivered', 'completed'])) {
                Flasher::setFlash('gagal', 'Anda tidak berhak memberikan ulasan untuk pesanan ini', 'error');
                header('Location: ' . BASEURL . '/account');
                exit;
            }
            
            $success = false;
            foreach ($ratings as $productId => $rating) {
                $ratingVal = intval($rating);
                if ($ratingVal < 1 || $ratingVal > 5) continue;
                
                $commentVal = htmlspecialchars($comments[$productId] ?? '');
                
                if (!$productModel->hasUserReviewedProduct($_SESSION['user_id'], $productId, $orderId)) {
                    $productModel->addReview($_SESSION['user_id'], $productId, $orderId, $ratingVal, $commentVal);
                    $success = true;
                }
            }
            
            if ($success) {
                Flasher::setFlash('berhasil', 'Ulasan berhasil disimpan, terima kasih!', 'success');
            } else {
                Flasher::setFlash('gagal', 'Ulasan gagal disimpan atau sudah pernah diulas.', 'error');
            }
            
            header('Location: ' . BASEURL . '/account');
            exit;
        }
    }

    public function confirm_delivery($id) {
        $orderModel = $this->model('OrderModel');
        $order = $orderModel->getOrderById($id);
        
        if(!$order || $order['user_id'] != $_SESSION['user_id'] || $order['status'] != 'shipped') {
            Flasher::setFlash('gagal', 'Pesanan tidak ditemukan atau tidak dapat dikonfirmasi', 'error');
            header('Location: ' . BASEURL . '/account');
            exit;
        }
        
        $orderModel->updateOrderStatus($id, 'completed');
        
        $notificationModel = $this->model('NotificationModel');
        $notificationModel->createNotification($_SESSION['user_id'], 'Pesanan Selesai', 'Terima kasih! Pesanan ' . $order['invoice'] . ' telah selesai diterima. Silakan berikan ulasan Anda.');
        
        Flasher::setFlash('berhasil', 'Terima kasih! Pesanan telah diselesaikan. Silakan berikan ulasan Anda.', 'success');
        header('Location: ' . BASEURL . '/account');
        exit;
    }

    public function submit_refund() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = intval($_POST['order_id'] ?? 0);
            $reason = trim($_POST['reason'] ?? '');
            
            $orderModel = $this->model('OrderModel');
            $refundModel = $this->model('RefundModel');
            
            $order = $orderModel->getOrderById($orderId);
            
            // Check ownership and if paid
            if (!$order || $order['user_id'] != $_SESSION['user_id'] || $order['payment_status'] != 'paid') {
                Flasher::setFlash('Gagal:', 'Pesanan tidak valid atau belum lunas.', 'error');
                header('Location: ' . BASEURL . '/account?tab=orders');
                exit;
            }
            
            // Check if already requested refund
            $existingRefund = $refundModel->getRefundRequestByOrderId($orderId);
            if ($existingRefund) {
                Flasher::setFlash('Gagal:', 'Refund untuk pesanan ini sudah pernah diajukan.', 'error');
                header('Location: ' . BASEURL . '/account?tab=orders');
                exit;
            }
            
            if (empty($reason)) {
                Flasher::setFlash('Gagal:', 'Alasan pengembalian dana wajib diisi.', 'error');
                header('Location: ' . BASEURL . '/account?tab=orders');
                exit;
            }
            
            // Handle upload proof
            $proofPath = null;
            if (isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['proof']['tmp_name'];
                $fileName = $_FILES['proof']['name'];
                $fileSize = $_FILES['proof']['size'];
                $fileType = $_FILES['proof']['type'];
                
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    if ($fileSize <= 2 * 1024 * 1024) { // 2MB max
                        $uploadFileDir = __DIR__ . '/../../public/uploads/refunds/';
                        if (!is_dir($uploadFileDir)) {
                            mkdir($uploadFileDir, 0755, true);
                        }
                        
                        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                        $dest_path = $uploadFileDir . $newFileName;
                        
                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            $proofPath = 'uploads/refunds/' . $newFileName;
                        } else {
                            Flasher::setFlash('Gagal:', 'Terjadi kesalahan saat mengunggah berkas bukti.', 'error');
                            header('Location: ' . BASEURL . '/account?tab=orders');
                            exit;
                        }
                    } else {
                        Flasher::setFlash('Gagal:', 'Ukuran berkas bukti maksimal 2MB.', 'error');
                        header('Location: ' . BASEURL . '/account?tab=orders');
                        exit;
                    }
                } else {
                    Flasher::setFlash('Gagal:', 'Ekstensi berkas tidak diizinkan. Gunakan JPG, JPEG, atau PNG.', 'error');
                    header('Location: ' . BASEURL . '/account?tab=orders');
                    exit;
                }
            } else {
                Flasher::setFlash('Gagal:', 'Berkas bukti wajib diunggah.', 'error');
                header('Location: ' . BASEURL . '/account?tab=orders');
                exit;
            }
            
            $refundData = [
                'order_id' => $orderId,
                'user_id' => $_SESSION['user_id'],
                'amount' => $order['total_amount'],
                'reason' => $reason,
                'proof_path' => $proofPath
            ];
            
            if ($refundModel->createRefundRequest($refundData)) {
                Flasher::setFlash('Berhasil!', 'Pengajuan refund berhasil dikirim. Menunggu tinjauan admin.', 'success');
            } else {
                Flasher::setFlash('Gagal:', 'Gagal mengajukan refund, silakan coba lagi.', 'error');
            }
            header('Location: ' . BASEURL . '/account?tab=orders');
            exit;
        }
    }
    
    public function submit_withdrawal() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $amount = floatval($_POST['amount'] ?? 0);
            $bankName = trim($_POST['bank_name'] ?? '');
            $accountNumber = trim($_POST['account_number'] ?? '');
            $accountHolder = trim($_POST['account_holder'] ?? '');
            
            if ($amount <= 0 || empty($bankName) || empty($accountNumber) || empty($accountHolder)) {
                Flasher::setFlash('Gagal:', 'Semua kolom formulir penarikan wajib diisi dengan benar.', 'error');
                header('Location: ' . BASEURL . '/account?tab=refund');
                exit;
            }
            
            $userModel = $this->model('UserModel');
            $refundModel = $this->model('RefundModel');
            
            $user = $userModel->getUserById($_SESSION['user_id']);
            if ($user['refund_balance'] < $amount) {
                Flasher::setFlash('Gagal:', 'Saldo refund Anda tidak mencukupi untuk melakukan penarikan.', 'error');
                header('Location: ' . BASEURL . '/account?tab=refund');
                exit;
            }
            
            if ($refundModel->createWithdrawal($_SESSION['user_id'], $amount, $bankName, $accountNumber, $accountHolder)) {
                Flasher::setFlash('Berhasil!', 'Permintaan penarikan dana berhasil diajukan.', 'success');
            } else {
                Flasher::setFlash('Gagal:', 'Terjadi kesalahan saat memproses penarikan.', 'error');
            }
            header('Location: ' . BASEURL . '/account?tab=refund');
            exit;
        }
    }
}
