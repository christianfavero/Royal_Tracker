<?php
session_start();
require 'config.php';
require 'cr-api.php';

$api = new ClashRoyaleAPI($clash_api_key);

$locations = [
    'global' => ['name' => 'Global', 'id' => 57000000],
    'US'     => ['name' => 'USA', 'id' => 57000009],
    'GB'     => ['name' => 'UK', 'id' => 57000011],
    'FR'     => ['name' => 'France', 'id' => 57000012],
    'DE'     => ['name' => 'Germany', 'id' => 57000013],
    'IT'     => ['name' => 'Italy', 'id' => 57000021],
    'ES'     => ['name' => 'Spain', 'id' => 57000014],
    'BR'     => ['name' => 'Brazil', 'id' => 57000019],
    'IN'     => ['name' => 'India', 'id' => 57000023],
    'JP'     => ['name' => 'Japan', 'id' => 57000022]
];

$selectedCountry = strtolower($_GET['country'] ?? 'global');
$locationId = $locations[$selectedCountry]['id'] ?? $locations['global']['id'];

try {
    $response = $api->getLeaderboard($locationId);
    $players = $response['items'] ?? [];
    foreach ($players as $idx => $player) {
        $stmt = $conn->prepare("
            INSERT INTO leaderboard (rank, player_tag, player_name, trophies, clan_name, location_name)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                rank = VALUES(rank),
                player_name = VALUES(player_name),
                trophies = VALUES(trophies),
                clan_name = VALUES(clan_name),
                location_name = VALUES(location_name),
                last_update = CURRENT_TIMESTAMP
        ");

        $stmt->bind_param(
            "ississ",
            $rank,
            $player_tag,
            $player_name,
            $trophies,
            $clan_name,
            $location_name
        );

        $rank = $idx + 1;
        $player_tag = $player['tag'] ?? '';
        $player_name = $player['name'] ?? '';
        $trophies = $player['trophies'] ?? 0;
        $clan_name = $player['clan']['name'] ?? '-';
        $location_name = $player['location']['name'] ?? '-';

        $stmt->execute();
        $stmt->close();
    }

    $sql = "SELECT * FROM leaderboard WHERE location_name = ? ORDER BY rank ASC LIMIT 100";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $locations[$selectedCountry]['name']);
    $stmt->execute();
    $result = $stmt->get_result();
    $players = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

} catch(Exception $e) {
    $players = [];
    $error = "Errore API: " . $e->getMessage();
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
    <div class="logo"><a href="index.php">Royal Tracker</a></div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="Leaderboard.php">Leaderboard</a></li>
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