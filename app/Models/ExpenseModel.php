<?php

class ExpenseModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllExpenses($filters = []) {
        $query = "SELECT * FROM expenses WHERE 1=1 ";
        
        if (!empty($filters['period'])) {
            $period = $filters['period'];
            $date_from = date('Y-m-d', strtotime("-{$period} days"));
            $query .= " AND date >= :date_from ";
        }

        if (!empty($filters['category'])) {
            $query .= " AND category = :category ";
        }

        if (!empty($filters['search'])) {
            $query .= " AND (title LIKE :search OR description LIKE :search) ";
        }

        $query .= " ORDER BY date DESC, id DESC";
        
        $this->db->query($query);
        
        if (!empty($filters['period'])) {
            $this->db->bind('date_from', $date_from);
        }
        if (!empty($filters['category'])) {
            $this->db->bind('category', $filters['category']);
        }
        if (!empty($filters['search'])) {
            $this->db->bind('search', '%' . $filters['search'] . '%');
        }
        
        return $this->db->resultSet();
    }

    public function getExpenseById($id) {
        $this->db->query("SELECT * FROM expenses WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function addExpense($data) {
        $this->db->query("INSERT INTO expenses (title, category, amount, date, description) 
                          VALUES (:title, :category, :amount, :date, :description)");
        $this->db->bind('title', $data['title']);
        $this->db->bind('category', $data['category']);
        $this->db->bind('amount', $data['amount']);
        $this->db->bind('date', $data['date']);
        $this->db->bind('description', $data['description'] ?? null);
        return $this->db->execute();
    }

    public function updateExpense($id, $data) {
        $this->db->query("UPDATE expenses 
                          SET title = :title, category = :category, amount = :amount, date = :date, description = :description 
                          WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->bind('title', $data['title']);
        $this->db->bind('category', $data['category']);
        $this->db->bind('amount', $data['amount']);
        $this->db->bind('date', $data['date']);
        $this->db->bind('description', $data['description'] ?? null);
        return $this->db->execute();
    }

    public function deleteExpense($id) {
        $this->db->query("DELETE FROM expenses WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function getCategories() {
        return ['Operasional', 'Gaji', 'Sewa', 'Marketing', 'Lainnya'];
    }
}
