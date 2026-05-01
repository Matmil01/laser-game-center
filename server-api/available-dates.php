<?php
// Offentligt API-endpoint til at hente datoer med tilgængelighed.
// Bruges af booking-kalenderen til at fremhæve tilgængelige datoer.

require 'cors.php';
header('Content-Type: application/json');
setCorsHeaders('GET');

require 'db.php';

$today = date('Y-m-d');

// Hent alle fremtidige datoer med et tilgængeligheds-vindue
$stmt = $pdo->prepare(
    'SELECT window_date FROM availability_windows WHERE window_date >= ? ORDER BY window_date'
);
$stmt->execute([$today]);
$rows = $stmt->fetchAll();

echo json_encode(array_column($rows, 'window_date'));
