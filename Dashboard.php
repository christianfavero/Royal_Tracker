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

<!-- ================= NAVBAR ================= -->
<nav class="navbar">
    <div class="logo"><a href="index.php">Royal Tracker</a></div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="decks.php">Decks</a></li>
        <li><a href="challenges.php">Challenges</a></li>
        <li><a href="community.php">Community</a></li>
        <li><a href="videos.php">Video</a></li>
    </ul>
    <ul class="nav-links">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<!-- ================= MAIN ================= -->
<main class="home-sections">
    <?php if(isset($error)): ?>
        <section class="section">
            <h2>Errore</h2>
            <p><?php echo $error; ?></p>
        </section>
    <?php else: ?>
        <section class="section">
            <h2>Benvenuto <?php echo htmlspecialchars($player["name"] ?? "N/D"); ?></h2>
            <p>GamerTag: <?php echo htmlspecialchars($gamertag); ?></p>

            <div class="cards-container">
                <div class="card">
                    <h3>Livello</h3>
                    <p><?php echo $player["expLevel"] ?? "N/D"; ?></p>
                </div>
                <div class="card">
                    <h3>Trofei</h3>
                    <p><?php echo $player["trophies"] ?? "N/D"; ?></p>
                </div>
                <div class="card">
                    <h3>Vittorie</h3>
                    <p><?php echo $player["wins"] ?? "N/D"; ?></p>
                </div>
                <div class="card">
                    <h3>Sconfitte</h3>
                    <p><?php echo $player["losses"] ?? "N/D"; ?></p>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

</body>
</html>
