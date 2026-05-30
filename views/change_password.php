<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/Database.php';
require_once '../classes/User.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];

    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    if ($user->changePassword($_SESSION['user_id'], $oldPassword, $newPassword)) {
        $message = 'Slaptažodis pakeistas. Prisijunkite iš naujo.';
        session_destroy();
    } else {
        $error = 'Senas slaptažodis neteisingas.';
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Slaptažodžio keitimas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Prisijungimo slaptažodžio keitimas</h1>

    <?php if ($message): ?><div class="message"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post">
        <label>Senas slaptažodis</label>
        <input type="password" name="old_password" required>

        <label>Naujas slaptažodis</label>
        <input type="password" name="new_password" required>

        <button type="submit">Pakeisti</button>
    </form>

    <p><a href="dashboard.php">Grįžti atgal</a></p>
</div>
</body>
</html>
