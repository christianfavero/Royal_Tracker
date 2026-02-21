<?php
require "config.php";
require "cr-api.php";

$api = new ClashRoyaleAPI($clash_api_key);

$locationId = $_GET['location'] ?? 'global';

$response = $api->getLeaderboard($locationId, 50);

// DEBUG: Togli il commento alla riga sotto se vuoi vedere cosa risponde l'API
// print_r($response); 

if (empty($response['items'])) {
    echo "<tr><td colspan='3' style='text-align:center;'>
            Nessun dato. URL usato: locations/$locationId/rankings/players
          </td></tr>";
    exit;
}

// DEFINIAMO $rankings usando i dati di $response
$rankings = $response['items'];

foreach($rankings as $player) {
    // Creiamo il link alla tua dashboard usando il tag
    $tagPerLink = urlencode($player['tag']);
    
    echo "<tr>
            <td class='rank'>#{$player['rank']}</td>
            <td class='player-name'>
                <a href='dashboard.php?tag={$tagPerLink}' style='color:white; text-decoration:none;'>
                    <strong>" . htmlspecialchars($player['name']) . "</strong>
                </a><br>
                <small>{$player['tag']}</small>
            </td>
            <td class='trophies'>🏆 {$player['trophies']}</td>
          </tr>";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <title>Classifica Clash Royale</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>

<main class="container">
    <h1>Classifica Top 50 (<?php echo strtoupper($loc); ?>)</h1>
    
    <div class="menu-classifica">
        <a href="leaderboard.php?loc=global">Mondiale</a> | 
        <a href="leaderboard.php?loc=57000122">Italia</a>
    </div>

    <table class="leaderboard-table">
        <thead>
            <tr>
                <th>Pos.</th>
                <th>Giocatore</th>
                <th>Clan</th>
                <th>Trofei</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($rankings)): ?>
                <?php foreach($rankings as $player): ?>
                    <tr>
                        <td>#<?php echo $player['rank']; ?></td>
                        <td>
                            <a href="dashboard.php?tag=<?php echo urlencode($player['tag']); ?>">
                                <?php echo htmlspecialchars($player['name']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($player['clan']['name'] ?? '-'); ?></td>
                        <td>🏆 <?php echo $player['trophies']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">Nessun dato trovato. Controlla la chiave API o l'ID location.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>