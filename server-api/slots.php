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

// Beregn afskæringstidspunkt: nu + 2 timer (kun relevant for dagens dato)
$cutoffMin = ($date === date('Y-m-d'))
    ? (int)date('H') * 60 + (int)date('i') + 120
    : 0;

// Hent alle availability_windows for denne dato
$wstmt = $pdo->prepare(
    'SELECT id, start_time, end_time FROM availability_windows WHERE window_date = ? ORDER BY start_time'
);
$wstmt->execute([$date]);
$windows = $wstmt->fetchAll();

if (!$windows) {
    echo json_encode([]);
    exit;
}

$slots = [];

foreach ($windows as $window) {
    // Beregn vindues start/slut i minutter
    [$sh, $sm] = array_map('intval', explode(':', substr($window['start_time'], 0, 5)));
    $startMin  = $sh * 60 + $sm;
    [$eh, $em] = array_map('intval', explode(':', substr($window['end_time'], 0, 5)));
    $endMin    = $eh * 60 + $em;

    // Hent alle bookinger i dette vindue
    $booked = [];
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

    // Generate ledige starttider (30-min trin) med max antal spil
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
        if ($maxGames >= 1 && $t >= $cutoffMin) {
            $slots[] = [
                'start_time' => sprintf('%02d:%02d', intdiv($t, 60), $t % 60),
                'max_games'  => $maxGames,
            ];
        }
    }
}

echo json_encode($slots);