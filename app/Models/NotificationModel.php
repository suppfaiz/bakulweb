<?php

class NotificationModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createNotification($userId, $title, $message) {
        $this->db->query("INSERT INTO notifications (user_id, title, message) VALUES (:user_id, :title, :message)");
        $this->db->bind('user_id', $userId);
        $this->db->bind('title', $title);
        $this->db->bind('message', $message);
        return $this->db->execute();
    }

    public function getNotificationsByUser($userId) {
        $this->db->query("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 20");
        $this->db->bind('user_id', $userId);
        return $this->db->resultSet();
    }

    public function getUnreadCount($userId) {
        $this->db->query("SELECT COUNT(*) as count FROM notifications WHERE user_id = :user_id AND is_read = 0");
        $this->db->bind('user_id', $userId);
        return $this->db->single()['count'] ?? 0;
    }

    public function markAsRead($id) {
        $this->db->query("UPDATE notifications SET is_read = 1 WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function markAllAsRead($userId) {
        $this->db->query("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id");
        $this->db->bind('user_id', $userId);
        return $this->db->execute();
    }
}
