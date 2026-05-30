<?php

class PromoModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getActivePromos() {
        $this->db->query("SELECT * FROM homepage_promos WHERE is_active = 1 ORDER BY sort_order ASC, id DESC");
        return $this->db->resultSet();
    }

    public function getAllPromos() {
        $this->db->query("SELECT * FROM homepage_promos ORDER BY sort_order ASC, id DESC");
        return $this->db->resultSet();
    }

    public function getPromoById($id) {
        $this->db->query("SELECT * FROM homepage_promos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addPromo($data) {
        $this->db->query("INSERT INTO homepage_promos (title, subtitle, image_path, link_url, sort_order, is_active) VALUES (:title, :subtitle, :image_path, :link_url, :sort_order, :is_active)");
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':subtitle', $data['subtitle']);
        $this->db->bind(':image_path', $data['image_path']);
        $this->db->bind(':link_url', $data['link_url']);
        $this->db->bind(':sort_order', $data['sort_order']);
        $this->db->bind(':is_active', $data['is_active']);
        return $this->db->execute();
    }

    public function updatePromo($id, $data) {
        $this->db->query("UPDATE homepage_promos SET title = :title, subtitle = :subtitle, image_path = :image_path, link_url = :link_url, sort_order = :sort_order, is_active = :is_active WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':subtitle', $data['subtitle']);
        $this->db->bind(':image_path', $data['image_path']);
        $this->db->bind(':link_url', $data['link_url']);
        $this->db->bind(':sort_order', $data['sort_order']);
        $this->db->bind(':is_active', $data['is_active']);
        return $this->db->execute();
    }

    public function deletePromo($id) {
        $this->db->query("DELETE FROM homepage_promos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
