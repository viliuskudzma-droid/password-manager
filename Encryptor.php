<?php
class Encryptor
{
    private $method = 'AES-256-CBC';

    public function encrypt($text, $key)
    {
        $secretKey = hash('sha256', $key, true);
        $iv = random_bytes(16);

        $encrypted = openssl_encrypt($text, $this->method, $secretKey, OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv . $encrypted);
    }

    public function decrypt($text, $key)
    {
        $data = base64_decode($text);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        $secretKey = hash('sha256', $key, true);

        return openssl_decrypt($encrypted, $this->method, $secretKey, OPENSSL_RAW_DATA, $iv);
    }
}
?>
