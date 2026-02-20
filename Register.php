<?php
session_start();
require "config.php";


// Connessione database
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Errore connessione: " . $conn->connect_error);
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $gamertag = trim($_POST["gamertag"]);

    if (empty($username) || empty($password) || empty($gamertag)) {
        $error = "Tutti i campi sono obbligatori.";
    } else {

        // Controlla se username esiste già
        $stmt = $conn->prepare("SELECT id_user FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username già esistente.";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO users (username, password_hash, player_tag) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $hashedPassword, $gamertag);

            if ($insert->execute()) {
                $success = "Registrazione completata! Puoi effettuare il login.";
            } else {
                $error = "Errore durante la registrazione.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione - Royal Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo"><a href="index.php">Royal Tracker</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="decks.php">Decks</a></li>
        </ul>
    </nav>

    <header class="auth-header">
        <h1><a href="index.php">Royal Tracker</a></h1>
    </header>

    <div class="form-container-centered">
        <div class="auth-card">
            <h2>Crea Account</h2>
            <p class="subtitle">Inserisci i tuoi dati per registrarti</p>

            <?php if ($error) echo "<p style='color:red; margin-bottom:10px;'>$error</p>"; ?>
            <?php if ($success) echo "<p style='color:green; margin-bottom:10px;'>$success</p>"; ?>

            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="gamertag" placeholder="GamerTag (#ABC123)" required>
                <button type="submit">Registrati</button>
            </form>
            
            <p class="auth-link">Hai già un account? <a href="login.php">Accedi</a></p>
        </div>
    </div>

</body>
</html>
