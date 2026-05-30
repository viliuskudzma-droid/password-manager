<?php
require_once __DIR__ . '/Encryptor.php';

class PasswordEntry
{
    private $db;
    private $encryptor;

    public function __construct($db)
    {
        $this->db = $db;
        $this->encryptor = new Encryptor();
    }

    public function add($userId, $title, $password, $key)
    {
        $encryptedPassword = $this->encryptor->encrypt($password, $key);

        $sql = 'INSERT INTO passwords (user_id, title, encrypted_password) VALUES (?, ?, ?)';
        $query = $this->db->prepare($sql);
        $query->execute(array($userId, $title, $encryptedPassword));
    }

    public function getAll($userId, $key)
    {
        $sql = 'SELECT * FROM passwords WHERE user_id = ? ORDER BY created_at DESC';
        $query = $this->db->prepare($sql);
        $query->execute(array($userId));
        $items = $query->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($items); $i++) {
            $items[$i]['password'] = $this->encryptor->decrypt($items[$i]['encrypted_password'], $key);
        }

        return $items;
    }
}
?>
