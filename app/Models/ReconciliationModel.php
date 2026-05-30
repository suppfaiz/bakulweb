<?php
// app/Models/ReconciliationModel.php

class ReconciliationModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getBankStatements() {
        $this->db->query("SELECT bs.*, o.invoice as matched_invoice, o.total_amount as order_amount FROM bank_statements bs LEFT JOIN orders o ON bs.reconciled_order_id = o.id ORDER BY bs.statement_date DESC");
        return $this->db->resultSet();
    }

    public function getUnreconciledStatements() {
        $this->db->query("SELECT * FROM bank_statements WHERE status = 'unreconciled' ORDER BY statement_date DESC");
        return $this->db->resultSet();
    }

    public function getUnreconciledOrders() {
        // Fetch paid orders that are not linked to any reconciled bank statement
        $this->db->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id WHERE o.payment_status = 'paid' AND o.id NOT IN (SELECT reconciled_order_id FROM bank_statements WHERE reconciled_order_id IS NOT NULL) ORDER BY o.created_at DESC");
        return $this->db->resultSet();
    }

    public function reconcile($statementId, $orderId) {
        $this->db->query("UPDATE bank_statements SET reconciled_order_id = :order_id, status = 'reconciled' WHERE id = :id");
        $this->db->bind('order_id', $orderId);
        $this->db->bind('id', $statementId);
        return $this->db->execute();
    }

    public function unreconcile($statementId) {
        $this->db->query("UPDATE bank_statements SET reconciled_order_id = NULL, status = 'unreconciled' WHERE id = :id");
        $this->db->bind('id', $statementId);
        return $this->db->execute();
    }

    public function getReconciliationSummary() {
        // Total mutasi bank masuk (amount > 0)
        $this->db->query("SELECT
            COUNT(*) as total_statements,
            SUM(CASE WHEN status = 'reconciled' THEN 1 ELSE 0 END) as reconciled_count,
            SUM(CASE WHEN status = 'unreconciled' THEN 1 ELSE 0 END) as unreconciled_count,
            SUM(CASE WHEN amount > 0 THEN amount ELSE 0 END) as total_bank_inflow,
            SUM(CASE WHEN status = 'reconciled' AND amount > 0 THEN amount ELSE 0 END) as reconciled_amount,
            SUM(CASE WHEN status = 'unreconciled' AND amount > 0 THEN amount ELSE 0 END) as unreconciled_amount
            FROM bank_statements");
        $summary = $this->db->single();

        // Hitung total nilai pesanan yang sudah direkonsiliasi
        $this->db->query("SELECT COALESCE(SUM(o.total_amount), 0) as order_total
            FROM bank_statements bs
            JOIN orders o ON bs.reconciled_order_id = o.id
            WHERE bs.status = 'reconciled'");
        $orderTotalData = $this->db->single();

        // Selisih: nilai mutasi bank rekonsiliasi vs nilai pesanan
        $reconciled_bank   = (float)($summary['reconciled_amount'] ?? 0);
        $reconciled_orders = (float)($orderTotalData['order_total'] ?? 0);

        return [
            'total_statements'    => (int)($summary['total_statements'] ?? 0),
            'reconciled_count'    => (int)($summary['reconciled_count'] ?? 0),
            'unreconciled_count'  => (int)($summary['unreconciled_count'] ?? 0),
            'total_bank_inflow'   => (float)($summary['total_bank_inflow'] ?? 0),
            'reconciled_amount'   => $reconciled_bank,
            'unreconciled_amount' => (float)($summary['unreconciled_amount'] ?? 0),
            'order_total'         => $reconciled_orders,
            'discrepancy'         => $reconciled_bank - $reconciled_orders, // +/- selisih
        ];
    }

    public function addBankStatement($data) {
        $this->db->query("INSERT INTO bank_statements (statement_date, description, amount, reference_code, status) VALUES (:date, :description, :amount, :ref, 'unreconciled')");
        $this->db->bind('date', $data['statement_date']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('amount', $data['amount']);
        $this->db->bind('ref', $data['reference_code']);
        return $this->db->execute();
    }
}
