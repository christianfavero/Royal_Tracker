<?php
session_start();
require "config.php";
require "cr-api.php";

// Inizializzazione API
$api = new ClashRoyaleAPI($clash_api_key);
$response = $api->GetAllCards();
$allCards = $response['items'] ?? [];

function getRarityColor($rarity) {
    switch (strtolower($rarity)) {
        case 'common': return '#5db6f3';    
        case 'rare': return '#ff9c44';      
        case 'epic': return '#c262ff';      
        case 'legendary': return '#00d0b3'; 
        case 'champion': return '#ffbb00';  
        default: return '#f5b700';
    }
}

$heroImages = [
    'Knight'        => 'heroes/knight.webp',
    'Giant'         => 'heroes/giant.webp',
    'Mini P.E.K.K.A'=> 'heroes/minipekka.webp',
    'Musketeer'     => 'heroes/musketeer.webp',
    'Ice Golem'     => 'heroes/icegolem.webp',
    'Wizard'        => 'heroes/wizard.webp'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Clash Royale</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">

<nav class="navbar">
        <div class="logo">
            <a href="index.php">Royal Tracker</a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="Cards.php">Carte</a></li>
            <li><a href = "Leaderboard.php">Leaderboard</a></li>
            <li><a href="challenges.php" class="requires-login">Challenges</a></li>
            <li><a href="Social.php" class="requires-login">Community</a></li>
            <li><a href="videos.php" class="requires-login">Video</a></li>
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
    <br><br><br><br>
<section class="section">
    <h2 style="text-align: center; margin-bottom: 30px;">Tutte le Carte</h2>
    <div class="all-cards-grid">

    <?php foreach($allCards as $card): 
        $cardName = $card['name'] ?? '';

    // Va per eroe, poi evoluzione e poi carta normale
    if (isset($heroImages[$cardName])) {
        $imageUrl = $heroImages[$cardName];
        $isHero = true;
    } elseif (isset($card['iconUrls']['evolutionMedium'])) {
        $imageUrl = $card['iconUrls']['evolutionMedium'];
        $isHero = false;
    } else {
        $imageUrl = $card['iconUrls']['medium'] ?? '';
        $isHero = false;
    }
        $borderColor = getRarityColor($card['rarity'] ?? '');
        $isEvolution = isset($card['iconUrls']['evolutionMedium']);
        $isHero = isset($heroImages[$cardName]);
    ?>
        <div class="card-item" style="border-color: <?php echo $borderColor; ?>;">
            <div class="elixir-badge"><?php echo $card['elixirCost'] ?? '0'; ?></div>
            
            <?php if($isEvolution): ?>
                <span class="evo-label">EVO</span>
            <?php endif; ?>

            <?php if($isHero): ?>
                <span class="hero-label">HERO</span>
            <?php endif; ?>
            
            <?php if ($isHero) echo '<br>'; ?>
            <img src="<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($cardName); ?>">
 
            <div class="card-info">
                <h3><?php echo htmlspecialchars($cardName); ?></h3>
                <p style="color: <?php echo $borderColor; ?>; font-weight: bold; font-size: 10px;">
                    <?php echo strtoupper($card['rarity'] ?? ''); ?>
                </p>
            </div>
        </div>

    <?php endforeach; ?>

    </div>
</section>
</main>

</body>
</html>