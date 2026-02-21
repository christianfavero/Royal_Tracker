<?php
session_start();
require "config.php";
require "cr-api.php";

$api = new ClashRoyaleAPI($clash_api_key);
$response = $api -> GetAllCards();
$allCards = $response['items'] ?? [];


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
            <?php foreach($allCards as $card): ?>
                <div class="card-item">
                    <?php if(isset($card['elixirCost'])): ?>
                        <div class="elixir-badge"><?php echo $card['elixirCost']; ?></div>
                    <?php endif; ?>

                    <img src="<?php echo $card['iconUrls']['medium']; ?>" alt="<?php echo $card['name']; ?>">
                    
                    <div class="card-info">
                        <h3><?php echo htmlspecialchars($card['name']); ?></h3>
                        <p>Livello Max: <?php echo $card['maxLevel']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
</body>
</html>