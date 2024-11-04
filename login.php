<?php
session_start();

$servername = "sql.24.svpj.link";
$username = "db_105646"; // Zastąp swoim użytkownikiem
$password = "7hkgj1nv"; // Zastąp swoim hasłem
$dbname = "db_105646";

// Utworzenie połączenia
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Zapytanie do bazy danych
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Weryfikacja hasła
        if (password_verify($password, $user['password'])) {
            // Sprawdzenie autoryzacji
            if ($user['authorized'] == 1) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];

                // Przekierowanie na chronioną stronę
                header("Location: chuj.php");
                exit;
            } else {
                echo "<div class='error'>Nie masz dostępu do tego panelu.</div>";
                exit;
            }
        } else {
            echo "<div class='error'>Błędna nazwa użytkownika lub hasło.</div>";
            exit;
        }
    } else {
        echo "<div class='error'>Błędna nazwa użytkownika lub hasło.</div>";
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Logowanie</h1>
        <form action="" method="POST">
            <div class="input-group">
                <label for="username">Nazwa użytkownika</label>
                <input type="text" id="username" name="username" placeholder="Nazwa użytkownika" required>
            </div>
            <div class="input-group">
                <label for="password">Hasło</label>
                <input type="text" id="password" name="password" placeholder="Hasło" required>
            </div>
            <button type="submit" class="btn">Zaloguj</button>
        </form>
    </div>
</body>
</html>
