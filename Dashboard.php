<?php
session_start();
require_once "config.php";
require_once "cr-api.php";

/* =========================
   CONTROLLO LOGIN
========================= */
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

/* =========================
   CONNESSIONE DB
========================= */
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Errore connessione DB: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];

/* =========================
   RECUPERO GAMERTAG
========================= */
$stmt = $conn->prepare("SELECT player_tag FROM users WHERE id_user = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || empty($user["player_tag"])) {
    die("GamerTag non trovato.");
}

$gamertag = strtoupper(trim($user["player_tag"]));
if ($gamertag[0] !== '#') {
    $gamertag = '#' . $gamertag;
}

/* =========================
   CHIAMATA API
========================= */
/* =========================
   CHIAMATA API
========================= */
$api = new ClashRoyaleAPI($clash_api_key);

$player = $api->getPlayer($gamertag);

// var_dump($player); die();

// Controllo robusto: se c'è un errore o non è un array valido
if (isset($player["error"]) || isset($player["reason"]) || !isset($player["name"])) {
    $error = "Errore API: " . ($player["message"] ?? $player["reason"] ?? "Giocatore non trovato.");
    $player = null; // Evita che l'HTML provi a leggerlo
} else {
    // Solo se il giocatore esiste, procediamo con il resto
    $battleLog = $api->getBattleLog($gamertag);
    $lastFiveBattles = [];
    if (!isset($battleLog["error"]) && is_array($battleLog)) {
        $lastFiveBattles = array_slice($battleLog, 0, 5);
    }
    $playerCards = $api->getPlayerCards($gamertag);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Royal Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
        <div class="logo">
            <img src="Logo.png">
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="Cards.php">Carte</a></li>
            <li><a href="challenges.php" class="requires-login">Challenges</a></li>
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

<main class="home-sections">
<br><br><br>
    <div class="view-selector-container">
        <select id="viewSelector" onchange="switchView(this.value)" class="view-selector">
            <option value="stats">Informazioni Generali</option>
            <option value="collection">Collezione Carte</option>
        </select>
    </div>

    <?php if(isset($error)): ?>
        <section class="section">
            <h2>Errore</h2>
            <p><?php echo htmlspecialchars($error); ?></p>
        </section>
    <?php else: ?>

        <div id="section-stats">
            
            <section class="section">
                <h2>Benvenuto <?php echo htmlspecialchars($player["name"]); ?></h2>
                <p>GamerTag: <?php echo htmlspecialchars($gamertag); ?></p>

                <div class="cards-container">
                    <div class="card"><h3>Livello</h3><p><?php echo $player["expLevel"]; ?></p></div>
                    <div class="card"><h3>Coppe</h3><p><?php echo $player["trophies"]; ?></p></div>
                    <div class="card"><h3>Vittorie</h3><p><?php echo $player["wins"]; ?></p></div>
                    <div class="card"><h3>Sconfitte</h3><p><?php echo $player["losses"]; ?></p></div>
                    <div class="card"><h3>Record Coppe</h3><p><?php echo $player["bestTrophies"]; ?></p></div>
                </div>
            </section>

            <section class="section">
                <h2 style="text-align:center; margin-bottom:30px;">Ultime 5 Partite</h2>
                <div class="battle-list">
                    <?php foreach($lastFiveBattles as $battle): 
                        $myCrowns = $battle["team"][0]["crowns"];
                        $opponentCrowns = $battle["opponent"][0]["crowns"];
                        $myWin = $myCrowns > $opponentCrowns;
                        $opponentWin = $opponentCrowns > $myCrowns;
                    ?>
                    <div class="battle-row">
                        <div class="player-side">
                            <h4>Tu <?php if($myWin): ?><img src="crown.png" class="winner-crown"><?php endif; ?></h4>
                            <div class="deck-images">
                                <?php foreach($battle["team"][0]["cards"] as $card): ?>
                                    <img src="<?php echo $card['iconUrls']['medium']; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="battle-vs"><?php echo $myCrowns . " - " . $opponentCrowns; ?></div>
                        <div class="player-side">
                            <h4><?php echo htmlspecialchars($battle["opponent"][0]["name"]); ?> <?php if($opponentWin): ?><img src="crown.png" class="winner-crown"><?php endif; ?></h4>
                            <div class="deck-images">
                                <?php foreach($battle["opponent"][0]["cards"] as $card): ?>
                                    <img src="<?php echo $card['iconUrls']['medium']; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div> <div id="section-collection" style="display: none;">
            <section class="section">
                <h2 style="text-align: center;">La tua Collezione </h2><br><br>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 15px;">
                    <?php foreach($playerCards as $card): ?>
                     <div class="card-item" style="
    position: relative; 
    text-align: center; 
    background: #2c2f38; 
    padding: 10px; 
    border-radius: 10px; 
    border: <?php echo $card['is_evo'] ? '3px solid #ff00ff' : '1px solid #444'; ?>;
    box-shadow: <?php echo $card['is_evo'] ? '0 0 15px rgba(255, 0, 255, 0.4)' : 'none'; ?>;
">
    
    <?php if($card['is_evo']): ?>
        <div style="position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: #ff00ff; color: white; font-size: 11px; padding: 2px 8px; border-radius: 5px; font-weight: bold; z-index: 10; box-shadow: 0 2px 5px black;">
            EVOLUZIONE
        </div>
    <?php endif; ?>

    <img src="<?php echo $card['iconUrls']['medium']; ?>" style="width: 100%; border-radius: 5px;">
    <div style="color: #f5b700; font-weight: bold; margin-top: 5px;">Liv. <?php echo $card['display_level']; ?></div>
</div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

    <?php endif; ?>
</main>

</main>
<script>
function switchView(view) {
    const statsSection = document.getElementById('section-stats');
    const collectionSection = document.getElementById('section-collection');

    if (view === 'collection') {
        statsSection.style.display = 'none';
        collectionSection.style.display = 'block';
    } else {
        statsSection.style.display = 'block';
        collectionSection.style.display = 'none';
    }
}
</script>
</body>

</html>
