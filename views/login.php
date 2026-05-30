<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    if ($user->login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Neteisingas vartotojo vardas arba slaptažodis.';
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Prisijungimas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Prisijungimas</h1>

    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post">
        <label>Vartotojo vardas</label>
        <input type="text" name="username" required>

        <label>Slaptažodis</label>
        <input type="password" name="password" required>

        <button type="submit">Prisijungti</button>
    </form>

    <p>Neturite paskyros? <a href="register.php">Registruotis</a></p>
</div>
</body>
</html>
