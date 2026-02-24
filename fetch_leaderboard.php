<?php
require "config.php";
require "cr-api.php"; // La tua classe ClashRoyaleAPI

header('Content-Type: application/json');

$api = new ClashRoyaleAPI($clash_api_key);
$action = $_GET['action'] ?? 'rankings';

if ($action === 'get_locations') {
    $data = $api->getLocations();
    echo json_encode($data);
    exit;
}

if ($action === 'rankings') {
    $loc = $_GET['location'] ?? 'global';
    $data = $api->getLeaderboard($loc, 50);
    echo json_encode($data);
    exit;
}