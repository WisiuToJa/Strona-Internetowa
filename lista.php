<?php
// Dane dostępu do bazy danych
$host = "sql.24.svpj.link";
$port = 3306;
$dbname = "db_105646";
$username = "db_105646";
$password = "7hkgj1nv";

// Utworzenie połączenia z bazą danych
$mysqli = new mysqli($host, $username, $password, $dbname, $port);

// Sprawdzenie połączenia
if ($mysqli->connect_error) {
    die("Błąd połączenia: " . $mysqli->connect_error);
}

// Zapytanie SQL do pobrania zalogowanych użytkowników w ciągu ostatnich 30 minut
$sql = "SELECT username, last_login FROM users WHERE last_login > NOW() - INTERVAL 30 MINUTE";
$result = $mysqli->query($sql);

// Sprawdzenie i wyświetlenie wyników
if ($result->num_rows > 0) {
    echo "<h2>Aktualnie zalogowani użytkownicy:</h2><ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row["username"]) . " - Ostatnie logowanie: " . $row["last_login"] . "</li>";
    }
    echo "</ul>";
} else {
    echo "Brak zalogowanych użytkowników.";
}

// Zamknięcie połączenia
$mysqli->close();
?>
