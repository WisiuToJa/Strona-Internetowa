<?php
// Ustawienia bazy danych
$servername = "sql.24.svpj.link";
$username = "db_105646"; // Zastąp swoim użytkownikiem
$password = "7hkgj1nv"; // Zastąp swoim hasłem
$dbname = "db_105646";

// Połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Zmienna do przechowywania komunikatu
$message = "";

// Sprawdzenie, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $typ = $_POST['typ'];
    $pytania = [];
    
    // Przechowywanie odpowiedzi z formularza
    for ($i = 1; $i <= 9; $i++) {
        $pytania[] = $_POST["pytanie$i"];
    }

    // Sprawdzanie ostatniego zgłoszenia
    $stmt = $conn->prepare("SELECT last_submission_time FROM zgłoszenia ORDER BY last_submission_time DESC LIMIT 1");
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($last_submission_time);
        $stmt->fetch();
        
        // Obliczenie różnicy czasu
        $time_diff = strtotime("now") - strtotime($last_submission_time);
        
        // Sprawdzenie, czy minęło 24 godziny
        if ($time_diff < 86400) { // 86400 sekund = 24 godziny
            $message = "Możesz wysłać zgłoszenie tylko raz na 24 godziny.";
        } else {
            // Wstawianie danych do bazy
            $stmt = $conn->prepare("INSERT INTO zgłoszenia (typ, pytanie1, pytanie2, pytanie3, pytanie4, pytanie5, pytanie6, pytanie7, pytanie8, pytanie9,last_submission_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssssssssss", $typ, $pytania[0], $pytania[1], $pytania[2], $pytania[3], $pytania[4], $pytania[5], $pytania[6], $pytania[7], $pytania[8]);

            if ($stmt->execute()) {
                $message = "Pomyślnie wysłano zgłoszenie.";
            } else {
                $message = "Wystąpił błąd: " . $stmt->error;
            }
        }
    } else {
        // Wstawianie danych do bazy, jeśli nie było wcześniejszych zgłoszeń
        $stmt = $conn->prepare("INSERT INTO zgłoszenia (typ, pytanie1, pytanie2, pytanie3, pytanie4, pytanie5, pytanie6, pytanie7, pytanie8, pytanie9, last_submission_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssssssss", $typ, $pytania[0], $pytania[1], $pytania[2], $pytania[3], $pytania[4], $pytania[5], $pytania[6], $pytania[7], $pytania[8], $ip_address);

        if ($stmt->execute()) {
            $message = "Pomyślnie wysłano zgłoszenie.";
        } else {
            $message = "Wystąpił błąd: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formularz Zgłoszeniowy</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #3a1f1f;
            color: #FFFFFF;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        .container {
            text-align: center;
            background-color: #522222;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
        }

        h1 {
            font-size: 3rem;
            color: #FFFFFF;
        }

        h2 {
            margin: 20px 0;
        }

        p {
            margin-top: 10px;
            font-size: 1.1rem;
        }

        .btn {
            display: inline-block;
            background-color: #8d0000;
            color: #FFFFFF;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #b00000;
        }

        input[type="text"], select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #ffffff;
            color: #000000;
        }

        input[type="text"]:focus, select:focus {
            border-color: #8d0000;
            outline: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>Formularz Zgłoszeniowy</h1>
        </div>

        <form method="POST" action="">
            <h2>Wybierz typ</h2>
            <select name="typ" required>
                <option value="" disabled selected>Wybierz...</option>
                <option value="organizacja">Organizacja</option>
                <option value="gang">Gang</option>
            </select>

            <?php for ($i = 1; $i <= 9; $i++): ?>
                <label for="pytanie<?php echo $i; ?>">Pytanie <?php echo $i; ?>:</label>
                <input type="text" name="pytanie<?php echo $i; ?>" id="pytanie<?php echo $i; ?>" required>
            <?php endfor; ?>

            <div class="buttons">
                <button type="submit" class="btn">Zatwierdź</button>
            </div>
        </form>

        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        </div>
    </div>
</body>
</html>
