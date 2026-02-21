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
$api = new ClashRoyaleAPI($clash_api_key);

$player = $api->getPlayer($gamertag);
$battleLog = $api->getBattleLog($gamertag);

if (!$player || isset($player["reason"])) {
    $error = "Impossibile recuperare i dati del giocatore.";
}

$lastFiveBattles = [];
if (!isset($battleLog["error"])) {
    $lastFiveBattles = array_slice($battleLog, 0, 5);
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
        <a href="index.php">Royal Tracker</a>
    </div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="Cards.php">Carte</a></li>
        <li><a href="challenges.php">Challenges</a></li>
        <li><a href="community.php">Community</a></li>
        <li><a href="videos.php">Video</a></li>
    </ul>
</nav>

<main class="home-sections">

<?php if(isset($error)): ?>

<section class="section">
    <h2>Errore</h2>
    <p><?php echo htmlspecialchars($error); ?></p>
</section>

<?php else: ?>

<section class="section">
    <h2>Benvenuto <?php echo htmlspecialchars($player["name"]); ?></h2>
    <p>GamerTag: <?php echo htmlspecialchars($gamertag); ?></p>

    <div class="cards-container">
        <div class="card">
            <h3>Livello</h3>
            <p><?php echo $player["expLevel"]; ?></p>
        </div>

        <div class="card">
            <h3>Trofei</h3>
            <p><?php echo $player["trophies"]; ?></p>
        </div>

        <div class="card">
            <h3>Vittorie</h3>
            <p><?php echo $player["wins"]; ?></p>
        </div>

        <div class="card">
            <h3>Sconfitte</h3>
            <p><?php echo $player["losses"]; ?></p>
        </div>

        <div class="card">
            <h3>Miglior Record</h3>
            <p><?php echo $player["bestTrophies"]; ?></p>
        </div>
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

        <!-- TU -->
        <div class="player-side">
            <h4>
                Tu
                <?php if($myWin): ?>
                    <img src="crown.png" class="winner-crown" alt="Winner">
                <?php endif; ?>
            </h4>

            <div class="deck-images">
                <?php foreach($battle["team"][0]["cards"] as $card): ?>
                    <img src="<?php echo $card['iconUrls']['medium']; ?>" alt="card">
                <?php endforeach; ?>
            </div>
        </div>

        <!-- VS -->
        <div class="battle-vs">
            <?php echo $myCrowns . " - " . $opponentCrowns; ?>
        </div>

        <!-- AVVERSARIO -->
        <div class="player-side">
            <h4>
                <?php echo htmlspecialchars($battle["opponent"][0]["name"]); ?>
                <?php if($opponentWin): ?>
                    <img src="crown.png" class="winner-crown" alt="Winner">
                <?php endif; ?>
            </h4>

            <div class="deck-images">
                <?php foreach($battle["opponent"][0]["cards"] as $card): ?>
                    <img src="<?php echo $card['iconUrls']['medium']; ?>" alt="card">
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <?php endforeach; ?>

    </div>
</section>

<?php endif; ?>

</main>
</body>
</html>