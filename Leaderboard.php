<?php
require "config.php";
require "cr-api.php";

$api = new ClashRoyaleAPI($clash_api_key);


$locations = [
    'global' => 57000000,
    'IT' => 57000021,
    'US' => 57000009
];

$selected = $_GET['location'] ?? 'global';
$locationId = $locations[$selected] ?? 57000000;

$response = $api->getLeaderboard($locationId);

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
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Leaderboard Clash Royale</title>
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

<div class="leaderboard-container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Top Players (<?php echo htmlspecialchars($locations[$selectedCountry]['name']); ?>)</h2>
        <form method="GET">
            <select name="country" onchange="this.form.submit()">
                <?php foreach($locations as $code => $loc): ?>
                    <option value="<?php echo $code; ?>" <?php echo ($selectedCountry === strtolower($code)) ? 'selected' : ''; ?>>
                        <?php echo $loc['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Trofei</th>
                    <th>Clan</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($players as $idx => $player): ?>
                    <?php
                        $class = '';
                        if ($idx === 0) $class = 'top1';
                        elseif ($idx === 1) $class = 'top2';
                        elseif ($idx === 2) $class = 'top3';
                    ?>
                    <tr class="<?php echo $class; ?>">
                        <td><?php echo $idx + 1; ?></td>
                        <td><?php echo htmlspecialchars($player['name'] ?? ''); ?></td>
                        <td><?php echo $player['trophies'] ?? '-'; ?></td>
                        <td><?php echo htmlspecialchars($player['clan_name'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($player['location_name'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>