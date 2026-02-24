<?php
require "config.php";
require "cr-api.php";

$api = new ClashRoyaleAPI($clash_api_key);
$action = $_GET['action'] ?? 'rankings';

if ($action === 'get_locations') {
    echo json_encode($api->getLocations());
    exit;
}

if ($action === 'rankings') {
    $loc = $_GET['location'] ?? 'global';
    echo json_encode($api->getLeaderboard($loc, 50));
    exit;
}