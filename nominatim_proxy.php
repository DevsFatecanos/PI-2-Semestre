<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$q = urlencode($_GET['q']);
$url =`https://nominatim.openstreetmap.org/search?format=json&limit=15&addressdetails=1&bounded=1&viewbox=-47.5,-23.2,-46.2,-24.1&q={$q}`;

$response = file_get_contents($url);
echo $response;