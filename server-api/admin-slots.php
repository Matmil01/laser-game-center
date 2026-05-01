<?php
// Admin API til at administrere tilgængelighed og bookinger.
// Understøttede handlinger: list, set_window, delete_window, cancel.

$envLoader = __DIR__ . '/load-env.php';
if (file_exists($envLoader)) require_once $envLoader;
require 'cors.php';
header('Content-Type: application/json');
setCorsHeaders('POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST')    { http_response_code(405); echo json_encode(['error' => 'Method not allowed']); exit; }

require 'admin-auth.php';
requireAdminAuth();
require 'db.php';

$body   = $GLOBALS['_BODY'] ?? json_decode(file_get_contents('php://input'), true);
$action = $body['action'] ?? 'list';
$date   = $body['date']   ?? '';
$id     = isset($body['id']) ? (int) $body['id'] : 0;

// Hent alle tilgængeligheds-windows og bookinger.

if ($action === 'list') {
    $wstmt = $pdo->query(
        'SELECT id, window_date, start_time, end_time
         FROM availability_windows
         ORDER BY window_date, start_time'
    );
    $windows = $wstmt->fetchAll();

    try {
        $bstmt = $pdo->query(
            'SELECT b.id, b.window_id, aw.window_date, b.start_time, b.num_games,
                    b.name, b.email, b.phone, b.note, b.participants, b.created_at
             FROM bookings b
             JOIN availability_windows aw ON b.window_id = aw.id
             ORDER BY aw.window_date, b.start_time'
        );
        $bookings = $bstmt->fetchAll();

        foreach ($bookings as &$b) {
            $b['id']           = (int) $b['id'];
            $b['window_id']    = (int) $b['window_id'];
            $b['num_games']    = (int) $b['num_games'];
            $b['participants'] = (int) $b['participants'];
        }
    } catch (Exception $e) {
        // Bookings table may have wrong schema – return windows with empty bookings
        // and surface the DB error so it can be diagnosed.
        echo json_encode([
            'windows'  => $windows,
            'bookings' => [],
            'db_error' => 'Bookings-tabel fejl: ' . $e->getMessage(),
        ]);
        exit;
    }

    echo json_encode(['windows' => $windows, 'bookings' => $bookings]);
    exit;
}

// Opret eller opdater tilgængeligheds-window for en dato

if ($action === 'set_window') {
    $from = $body['from'] ?? '';
    $to   = $body['to']   ?? '';

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        http_response_code(400); echo json_encode(['error' => 'Ugyldigt dato-format']); exit;
    }
    if (!preg_match('/^\d{2}:\d{2}$/', $from) || !preg_match('/^\d{2}:\d{2}$/', $to)) {
        http_response_code(400); echo json_encode(['error' => 'Ugyldigt tids-format']); exit;
    }

    [$fh, $fm] = array_map('intval', explode(':', $from));
    [$th, $tm] = array_map('intval', explode(':', $to));
    $fromMin   = $fh * 60 + $fm;
    $toMin     = $th * 60 + $tm;

    if ($fromMin >= $toMin) {
        http_response_code(400); echo json_encode(['error' => '"Fra" skal være før "Til"']); exit;
    }
    if ($toMin - $fromMin < 30) {
        http_response_code(400); echo json_encode(['error' => 'Tidsrammen skal være mindst 30 minutter']); exit;
    }

    // Check for existing window on this date
    $existing = $pdo->prepare('SELECT id FROM availability_windows WHERE window_date = ?');
    $existing->execute([$date]);
    $win = $existing->fetch();

    if ($win) {
        http_response_code(409);
        echo json_encode(['error' => 'Der er allerede en tidsramme for denne dato. Klik på den i kalenderen for at redigere.']);
        exit;
    }

    $pdo->prepare('INSERT INTO availability_windows (window_date, start_time, end_time) VALUES (?, ?, ?)')
        ->execute([$date, $from . ':00', $to . ':00']);
    $windowId = (int) $pdo->lastInsertId();
    echo json_encode(['success' => true, 'id' => $windowId, 'created' => true]);
    exit;
}

// Opdater tidsramme for et eksisterende window

if ($action === 'update_window') {
    $from = $body['from'] ?? '';
    $to   = $body['to']   ?? '';

    if (!$id) { http_response_code(400); echo json_encode(['error' => 'Mangler vindue-id']); exit; }
    if (!preg_match('/^\d{2}:\d{2}$/', $from) || !preg_match('/^\d{2}:\d{2}$/', $to)) {
        http_response_code(400); echo json_encode(['error' => 'Ugyldigt tids-format']); exit;
    }

    [$fh, $fm] = array_map('intval', explode(':', $from));
    [$th, $tm] = array_map('intval', explode(':', $to));
    $fromMin   = $fh * 60 + $fm;
    $toMin     = $th * 60 + $tm;

    if ($fromMin >= $toMin) {
        http_response_code(400); echo json_encode(['error' => '"Fra" skal være før "Til"']); exit;
    }
    if ($toMin - $fromMin < 30) {
        http_response_code(400); echo json_encode(['error' => 'Tidsrammen skal være mindst 30 minutter']); exit;
    }

    $stmt = $pdo->prepare('SELECT id FROM availability_windows WHERE id = ?');
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        http_response_code(404); echo json_encode(['error' => 'Vindue ikke fundet']); exit;
    }

    $check = $pdo->prepare(
        'SELECT COUNT(*) FROM bookings
         WHERE window_id = ?
           AND (FLOOR(TIME_TO_SEC(start_time)/60) < ?
             OR FLOOR(TIME_TO_SEC(start_time)/60) + num_games * 30 > ?)'
    );
    $check->execute([$id, $fromMin, $toMin]);
    if ((int)$check->fetchColumn() > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'Tidsrammen kan ikke indsnævres – der er bookinger uden for den nye tidsramme']);
        exit;
    }

    $pdo->prepare('UPDATE availability_windows SET start_time = ?, end_time = ? WHERE id = ?')
        ->execute([$from . ':00', $to . ':00', $id]);
    echo json_encode(['success' => true]);
    exit;
}

// Slet et tilgængeligheds-window

if ($action === 'delete_window') {
    if (!$id) { http_response_code(400); echo json_encode(['error' => 'Mangler vindue-id']); exit; }

    $stmt = $pdo->prepare('SELECT id FROM availability_windows WHERE id = ?');
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        http_response_code(404); echo json_encode(['error' => 'Vindue ikke fundet']); exit;
    }

    $countStmt = $pdo->prepare('SELECT COUNT(*) FROM bookings WHERE window_id = ?');
    $countStmt->execute([$id]);
    if ((int)$countStmt->fetchColumn() > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'Kan ikke slette tidsramme med aktive bookinger. Annuller bookingerne først.']);
        exit;
    }

    $pdo->prepare('DELETE FROM availability_windows WHERE id = ?')->execute([$id]);
    echo json_encode(['success' => true]);
    exit;
}

// Annuller en booking

if ($action === 'cancel') {
    if (!$id) { http_response_code(400); echo json_encode(['error' => 'Mangler booking-id']); exit; }

    $stmt = $pdo->prepare('SELECT id FROM bookings WHERE id = ?');
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        http_response_code(404); echo json_encode(['error' => 'Booking ikke fundet']); exit;
    }

    $pdo->prepare('DELETE FROM bookings WHERE id = ?')->execute([$id]);
    echo json_encode(['success' => true]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Ukendt handling']);