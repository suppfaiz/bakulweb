<?php
class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function registerUser($data) {
        $this->db->query("INSERT INTO users (username, email, password, phone, address, role, is_verified, verification_code) VALUES (:username, :email, :password, :phone, :address, 'customer', 0, :verification_code)");
        $this->db->bind('username', $data['username']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', password_hash($data['password'], PASSWORD_BCRYPT));
        $this->db->bind('phone', $data['phone']);
        $this->db->bind('address', $data['address']);
        $this->db->bind('verification_code', $data['verification_code']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function verifyUser($email, $code) {
        $this->db->query("SELECT * FROM users WHERE email = :email AND verification_code = :code");
        $this->db->bind('email', $email);
        $this->db->bind('code', $code);
        $user = $this->db->single();
        
        if ($user) {
            $this->db->query("UPDATE users SET is_verified = 1, verification_code = NULL WHERE email = :email");
            $this->db->bind('email', $email);
            return $this->db->execute();
        }
        return false;
    }

    public function updateVerificationCode($email, $code) {
        $this->db->query("UPDATE users SET verification_code = :code WHERE email = :email");
        $this->db->bind('code', $code);
        $this->db->bind('email', $email);
        return $this->db->execute();
    }

    public function updateUsername($id, $username) {
        $this->db->query("UPDATE users SET username = :username WHERE id = :id");
        $this->db->bind('username', $username);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function updatePassword($id, $new_password) {
        $this->db->query("UPDATE users SET password = :password WHERE id = :id");
        $this->db->bind('password', password_hash($new_password, PASSWORD_BCRYPT));
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function updateProfile($id, $username, $phone, $address) {
        $this->db->query("UPDATE users SET username = :username, phone = :phone, address = :address WHERE id = :id");
        $this->db->bind('username', $username);
        $this->db->bind('phone', $phone);
        $this->db->bind('address', $address);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function getCustomerCount() {
        $this->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'");
        return $this->db->single()['count'] ?? 0;
    }

    // [OTP DINONAKTIFKAN SEMENTARA] - langsung aktifkan akun tanpa verifikasi email
    public function activateUser($email) {
        $this->db->query("UPDATE users SET is_verified = 1, verification_code = NULL WHERE email = :email");
        $this->db->bind('email', $email);
        return $this->db->execute();
    }
}
