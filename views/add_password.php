<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/Database.php';
require_once '../classes/PasswordEntry.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $password = trim($_POST['password']);

    if ($title === '' || $password === '') {
        $error = 'Užpildykite visus laukus.';
    } else {
        $database = new Database();
        $db = $database->connect();
        $passwordEntry = new PasswordEntry($db);
        $passwordEntry->add($_SESSION['user_id'], $title, $password, $_SESSION['user_key']);
        $message = 'Slaptažodis išsaugotas.';
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Pridėti slaptažodį</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Pridėti slaptažodį</h1>

    <?php if ($message): ?><div class="message"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post">
        <label>Svetainės arba programos pavadinimas</label>
        <input type="text" name="title" placeholder="Pvz. Gmail" required>

        <label>Slaptažodis</label>
        <input type="text" name="password" required>

        <button type="submit">Išsaugoti</button>
    </form>

    <p><a href="dashboard.php">Grįžti atgal</a></p>
</div>
</body>
</html>
