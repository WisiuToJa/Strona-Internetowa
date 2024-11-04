<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Odczytanie liczby zalogowanych użytkowników
$usersFile = 'active_users.json';
$loggedInUsers = file_exists($usersFile) ? count(json_decode(file_get_contents($usersFile), true)) : 0;
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pulpit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Witaj, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Status: Zalogowanych użytkowników: <?php echo $loggedInUsers; ?></p>

        <div class="buttons">
            <a href="podaniacheck.php" class="btn">Sprawdzanie Podan</a>
            <a href="lista.php" class="btn">Lista Osób</a>
        </div>
    </div>
</body>
</html>
