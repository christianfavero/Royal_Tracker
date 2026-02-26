<?php
session_start();
require_once "config.php";
require_once "cr-api.php"; 

if(!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// connessione e recupero gamertag
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error)
    die("Errore connessione DB: " . $conn->connect_error);

$user_id = $_SESSION["user_id"];

// Recuperiamo il tag dal DB invece che dalla sessione
$stmt = $conn->prepare("SELECT player_tag FROM users WHERE id_user = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data || empty($user_data["player_tag"]))
    die("GamerTag non trovato nel database.");

$gamertag = strtoupper(trim($user_data["player_tag"]));

if ($gamertag[0] !== '#')
    $gamertag = '#' . $gamertag;

//API
$api = new ClashRoyaleAPI($clash_api_key);
$player = $api->getPlayer($gamertag);

// Prende i dati reali
$playerTrophies = intval($player["trophies"] ?? 0);
$playerCardCount = isset($player["cards"]) ? count($player["cards"]) : 0;

// Recupera sfide
$query = "SELECT c.*, uc.completed AS gia_fatta 
          FROM challenges c 
          LEFT JOIN user_challenge uc ON c.id_challenge = uc.id_challenge AND uc.id_user = ?";

$stmt_ch = $conn->prepare($query);
$stmt_ch->bind_param("i", $user_id);
$stmt_ch->execute();
$challenges = $stmt_ch->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Challenges - Royal Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src = "Logo.png">
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

    <main class="home-section">
        <br><br><br>
        <h2 style="text-align:center;">Le tue Sfide</h2>
        <p style="text-align:center;">Coppe attuali: <strong><?= $playerTrophies ?></strong></p>
        
        <div class="challenges-container" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; padding: 20px;">
            <?php while ($row = $challenges->fetch_assoc()): 
                $isDone = ($row['gia_fatta'] == 1);
                $type = $row['type']; 
                $target = intval($row['target_value']);
                $currentVal = ($type == 'coppe') ? $playerTrophies : $playerCardCount;

                // Sincronizzazione automatica se hai raggiunto l'obiettivo
                if (!$isDone && $target > 0 && $currentVal >= $target) {
                   // Modifica la riga dell'INSERT aggiungendo NOW()
                    $ins = $conn->prepare(
                    "INSERT INTO user_challenge (id_user, id_challenge, completed, completed_at) 
                     VALUES (?, ?, 1, NOW()) 
                     ON DUPLICATE KEY UPDATE completed = 1, completed_at = NOW()"
                    );
                    $ins->bind_param("ii", $user_id, $row['id_challenge']);
                    $ins->execute();
                    $isDone = true;
                }
                $percent = ($target > 0) ? ($currentVal / $target) * 100 : 0;
                if($percent > 100) $percent = 100;
            ?>

            <div class="challenge-card">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p style="color: #666;"><?= htmlspecialchars($row['description']) ?></p>
                <div class = "div-challenge">
                    <div style="background: <?= $isDone ? '#2ecc71' : '#3498db' ?>; width: <?= $percent ?>%; height: 100%;"></div>
                </div>
                <p style="font-weight: bold;"><?= $currentVal ?> / <?= $target ?></p>
                <?php if ($isDone): ?>
                    <div class = "challenge-completata">SBLOCCATA!</div>
                <?php else: ?>
                    <span class = "challenge-in-corso">IN CORSO...</span>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>