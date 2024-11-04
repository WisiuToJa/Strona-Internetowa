<?php
session_start();

// Plik do przechowywania zalogowanych użytkowników
$usersFile = 'active_users.json';

// Odczytanie obecnych zalogowanych użytkowników
$activeUsers = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

// Usunięcie użytkownika z listy
if (isset($_SESSION['username']) && in_array($_SESSION['username'], $activeUsers)) {
    $activeUsers = array_diff($activeUsers, [$_SESSION['username']]);
    file_put_contents($usersFile, json_encode(array_values($activeUsers)));
}

// Zakończenie sesji
session_destroy();

// Przekierowanie do strony logowania
header("Location: login.php");
exit;
?>
