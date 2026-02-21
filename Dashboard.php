<?php
session_start();
require_once "config.php";
require_once "cr-api.php";

// Se non loggato → vai al login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Connessione DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Errore connessione DB: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];

// Recupero gamertag dal DB
$stmt = $conn->prepare("SELECT player_tag FROM users WHERE id_user = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$gamertag = strtoupper(trim($user["player_tag"]));
if ($gamertag[0] !== '#') {
    $gamertag = '#' . $gamertag;
}

// Chiamata API
$api = new ClashRoyaleAPI($clash_api_key);
$player = $api->getPlayer($gamertag);

// ... dopo aver creato l'oggetto $api = new ClashRoyaleAPI($apiKey);

$battleLog = $api->getBattleLog($gamertag);

// Prendiamo solo le prime 5 partite se non ci sono errori
$lastFiveBattles = [];
if (!isset($battleLog['error'])) {
    $lastFiveBattles = array_slice($battleLog, 0, 5);
}
// Controllo se API ha restituito dati
if (!$player || isset($player["reason"])) {
    $error = "Impossibile recuperare i dati del giocatore. Controlla il GamerTag o la chiave API.";
}
?>



<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Royal Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
        <div class="logo">
            <a href="index.php">Royal Tracker</a>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="Cards.php">Cards</a></li>
            <li><a href="challenges.php" class="requires-login">Challenges</a></li>
            <li><a href="community.php" class="requires-login">Community</a></li>
            <li><a href="videos.php" class="requires-login">Video</a></li>
        </ul>
</nav>  
<!-- ================= NAVBAR ================= -->
<main class="home-sections">
    <?php if(isset($error) && !empty($error)): ?>
        <section class="section">
            <h2>Errore</h2>
            <p><?php echo htmlspecialchars($error); ?></p>
        </section>
    <?php else: ?>
        <section class="section">
            <h2>Benvenuto <?php echo htmlspecialchars($player["name"] ?? "Guerriero"); ?></h2>
            <p>GamerTag: <?php echo htmlspecialchars($gamertag); ?></p>

            <div class="cards-container">
                
                <div class="card">
                    <h3>Livello</h3>
                    <p><?php echo $player["expLevel"] ?? "N/D"; ?></p>
                </div>

                <div class="card">
                    <h3>Trofei</h3>
                    <p><?php echo $player["trophies"] ?? "0"; ?></p>
                </div>

                <div class="card">
                    <h3>Vittorie</h3>
                    <p><?php echo $player["wins"] ?? "0"; ?></p>
                </div>

                <div class="card">
                    <h3>Sconfitte</h3>
                    <p><?php echo $player["losses"] ?? "0"; ?></p>
                </div>

                <div class="card">
                    <h3>Miglior Record</h3>
                    <p><?php echo $player["bestTrophies"] ?? "N/D"; ?></p>
                </div>

            </div>
        </section>
    <?php endif; ?>
</main>
<section class="section">
    <h2 style="text-align: center; margin-bottom: 30px;">Ultime 5 Partite</h2>
    <div class="battle-list">
        <?php foreach($lastFiveBattles as $battle): ?>
         <div class="battle-row">
    <div class="player-side">
        <h4>Tu</h4>
        <div class="deck-images">
            <?php foreach($battle["team"][0]["cards"] as $card): ?>
                <img src="<?php echo $card['iconUrls']['medium']; ?>">
            <?php endforeach; ?>
        </div>
    </div>

    <div class="battle-vs">VS</div>

    <div class="player-side">
        <h4><?php echo htmlspecialchars($battle["opponent"][0]["name"]); ?>
        <div class="deck-images">
            <?php foreach($battle["opponent"][0]["cards"] as $card): ?>
                <img src="<?php echo $card['iconUrls']['medium']; ?>">
            <?php endforeach; ?>
        </div>
        </h4>
    </div>
</div>
        <?php endforeach; ?>
    </div>
</section>

</body>
</html>
