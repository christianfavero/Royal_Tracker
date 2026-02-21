<?php
require "config.php";
require "cr-api.php";

$api = new ClashRoyaleAPI($clash_api_key);

// Assicuriamoci che locationId sia trattato correttamente
$locationId = $_GET['location'] ?? 'global';

// Se è numerico (ID paese), lo forziamo a intero per sicurezza
if (is_numeric($locationId)) {
    $locationId = (int)$locationId;
}

$response = $api->getLeaderboard($locationId, 50);

if (empty($response['items'])) {
    echo "<tr><td colspan='3' style='text-align:center;'>
            Nessun dato. URL usato: locations/$locationId/rankings/players
          </td></tr>";
    exit;
}

// ... resto del ciclo foreach ...
// Se i dati ci sono, stampiamo le righe
foreach($rankings as $player) {
    echo "<tr>
            <td class='rank'>#{$player['rank']}</td>
            <td class='player-name'>
                <strong>" . htmlspecialchars($player['name']) . "</strong><br>
                <small>{$player['tag']}</small>
            </td>
            <td class='trophies'>🏆 {$player['trophies']}</td>
          </tr>";
}
// In cr-api.php, dentro il metodo request, aggiungi questo temporaneamente:
// echo "URL Chiamato: " . $url;
?>