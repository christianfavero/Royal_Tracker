<?php
session_start();
require "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $gamertag = strtoupper(trim($_POST["gamertag"]));

    if ($gamertag === "") {
        $error = "Inserisci il tuo Gamer Tag!";
    } else {
        $stmt = $conn->prepare("SELECT id_user, username, player_tag FROM users WHERE player_tag = ?");
        $stmt->bind_param("s", $gamertag);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $_SESSION["user_id"] = $user["id_user"];
    $_SESSION["username"] = $user["username"]; 

    $_SESSION["gamertag"] = $user["player_tag"]; 
   
        } else {
            $insert = $conn->prepare("INSERT INTO users (player_tag) VALUES (?)");
            $insert->bind_param("s", $gamertag);
            $insert->execute();
            $_SESSION["user_id"] = $insert->insert_id;
            $_SESSION["gamertag"] = $gamertag;
        }
        header("Location: index.php");
        exit;
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
        <div class="logo"><a href="index.php">Royal Tracker</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="decks.php">Decks</a></li>
        </ul>
    </nav>
<br><br><br><br>
<div class="login-card">
    <div class="auth-card"> 
    <h2>Entra nell’Arena</h2>
    <p class="subtitle">Inserisci il tuo Gamer Tag per iniziare</p>
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label for="gamertag"></label>
            <input type="text" id="gamertag" name="gamertag" placeholder="#ABC123" required>
        </div>
        <button type="submit" class="login-btn">Entra</button>
    </form>
</div>

</body>
</html>
