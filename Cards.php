<?php
session_start();
require "config.php";
require "cr-api.php";


$api = new ClashRoyaleAPI($clash_api_key);
$response = $api -> GetAllCards();
$allCards = $response['items'] ?? [];

function getRarityColor($rarity) {
    switch (strtolower($rarity)) {
        case 'common': return '#5db6f3';    // Blu
        case 'rare': return '#ff9c44';      // Arancione
        case 'epic': return '#c262ff';      // Viola
        case 'legendary': return '#00d0b3'; // Verde acqua/Cangiante
        case 'champion': return '#ffbb00';  // Oro
        default: return '#f5b700';
    }
}

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
        <div class="logo"><a href="index.php">Royal Tracker</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
        </ul>
    </nav>
    <main class="home-sections">
    <section class="section">
        <h2 style="text-align: center; margin-bottom: 30px;">Tutte le Carte</h2>

        <div class="all-cards-grid">
    <?php foreach($allCards as $card): 
    // 1. Scegliamo l'immagine: se c'è l'evoluzione, prendi quella
    $imageUrl = $card['iconUrls']['evolutionMedium'] ?? $card['iconUrls']['medium'];
    
    // 2. Prendiamo il colore in base alla rarità
    $rarity = $card['rarity'] ?? '';
    $borderColor = getRarityColor($rarity);
    
    // 3. Controlliamo se è un'evoluzione
    $isEvolution = isset($card['iconUrls']['evolutionMedium']);
    
    // 4. Controlliamo se è un campione (hero)
    $isChampion = strtolower($rarity) === 'champion';
?>

        <div class="card-item" style="border-color: <?php echo $borderColor; ?>;">
            
            <div class="elixir-badge"><?php echo $card['elixirCost'] ?? '0'; ?></div>
            
            <?php if($isEvolution): ?>
                <span class="evo-label">EVO</span>
            <?php endif; ?>

            <img src="<?php echo $imageUrl; ?>" alt="<?php echo $card['name']; ?>">
            
            <div class="card-info">
                <h3><?php echo htmlspecialchars($card['name']); ?></h3>
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