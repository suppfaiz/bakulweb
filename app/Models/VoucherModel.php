<?php

class VoucherModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllVouchers() {
        $this->db->query("SELECT * FROM vouchers ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function getVoucherById($id) {
        $this->db->query("SELECT * FROM vouchers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getVoucherByCode($code) {
        $this->db->query("SELECT * FROM vouchers WHERE BINARY code = :code AND is_active = 1");
        $this->db->bind(':code', $code);
        return $this->db->single();
    }

    public function addVoucher($data) {
        $this->db->query("INSERT INTO vouchers (code, discount_type, discount_amount, min_spend, expiry_date, usage_limit, is_active) VALUES (:code, :discount_type, :discount_amount, :min_spend, :expiry_date, :usage_limit, :is_active)");
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':discount_type', $data['discount_type']);
        $this->db->bind(':discount_amount', $data['discount_amount']);
        $this->db->bind(':min_spend', $data['min_spend']);
        $this->db->bind(':expiry_date', empty($data['expiry_date']) ? null : $data['expiry_date']);
        $this->db->bind(':usage_limit', empty($data['usage_limit']) ? null : (int)$data['usage_limit']);
        $this->db->bind(':is_active', $data['is_active']);
        return $this->db->execute();
    }

    public function updateVoucher($id, $data) {
        $this->db->query("UPDATE vouchers SET code = :code, discount_type = :discount_type, discount_amount = :discount_amount, min_spend = :min_spend, expiry_date = :expiry_date, usage_limit = :usage_limit, is_active = :is_active WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':discount_type', $data['discount_type']);
        $this->db->bind(':discount_amount', $data['discount_amount']);
        $this->db->bind(':min_spend', $data['min_spend']);
        $this->db->bind(':expiry_date', empty($data['expiry_date']) ? null : $data['expiry_date']);
        $this->db->bind(':usage_limit', empty($data['usage_limit']) ? null : (int)$data['usage_limit']);
        $this->db->bind(':is_active', $data['is_active']);
        return $this->db->execute();
    }

    public function deleteVoucher($id) {
        $this->db->query("DELETE FROM vouchers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function incrementUsage($code) {
        $this->db->query("UPDATE vouchers SET usage_count = usage_count + 1 WHERE code = :code");
        $this->db->bind(':code', $code);
        return $this->db->execute();
    }
}
