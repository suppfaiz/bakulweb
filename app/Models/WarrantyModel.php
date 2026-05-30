<?php
// app/Models/WarrantyModel.php

class WarrantyModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllClaims() {
        $this->db->query("SELECT w.*, u.username, u.email 
                          FROM warranty_claims w 
                          JOIN users u ON w.user_id = u.id 
                          ORDER BY w.created_at DESC");
        return $this->db->resultSet();
    }

    public function getClaimById($id) {
        $this->db->query("SELECT w.*, u.username, u.email 
                          FROM warranty_claims w 
                          JOIN users u ON w.user_id = u.id 
                          WHERE w.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function updateClaimStatus($id, $status) {
        $this->db->query("UPDATE warranty_claims SET status = :status WHERE id = :id");
        $this->db->bind('status', $status);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function getClaimsByUser($userId) {
        $this->db->query("SELECT * FROM warranty_claims WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind('user_id', $userId);
        return $this->db->resultSet();
    }
}
