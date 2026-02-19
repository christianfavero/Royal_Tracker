<?php

class ClashRoyaleAPI {

    private $apiKey;
    private $baseUrl = "https://api.clashroyale.com/v1/";

    // Costruttore: riceve la API key
    public function __construct($key) {
        $this->apiKey = $key;
    }

    // Metodo privato generico per fare richieste
    private function request($endpoint) {

        $url = $this->baseUrl . $endpoint;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Authorization: Bearer " . $this->apiKey
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return [
                "error" => true,
                "message" => curl_error($ch)
            ];
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    // =========================
    // METODI PUBBLICI UTILIZZABILI
    // =========================

    // Ottiene dati giocatore
    public function getPlayer($tag) {
        $encodedTag = urlencode($tag);
        return $this->request("players/" . $encodedTag);
    }

    // Ottiene info clan
    public function getClan($tag) {
        $encodedTag = urlencode($tag);
        return $this->request("clans/" . $encodedTag);
    }

    // Ottiene lista carte
    public function getCards() {
        return $this->request("cards");
    }

    // Ottiene tornei pubblici
    public function getTournaments() {
        return $this->request("tournaments");
    }
}
