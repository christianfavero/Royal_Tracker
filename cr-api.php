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


    public function GetAllCards() : array{
        return $this->request("cards");
    }

    public function getLeaderboard(string $location = 'global'): array {
        return $this->request("locations/{$location}/rankings/players");
    }
}
