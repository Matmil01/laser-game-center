<?php
// Til at hente ledige starttider for en given dato.
// Returnerer 30-minutters intervaller med antal mulige spil per starttid.

require 'cors.php';
header('Content-Type: application/json');
setCorsHeaders('GET');

require 'db.php';

$date = $_GET['date'] ?? null;

if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing date']);
    exit;
}

// Hent tilgængeligheds-vindue for denne dato
$wstmt = $pdo->prepare(
    'SELECT id, start_time, end_time FROM availability_windows WHERE window_date = ?'
);
$wstmt->execute([$date]);
$window = $wstmt->fetch();

if (!$window) {
    echo json_encode([]);
    exit;
}

// Beregn vindues start/slut i minutter
[$sh, $sm] = array_map('intval', explode(':', substr($window['start_time'], 0, 5)));
$startMin  = $sh * 60 + $sm;
[$eh, $em] = array_map('intval', explode(':', substr($window['end_time'], 0, 5)));
$endMin    = $eh * 60 + $em;

// Hent alle bookinger i dette vindue
$booked = [];
try {
    $bstmt = $pdo->prepare(
        'SELECT FLOOR(TIME_TO_SEC(start_time)/60) AS start_min, num_games
         FROM bookings WHERE window_id = ?'
    );
    $bstmt->execute([$window['id']]);
    $bookingRows = $bstmt->fetchAll();

    // Build liste over booket tidsrum [start_min, end_min]
    foreach ($bookingRows as $b) {
        $bStart   = (int)$b['start_min'];
        $booked[] = [$bStart, $bStart + (int)$b['num_games'] * 30];
    }
} catch (Exception $e) {
    // Bookings table issue – treat as no bookings so available slots still show
    $booked = [];
}

// Generate ledige starttider (30-min trin) med max antal spil
$slots = [];
for ($t = $startMin; $t + 30 <= $endMin; $t += 30) {
    $maxGames = 0;
    for ($g = 1; $g <= 4; $g++) {
        $segEnd  = $t + $g * 30;
        if ($segEnd > $endMin) break;
        $blocked = false;
        foreach ($booked as $b) {
            if ($t < $b[1] && $segEnd > $b[0]) { $blocked = true; break; }
        }
        if ($blocked) break;
        $maxGames = $g;
    }
    if ($maxGames >= 1) {
        $slots[] = [
            'start_time' => sprintf('%02d:%02d', intdiv($t, 60), $t % 60),
            'max_games'  => $maxGames,
        ];
    }
}

echo json_encode($slots);