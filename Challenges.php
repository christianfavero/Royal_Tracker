<?php
session_start();
require_once "config.php";
require_once "cr-api.php"; 

if(!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Errore connessione DB: " . $conn->connect_error); }

$user_id = $_SESSION["user_id"];

// 1. Recupero Gamertag aggiornato
$stmt = $conn->prepare("SELECT player_tag FROM users WHERE id_user = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data || empty($user_data["player_tag"])) { die("GamerTag non trovato."); }

$gamertag = strtoupper(trim($user_data["player_tag"]));
if ($gamertag[0] !== '#') { $gamertag = '#' . $gamertag; }

// 2. Chiamata API
$api = new ClashRoyaleAPI($clash_api_key);
$player = $api->getPlayer($gamertag);

$playerTrophies = intval($player["trophies"] ?? 0);
$playerCardCount = isset($player["cards"]) ? count($player["cards"]) : 0;

// 3. Recupero Sfide con LEFT JOIN
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
            <li><a href="challenges.php">Challenges</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
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
        <p style="text-align:center;">Trofei: <strong><?= $playerTrophies ?></strong> | Carte Sbloccate: <strong><?= $playerCardCount ?></strong></p>
        
        <div class="challenges-container" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; padding: 20px;">
<div class="challenges-container" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; padding: 20px;">
    <?php while ($row = $challenges->fetch_assoc()): 
        $id_ch = $row['id_challenge'];
        $target = intval($row['target_value']);
        $type = $row['type']; 
        $reward = intval($row['reward_points']); 
        
        // Valore attuale dall'API
        $currentVal = ($type == 'coppe') ? $playerTrophies : $playerCardCount;
        
        // LOGICA DINAMICA
        $hasRequirements = ($target > 0 && $currentVal >= $target);
        $wasDone = ($row['gia_fatta'] == 1);

        if ($hasRequirements && !$wasDone) {
            // Sblocca la sfida
            $ins = $conn->prepare("INSERT INTO user_challenge (id_user, id_challenge, completed, completed_at) 
                                   VALUES (?, ?, 1, NOW()) 
                                   ON DUPLICATE KEY UPDATE completed = 1");
            $ins->bind_param("ii", $user_id, $id_ch);
            $ins->execute();
            $isDone = true;
        } 
        elseif (!$hasRequirements && $wasDone) {
            // TOGLIE il completamento se i requisiti non ci sono più
            $upd = $conn->prepare("UPDATE user_challenge SET completed = 0 WHERE id_user = ? AND id_challenge = ?");
            $upd->bind_param("ii", $user_id, $id_ch);
            $upd->execute();
            $isDone = false;
        } 
        else {
            $isDone = $wasDone;
        }

        // Calcolo percentuale
        $percent = ($target > 0) ? min(($currentVal / $target) * 100, 100) : 0;
    ?>

    <div class="challenge-card" style="background: white; border: 1px solid #ddd; padding: 20px; width: 300px; border-radius: 10px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h3 style="margin:0; color: #333;"><?= htmlspecialchars($row['title']) ?></h3>
        <p style="color: #666; font-size: 0.9em;"><?= htmlspecialchars($row['description']) ?></p>
        
        <p style="font-weight: bold; color: #f5b700; margin: 10px 0;">Premio: +<?= $reward ?> PT</p>

        <div style="background: #eee; height: 12px; border-radius: 6px; margin: 10px 0; overflow: hidden; border: 1px solid #ddd;">
            <div style="background: <?= $isDone ? '#2ecc71' : '#3498db' ?>; width: <?= $percent ?>%; height: 100%; transition: width 0.5s;"></div>
        </div>

        <p style="font-size: 0.85em;"><?= $currentVal ?> / <?= $target ?> <?= ($type == 'coppe' ? 'Coppe' : 'Carte') ?></p>

        <?php if ($isDone): ?>
            <div style="color: #27ae60; font-weight: bold; background: #eafaf1; padding: 8px; border-radius: 5px; border: 1px solid #2ecc71;">✅ COMPLETATA</div>
        <?php else: ?>
            <div style="color: #7f8c8d; font-style: italic; background: #f9f9f9; padding: 8px; border-radius: 5px; border: 1px solid #ddd;">🕒 In corso...</div>
        <?php endif; ?>
    </div>

    <?php endwhile; ?>

    </main>
</body>
</html>