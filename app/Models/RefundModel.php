<?php
// app/Models/RefundModel.php

class RefundModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createRefundRequest($data) {
        $this->db->query("INSERT INTO refund_requests (order_id, user_id, amount, reason, proof_path, status) VALUES (:order_id, :user_id, :amount, :reason, :proof_path, 'pending')");
        $this->db->bind('order_id', $data['order_id']);
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('amount', $data['amount']);
        $this->db->bind('reason', $data['reason']);
        $this->db->bind('proof_path', $data['proof_path']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function getRefundRequestByOrderId($orderId) {
        $this->db->query("SELECT * FROM refund_requests WHERE order_id = :order_id");
        $this->db->bind('order_id', $orderId);
        return $this->db->single();
    }

    public function getRefundRequestsByUser($userId) {
        $this->db->query("SELECT r.*, o.invoice FROM refund_requests r JOIN orders o ON r.order_id = o.id WHERE r.user_id = :user_id ORDER BY r.created_at DESC");
        $this->db->bind('user_id', $userId);
        return $this->db->resultSet();
    }

    public function getAllRefundRequests() {
        $this->db->query("SELECT r.*, o.invoice, u.username, u.email FROM refund_requests r JOIN orders o ON r.order_id = o.id JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");
        return $this->db->resultSet();
    }

    public function getRefundRequestById($id) {
        $this->db->query("SELECT r.*, o.invoice, u.username, u.email, u.refund_balance FROM refund_requests r JOIN orders o ON r.order_id = o.id JOIN users u ON r.user_id = u.id WHERE r.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function updateRefundStatus($id, $status, $adminNotes) {
        $this->db->query("SELECT * FROM refund_requests WHERE id = :id");
        $this->db->bind('id', $id);
        $request = $this->db->single();

        if (!$request) return false;

        // If transitioning to approved and it was not approved before, we add to user's refund balance
        if ($status === 'approved' && $request['status'] !== 'approved') {
            // Update refund request status
            $this->db->query("UPDATE refund_requests SET status = :status, admin_notes = :admin_notes WHERE id = :id");
            $this->db->bind('status', $status);
            $this->db->bind('admin_notes', $adminNotes);
            $this->db->bind('id', $id);
            $this->db->execute();

            // Add to user's refund balance
            $this->db->query("UPDATE users SET refund_balance = refund_balance + :amount WHERE id = :user_id");
            $this->db->bind('amount', $request['amount']);
            $this->db->bind('user_id', $request['user_id']);
            $this->db->execute();
            
            // Set the order status to cancelled
            $this->db->query("UPDATE orders SET status = 'cancelled' WHERE id = :order_id");
            $this->db->bind('order_id', $request['order_id']);
            $this->db->execute();

            return true;
        } else {
            // For other status transitions
            $this->db->query("UPDATE refund_requests SET status = :status, admin_notes = :admin_notes WHERE id = :id");
            $this->db->bind('status', $status);
            $this->db->bind('admin_notes', $adminNotes);
            $this->db->bind('id', $id);
            return $this->db->execute();
        }
    }

    // Withdrawal methods
    public function createWithdrawal($userId, $amount, $bankName, $accountNumber, $accountHolder) {
        // Check if user has sufficient refund balance
        $this->db->query("SELECT refund_balance FROM users WHERE id = :user_id");
        $this->db->bind('user_id', $userId);
        $user = $this->db->single();

        if (!$user || $user['refund_balance'] < $amount) {
            return false;
        }

        // Subtract from user balance
        $this->db->query("UPDATE users SET refund_balance = refund_balance - :amount WHERE id = :user_id");
        $this->db->bind('amount', $amount);
        $this->db->bind('user_id', $userId);
        $this->db->execute();

        // Create withdrawal record
        $this->db->query("INSERT INTO withdrawals (user_id, amount, bank_name, account_number, account_holder, status) VALUES (:user_id, :amount, :bank_name, :account_number, :account_holder, 'pending')");
        $this->db->bind('user_id', $userId);
        $this->db->bind('amount', $amount);
        $this->db->bind('bank_name', $bankName);
        $this->db->bind('account_number', $accountNumber);
        $this->db->bind('account_holder', $accountHolder);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function getWithdrawalsByUser($userId) {
        $this->db->query("SELECT * FROM withdrawals WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind('user_id', $userId);
        return $this->db->resultSet();
    }

    public function getAllWithdrawals() {
        $this->db->query("SELECT w.*, u.username, u.email FROM withdrawals w JOIN users u ON w.user_id = u.id ORDER BY w.created_at DESC");
        return $this->db->resultSet();
    }

    public function getWithdrawalById($id) {
        $this->db->query("SELECT w.*, u.username, u.email FROM withdrawals w JOIN users u ON w.user_id = u.id WHERE w.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function updateWithdrawalStatus($id, $status, $adminNotes) {
        $this->db->query("SELECT * FROM withdrawals WHERE id = :id");
        $this->db->bind('id', $id);
        $w = $this->db->single();

        if (!$w) return false;

        // If status changes to failed, we return the funds back to user's refund balance
        if ($status === 'failed' && $w['status'] !== 'failed') {
            $this->db->query("UPDATE withdrawals SET status = :status, admin_notes = :admin_notes WHERE id = :id");
            $this->db->bind('status', $status);
            $this->db->bind('admin_notes', $adminNotes);
            $this->db->bind('id', $id);
            $this->db->execute();

            $this->db->query("UPDATE users SET refund_balance = refund_balance + :amount WHERE id = :user_id");
            $this->db->bind('amount', $w['amount']);
            $this->db->bind('user_id', $w['user_id']);
            $this->db->execute();
            return true;
        } else {
            $this->db->query("UPDATE withdrawals SET status = :status, admin_notes = :admin_notes WHERE id = :id");
            $this->db->bind('status', $status);
            $this->db->bind('admin_notes', $adminNotes);
            $this->db->bind('id', $id);
            return $this->db->execute();
        }
    }
}
