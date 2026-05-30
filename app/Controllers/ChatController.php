<?php

class ChatController extends Controller {

    public function __construct() {
        // Auto-create chats table if not exists
        $db = new Database;
        $db->query("CREATE TABLE IF NOT EXISTS `chats` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL,
          `admin_id` int(11) DEFAULT NULL,
          `product_id` int(11) DEFAULT NULL,
          `message` text NOT NULL,
          `is_admin` tinyint(1) NOT NULL DEFAULT 0,
          `is_read` tinyint(1) NOT NULL DEFAULT 0,
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`),
          FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
          FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $db->execute();
    }

    // Customer pulls their messages
    public function get_messages() {
        require_once __DIR__ . '/../Helpers/Auth.php';
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $db = new Database;
        $db->query("SELECT c.*, p.name as product_name 
                    FROM chats c
                    LEFT JOIN products p ON c.product_id = p.id
                    WHERE c.user_id = :user_id 
                    ORDER BY c.created_at ASC");
        $db->bind('user_id', $userId);
        $messages = $db->resultSet();

        // Mark admin's messages as read because customer has read them
        $db->query("UPDATE chats SET is_read = 1 WHERE user_id = :user_id AND is_admin = 1 AND is_read = 0");
        $db->bind('user_id', $userId);
        $db->execute();

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'messages' => $messages]);
        exit;
    }

    // Customer sends message
    public function send() {
        require_once __DIR__ . '/../Helpers/Auth.php';
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $message = trim($data['message'] ?? '');
        $productId = !empty($data['product_id']) ? intval($data['product_id']) : null;

        if (!$message) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Pesan tidak boleh kosong']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $db = new Database;
        $db->query("INSERT INTO chats (user_id, product_id, message, is_admin, is_read) 
                    VALUES (:user_id, :product_id, :message, 0, 0)");
        $db->bind('user_id', $userId);
        $db->bind('product_id', $productId);
        $db->bind('message', $message);
        
        if ($db->execute()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan pesan']);
        }
        exit;
    }

    // GET /chat/admin - Admin Chat Dashboard
    public function admin() {
        require_once __DIR__ . '/../Helpers/Auth.php';
        Auth::requireRole(['superadmin', 'admin']);

        $data['judul'] = 'Live Chat Pelanggan | BAKUL Enterprise';
        $this->view('admin/chat/index', $data);
    }

    // AJAX Endpoint for admin to load threads list
    public function admin_get_threads() {
        require_once __DIR__ . '/../Helpers/Auth.php';
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['superadmin', 'admin'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $db = new Database;
        // Group by user and find their latest message and unread count
        $db->query("SELECT u.id as user_id, u.username, u.email,
                           (SELECT message FROM chats WHERE user_id = u.id ORDER BY created_at DESC LIMIT 1) as latest_message,
                           (SELECT created_at FROM chats WHERE user_id = u.id ORDER BY created_at DESC LIMIT 1) as latest_message_time,
                           (SELECT COUNT(*) FROM chats WHERE user_id = u.id AND is_admin = 0 AND is_read = 0) as unread_count
                    FROM users u
                    WHERE EXISTS (SELECT 1 FROM chats WHERE user_id = u.id)
                    ORDER BY latest_message_time DESC");
        $threads = $db->resultSet();

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'threads' => $threads]);
        exit;
    }

    // AJAX Endpoint for admin to load messages with a specific user
    public function admin_get_messages($userId) {
        require_once __DIR__ . '/../Helpers/Auth.php';
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['superadmin', 'admin'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $db = new Database;
        $db->query("SELECT c.*, p.name as product_name, p.slug as product_slug,
                           (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as product_image
                    FROM chats c
                    LEFT JOIN products p ON c.product_id = p.id
                    WHERE c.user_id = :user_id 
                    ORDER BY c.created_at ASC");
        $db->bind('user_id', intval($userId));
        $messages = $db->resultSet();

        // Mark these user's messages as read by admin
        $db->query("UPDATE chats SET is_read = 1 WHERE user_id = :user_id AND is_admin = 0 AND is_read = 0");
        $db->bind('user_id', intval($userId));
        $db->execute();

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'messages' => $messages]);
        exit;
    }

    // AJAX Endpoint for admin to send reply
    public function admin_send() {
        require_once __DIR__ . '/../Helpers/Auth.php';
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['superadmin', 'admin'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $message = trim($data['message'] ?? '');
        $userId = !empty($data['user_id']) ? intval($data['user_id']) : null;
        $productId = !empty($data['product_id']) ? intval($data['product_id']) : null;

        if (!$message || !$userId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Pesan atau User ID tidak valid']);
            exit;
        }

        $adminId = $_SESSION['user_id'];
        $db = new Database;
        $db->query("INSERT INTO chats (user_id, admin_id, product_id, message, is_admin, is_read) 
                    VALUES (:user_id, :admin_id, :product_id, :message, 1, 0)");
        $db->bind('user_id', $userId);
        $db->bind('admin_id', $adminId);
        $db->bind('product_id', $productId);
        $db->bind('message', $message);

        if ($db->execute()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan pesan admin']);
        }
        exit;
    }
}
