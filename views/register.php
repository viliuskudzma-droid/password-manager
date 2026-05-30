<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $error = 'Užpildykite visus laukus.';
    } else {
        $database = new Database();
        $db = $database->connect();
        $user = new User($db);

        if ($user->register($username, $password)) {
            $message = 'Registracija sėkminga. Dabar galite prisijungti.';
        } else {
            $error = 'Toks vartotojas jau egzistuoja.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Registracija</h1>

    <?php if ($message): ?><div class="message"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post">
        <label>Vartotojo vardas</label>
        <input type="text" name="username" required>

        <label>Slaptažodis</label>
        <input type="password" name="password" required>

        <button type="submit">Registruotis</button>
    </form>

    <p>Jau turite paskyrą? <a href="login.php">Prisijungti</a></p>
</div>
</body>
</html>
