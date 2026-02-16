<?php
session_start();
require_once "../config.php";

// Connessione database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

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
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username già esistente.";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO users (username, password, gamertag) VALUES (?, ?, ?)");
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
    <title>Registrazione</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">

<div class="auth-container">
    <div class="auth-box">
        <h2>Crea Account</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-msg"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="gamertag" placeholder="GamerTag (#ABC123)" required>

            <button type="submit">Registrati</button>
        </form>

        <p class="auth-link">
            Hai già un account?
            <a href="login.php">Accedi</a>
        </p>
    </div>
</div>

</body>
</html>
