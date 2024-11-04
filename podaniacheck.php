<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Database settings
$servername = "sql.24.svpj.link";
$username = "db_105646"; // Replace with your username
$password = "7hkgj1nv"; // Replace with your password
$dbname = "db_105646";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize applications array
$applications = [];
$selected_typ = '';
$selected_id = '';
$podanie = [];

// Fetch available types from the database for the dropdown menu
$types = [];
$type_query = "SELECT DISTINCT typ FROM zgłoszenia";
$type_result = $conn->query($type_query);

if ($type_result->num_rows > 0) {
    while ($type_row = $type_result->fetch_assoc()) {
        $types[] = $type_row['typ'];
    }
}

// Check if a type has been selected
if (isset($_POST['typ'])) {
    $selected_typ = $conn->real_escape_string($_POST['typ']);

    // Fetch applications by type
    $app_query = "SELECT id FROM zgłoszenia WHERE typ = '$selected_typ'";
    $app_result = $conn->query($app_query);

    if ($app_result->num_rows > 0) {
        while ($app_row = $app_result->fetch_assoc()) {
            $applications[] = $app_row['id'];
        }
    }
}

// Check if an application ID has been selected
if (isset($_POST['application_id'])) {
    $selected_id = $conn->real_escape_string($_POST['application_id']);

    // Fetch all fields for the selected application ID
    $sql = "SELECT * FROM zgłoszenia WHERE id = '$selected_id' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $podanie = $result->fetch_assoc();
    } else {
        echo "Brak danych dla wybranego podania.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podania Gang</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Your CSS styling */
    </style>
</head>
<body>
    <div class="container">
        <h1>Podania</h1>
        <p>Formularz podania o przystąpienie.</p>

        <!-- Form for selecting type -->
        <form method="POST" action="">
            <h2>Wybierz typ podania</h2>
            <select name="typ" required onchange="this.form.submit()">
                <option value="">-- Wybierz typ podania --</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>" <?php if ($selected_typ == $type) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($type); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- Form for selecting application ID if type is selected -->
        <?php if (!empty($applications)): ?>
            <form method="POST" action="">
                <input type="hidden" name="typ" value="<?php echo htmlspecialchars($selected_typ); ?>">
                <h2>Wybierz ID podania</h2>
                <select name="application_id" required onchange="this.form.submit()">
                    <option value="">-- Wybierz ID podania --</option>
                    <?php foreach ($applications as $app_id): ?>
                        <option value="<?php echo htmlspecialchars($app_id); ?>" <?php if ($selected_id == $app_id) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($app_id); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php endif; ?>

        <!-- Display the full application data if an application ID is selected -->
        <?php if (!empty($podanie)): ?>
            <div class="application-details">
                <h2>Wybrane Podanie (ID: <?php echo htmlspecialchars($selected_id); ?>)</h2>
                <p><strong>Typ:</strong> <?php echo htmlspecialchars($podanie['typ']); ?></p>
                <?php for ($i = 1; $i <= 9; $i++): ?>
                    <?php if (!empty($podanie["pytanie$i"])): ?>
                        <p><strong>Pytanie <?php echo $i; ?>:</strong> <?php echo htmlspecialchars($podanie["pytanie$i"]); ?></p>
                    <?php endif; ?>
                <?php endfor; ?>
                <p><strong>Last Submission Time:</strong> <?php echo htmlspecialchars($podanie['last_submission_time']); ?></p>
                <p><strong>Data Wysłania:</strong> <?php echo htmlspecialchars($podanie['data_wyslania']); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
