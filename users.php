<?php
session_start();

// Dodaj aktywnego użytkownika do pliku
function addActiveUser($username) {
    $activeUsers = [];
    // Wczytaj aktualnych zalogowanych użytkowników z pliku
    if (file_exists('active_users.json')) {
        $activeUsers = json_decode(file_get_contents('active_users.json'), true);
    }

    // Dodaj nowego użytkownika, jeśli nie jest już na liście
    if (!in_array($username, $activeUsers)) {
        $activeUsers[] = $username;
        file_put_contents('active_users.json', json_encode($activeUsers));
    }
}

// Usuń użytkownika z pliku, gdy się wyloguje
function removeActiveUser($username) {
    $activeUsers = [];
    // Wczytaj aktualnych zalogowanych użytkowników z pliku
    if (file_exists('active_users.json')) {
        $activeUsers = json_decode(file_get_contents('active_users.json'), true);
    }

    // Usuń użytkownika, jeśli istnieje na liście
    $activeUsers = array_diff($activeUsers, [$username]);
    file_put_contents('active_users.json', json_encode(array_values($activeUsers)));
}

// Zlicz aktywnych użytkowników
function countActiveUsers() {
    if (file_exists('active_users.json')) {
        $activeUsers = json_decode(file_get_contents('active_users.json'), true);
        return count($activeUsers);
    }
    return 0;
}

// Dodaj aktywnego użytkownika, jeśli jesteśmy w sesji
if (isset($_SESSION['username'])) {
    addActiveUser($_SESSION['username']);
}
?>
