<?php

class ClashRoyaleAPI {

    private string $apiKey;
    private string $baseUrl = "https://api.clashroyale.com/v1/";

    public function __construct(string $key) {
        $this->apiKey = $key;
    }

    public function getBattleLog(string $gamertag): array {
    // Rimuove eventuale # e riaggiunge per sicurezza con l'encode
    $gamertag = ltrim($gamertag, '#');
    $encodedTag = urlencode("#" . $gamertag);

    return $this->request("players/" . $encodedTag . "/battlelog");
}

    private function request(string $endpoint): array {

        $url = $this->baseUrl . $endpoint;

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Authorization: Bearer " . $this->apiKey
            ]
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            return [
                "error" => true,
                "message" => curl_error($ch)
            ];
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (!is_array($data)) {
            return [
                "error" => true,
                "message" => "Risposta non valida dall'API"
            ];
        }

        // Se l'API restituisce errore
        if (isset($data['reason'])) {
            return [
                "error" => true,
                "message" => $data['reason']
            ];
        }

        return $data;
    }

    public function getPlayer(string $gamertag): array {

        // Rimuove eventuale #
        $gamertag = ltrim($gamertag, '#');

        // URL encode obbligatorio
        $encodedTag = urlencode("#" . $gamertag);

        return $this->request("players/" . $encodedTag);
    }
/**
 * Estrae la lista delle carte possedute dal giocatore con i relativi livelli.
 */
public function getPlayerCards(string $gamertag): array {
    $profile = $this->getPlayer($gamertag);
    if (isset($profile['error']) || !isset($profile['cards'])) {
        return $profile;
    }

    $cards = $profile['cards'];

    // 1. Calcoliamo i livelli e le evoluzioni per TUTTE le carte prima di fare altro
    foreach ($cards as &$card) {
        $offset = 0;
        // Usiamo un approccio sicuro per la rarità
        $rarity = isset($card['rarity']) ? strtolower($card['rarity']) : 'common';
        
        switch ($rarity) {
            case 'rare': $offset = 2; break;
            case 'epic': $offset = 5; break;
            case 'legendary': $offset = 8; break;
            case 'champion': $offset = 10; break;
            default: $offset = 0;
        }
        
        // Assegniamo SEMPRE il display_level per evitare il Warning
        $card['display_level'] = ($card['level'] ?? 1) + $offset;

        // Controllo Evoluzione: usiamo il campo evolutionLevel che l'API fornisce per ogni carta
        $card['is_evo'] = isset($card['evolutionLevel']) && $card['evolutionLevel'] > 0;
        
        // Se è evoluta, proviamo a forzare l'immagine viola
        if ($card['is_evo'] && isset($card['iconUrls']['evolutionMedium'])) {
            $card['iconUrls']['medium'] = $card['iconUrls']['evolutionMedium'];
        }
    }
    unset($card); // Scolleghiamo il riferimento del ciclo

    // 2. Ora che TUTTE hanno il display_level, ordiniamo
    usort($cards, function($a, $b) {
        // Se il livello è uguale, ordina per nome
        if ($b['display_level'] === $a['display_level']) {
            return strcmp($a['name'] ?? '', $b['name'] ?? '');
        }
        return $b['display_level'] <=> $a['display_level'];
    });

    return $cards;
}

    public function GetAllCards() : array{
        return $this->request("cards");
    }

public function getLeaderboard($locationId, $limit = 50) {
        return $this->request("https://api.clashroyale.com/v1/locations/$locationId/rankings/players?limit=$limit");
    }

public function getLocations() {
        return $this->request("https://api.clashroyale.com/v1/locations");
    }
}
