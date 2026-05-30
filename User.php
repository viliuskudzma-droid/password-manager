<?php
require_once __DIR__ . '/Encryptor.php';

class User
{
    private $db;
    private $encryptor;

    public function __construct($db)
    {
        $this->db = $db;
        $this->encryptor = new Encryptor();
    }

    public function register($username, $password)
    {
        $sql = 'SELECT id FROM users WHERE username = ?';
        $query = $this->db->prepare($sql);
        $query->execute(array($username));

        if ($query->fetch()) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $key = bin2hex(random_bytes(16));
        $encryptedKey = $this->encryptor->encrypt($key, $password);

        $sql = 'INSERT INTO users (username, password_hash, encrypted_key) VALUES (?, ?, ?)';
        $query = $this->db->prepare($sql);
        $query->execute(array($username, $hash, $encryptedKey));

        return true;
    }

    public function login($username, $password)
    {
        $sql = 'SELECT * FROM users WHERE username = ?';
        $query = $this->db->prepare($sql);
        $query->execute(array($username));
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_key'] = $this->encryptor->decrypt($user['encrypted_key'], $password);

        return true;
    }

    public function changePassword($userId, $oldPassword, $newPassword)
    {
        $sql = 'SELECT * FROM users WHERE id = ?';
        $query = $this->db->prepare($sql);
        $query->execute(array($userId));
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        if (!password_verify($oldPassword, $user['password_hash'])) {
            return false;
        }

        $key = $this->encryptor->decrypt($user['encrypted_key'], $oldPassword);
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $newEncryptedKey = $this->encryptor->encrypt($key, $newPassword);

        $sql = 'UPDATE users SET password_hash = ?, encrypted_key = ? WHERE id = ?';
        $query = $this->db->prepare($sql);
        $query->execute(array($newHash, $newEncryptedKey, $userId));

        return true;
    }
}
?>
