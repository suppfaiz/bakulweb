<?php

class NotificationController extends Controller {
    public function __construct() {
        require_once __DIR__ . '/../Helpers/Auth.php';
        Auth::requireLogin();
    }

    public function get() {
        $notificationModel = $this->model('NotificationModel');
        $notifications = $notificationModel->getNotificationsByUser($_SESSION['user_id']);
        $unreadCount = $notificationModel->getUnreadCount($_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
        exit;
    }

    public function read() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $id = $data['id'] ?? 0;

            if ($id) {
                $notificationModel = $this->model('NotificationModel');
                $notificationModel->markAsRead($id);
                
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }

    public function read_all() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $notificationModel = $this->model('NotificationModel');
            $notificationModel->markAllAsRead($_SESSION['user_id']);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }
}
