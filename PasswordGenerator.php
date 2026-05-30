<?php
class PasswordGenerator
{
    public $length;
    public $lowercase;
    public $uppercase;
    public $numbers;
    public $specials;

    public function __construct($length, $lowercase, $uppercase, $numbers, $specials)
    {
        $this->length = $length;
        $this->lowercase = $lowercase;
        $this->uppercase = $uppercase;
        $this->numbers = $numbers;
        $this->specials = $specials;
    }

    private function takeChars($chars, $count)
    {
        $result = '';

        for ($i = 0; $i < $count; $i++) {
            $nr = rand(0, strlen($chars) - 1);
            $result = $result . $chars[$nr];
        }

        return $result;
    }

    public function generate()
    {
        $sum = $this->lowercase + $this->uppercase + $this->numbers + $this->specials;

        if ($sum != $this->length) {
            return 'Klaida';
        }

        $password = '';
        $password = $password . $this->takeChars('abcdefghijklmnopqrstuvwxyz', $this->lowercase);
        $password = $password . $this->takeChars('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $this->uppercase);
        $password = $password . $this->takeChars('0123456789', $this->numbers);
        $password = $password . $this->takeChars('!@#$%&*.-_', $this->specials);

        return str_shuffle($password);
    }
}
?>
