<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/Database.php';
require_once '../classes/PasswordGenerator.php';
require_once '../classes/PasswordEntry.php';

$database = new Database();
$db = $database->connect();
$passwordEntry = new PasswordEntry($db);

$generatedPassword = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
    $length = (int)$_POST['length'];
    $lowercase = (int)$_POST['lowercase'];
    $uppercase = (int)$_POST['uppercase'];
    $numbers = (int)$_POST['numbers'];
    $specials = (int)$_POST['specials'];

    $generator = new PasswordGenerator($length, $lowercase, $uppercase, $numbers, $specials);
    $generatedPassword = $generator->generate();

    if ($generatedPassword == 'Klaida') {
        $error = 'Simbolių kiekiai turi sudaryti bendrą ilgį.';
        $generatedPassword = '';
    }
}

$passwords = $passwordEntry->getAll($_SESSION['user_id'], $_SESSION['user_key']);
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Valdymo langas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Slaptažodžių generatorius ir saugykla</h1>

    <div class="menu">
        Prisijungęs vartotojas: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
        | <a href="add_password.php">Pridėti slaptažodį</a>
        | <a href="change_password.php">Keisti prisijungimo slaptažodį</a>
        | <a href="logout.php">Atsijungti</a>
    </div>

    <h2>Slaptažodžio generavimas</h2>

    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post">
        <label>Bendras slaptažodžio ilgis</label>
        <input type="number" name="length" value="9" min="1" required>

        <label>Mažųjų raidžių kiekis</label>
        <input type="number" name="lowercase" value="2" min="0" required>

        <label>Didžiųjų raidžių kiekis</label>
        <input type="number" name="uppercase" value="3" min="0" required>

        <label>Skaičių kiekis</label>
        <input type="number" name="numbers" value="2" min="0" required>

        <label>Specialių simbolių kiekis</label>
        <input type="number" name="specials" value="2" min="0" required>

        <button type="submit" name="generate">Generuoti</button>
    </form>

    <?php if ($generatedPassword): ?>
        <div class="message">
            Sugeneruotas slaptažodis: <strong><?= htmlspecialchars($generatedPassword) ?></strong>
        </div>
    <?php endif; ?>

    <h2>Išsaugoti slaptažodžiai</h2>

    <table>
        <tr>
            <th>Pavadinimas</th>
            <th>Slaptažodis</th>
            <th>Data</th>
        </tr>
        <?php foreach ($passwords as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= htmlspecialchars($item['password']) ?></td>
                <td><?= htmlspecialchars($item['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
