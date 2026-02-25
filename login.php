<?php
session_start();
require "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $gamertag = strtoupper(trim($_POST["gamertag"]));
    $password = trim($_POST["password"]);

    if ($gamertag === "" || $password === "") {
        $error = "Inserisci tutti i dati";
    } else {
        // 1. Cerchiamo l'utente solo per Player Tag
        $stmt = $conn->prepare("SELECT id_user, username, player_tag, password_hash FROM users WHERE player_tag = ?");
        $stmt->bind_param("s", $gamertag);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

        
            if (password_verify($password, $user["password_hash"])) {
                // LOGIN SUCCESSO
                $_SESSION["user_id"] = $user["id_user"];
                $_SESSION["username"] = $user["username"]; 
                $_SESSION["gamertag"] = $user["player_tag"]; 

                header("Location: index.php");
                exit;
            } else {
                $error = "Password errata";
            }
        } else {
            $error = "Gamer Tag non trovato";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Clash Royale Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
<nav class="navbar">
        <div class="logo">
            <img src="Logo.png">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="Cards.php">Carte</a></li>
            <li><a href="Challenges.php" class="requires-login">Challenges</a></li>
            <li><a href="Social.php" class="requires-login">Community</a></li>
            <li><a href="Video.php" class="requires-login">Video</a></li>
        
        </ul>

        <ul class="nav-links">
            <?php if(isset($_SESSION["user_id"])): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Accedi</a></li>
                <li><a href="register.php">Registrati</a></li>
            <?php endif; ?>
        </ul>
    </nav>
<br><br><br><br>
<div class="login-card">
    <div class="auth-card"> 
    <h2>Entra nell’Arena</h2>
    <p class="subtitle">Inserisci il tuo Gamer Tag per iniziare</p>
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div><br><br>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label for="gamertag"></label>
            <input type="text" id="gamertag" name="gamertag" placeholder="#ABC123" required>
            <label for="gamertag"></label>
          <input  type="password" id="password" name="password" placeholder="*****" required>
        </div>
        <button type="submit" class="login-btn">Entra</button>
    </form>
</div>

</body>
</html>
