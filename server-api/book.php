<?php
// Offentligt API-endpoint til at oprette en booking.
// Modtager kundeoplysninger, ønsket dato, starttidspunkt og antal spil.
// Validerer mod tilgængeligheds-vinduet og eksisterende bookinger.

require 'cors.php';
$envLoader = __DIR__ . '/load-env.php';
if (file_exists($envLoader)) require_once $envLoader;
header('Content-Type: application/json');
setCorsHeaders('POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

require 'db.php';

$data         = json_decode(file_get_contents('php://input'), true);

// Honeypot
if (!empty(trim($data['website'] ?? ''))) {
    http_response_code(200);
    echo json_encode(['error' => 'Spam detected']);
    exit;
}

$name         = trim($data['name']       ?? '');
$email        = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone        = trim($data['phone']      ?? '');
$note         = trim($data['note']       ?? '');
$date         = $data['date']            ?? '';
$start_time   = $data['start_time']      ?? '';
$num_games    = (int)($data['num_games'] ?? 0);
$participants = (int)($data['participants'] ?? 4);

if (!$name || !$email || !$phone || !$date || !$start_time || !$num_games) {
    http_response_code(400);
    echo json_encode(['error' => 'Udfyld venligst alle påkrævede felter']);
    exit;
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !preg_match('/^\d{2}:\d{2}$/', $start_time)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ugyldigt dato- eller tidsformat']);
    exit;
}
if ($num_games < 1 || $num_games > 4) {
    http_response_code(400);
    echo json_encode(['error' => 'Antal spil skal være mellem 1 og 4']);
    exit;
}
if (mb_strlen($name) > 255 || mb_strlen($phone) > 50 || mb_strlen($note) > 1000) {
    http_response_code(400);
    echo json_encode(['error' => 'Feltværdi er for lang']);
    exit;
}
if ($participants < 1 || $participants > 99) {
    http_response_code(400);
    echo json_encode(['error' => 'Antal deltagere skal være mellem 1 og 99']);
    exit;
}

[$sh, $sm] = array_map('intval', explode(':', $start_time));
$startMin  = $sh * 60 + $sm;
$endMin    = $startMin + $num_games * 30;

// Lås vinduet og tjek for overlap
$pdo->beginTransaction();

$wstmt = $pdo->prepare(
    'SELECT id, start_time, end_time FROM availability_windows WHERE window_date = ? FOR UPDATE'
);
$wstmt->execute([$date]);
$window = $wstmt->fetch();

if (!$window) {
    $pdo->rollBack();
    http_response_code(409);
    echo json_encode(['error' => 'Ingen tilgængelighed på denne dato.']);
    exit;
}

[$wsh, $wsm] = array_map('intval', explode(':', substr($window['start_time'], 0, 5)));
$winStart    = $wsh * 60 + $wsm;
[$weh, $wem] = array_map('intval', explode(':', substr($window['end_time'], 0, 5)));
$winEnd      = $weh * 60 + $wem;

if ($startMin < $winStart || $endMin > $winEnd) {
    $pdo->rollBack();
    http_response_code(409);
    echo json_encode(['error' => 'Tidspunktet er uden for den tilgængelige tidsramme.']);
    exit;
}

// Tjek for overlap med eksisterende bookinger
$overlapStmt = $pdo->prepare(
    'SELECT COUNT(*) FROM bookings
     WHERE window_id = ?
       AND FLOOR(TIME_TO_SEC(start_time)/60) < ?
       AND FLOOR(TIME_TO_SEC(start_time)/60) + num_games * 30 > ?'
);
$overlapStmt->execute([$window['id'], $endMin, $startMin]);
if ((int)$overlapStmt->fetchColumn() > 0) {
    $pdo->rollBack();
    http_response_code(409);
    echo json_encode(['error' => 'Dette tidspunkt er ikke længere ledigt. Vælg venligst et andet.']);
    exit;
}

// Gem booking
$pdo->prepare(
    'INSERT INTO bookings (window_id, start_time, num_games, name, email, phone, note, participants)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
)->execute([$window['id'], $start_time . ':00', $num_games, $name, $email, $phone, $note, $participants]);

$pdo->commit();

$dateFormatted  = date('d/m/Y', strtotime($date));
$startFormatted = $start_time;
$endFormatted   = sprintf('%02d:%02d', intdiv($endMin, 60), $endMin % 60);

// Send bekræftelsesemail
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host       = getenv('SMTP_HOST');
$mail->Port       = (int)getenv('SMTP_PORT');
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->SMTPAuth   = true;
$mail->Username   = getenv('SMTP_USER');
$mail->Password   = getenv('SMTP_PASS');
$mail->CharSet    = 'UTF-8';

$mail->setFrom(getenv('SMTP_USER'), getenv('SMTP_FROM_NAME'));
$mail->addAddress($email, $name);
$mail->Subject = 'Bekræftelse på din Lasertag-booking';
$mail->Body    = "Hej $name,\n\n"
    . "Din tid til lasertag er nu booket og bekræftet!\n\n"
    . "Dato: $dateFormatted\n"
    . "Tid: $startFormatted – $endFormatted\n"
    . "Antal spil: $num_games\n"
    . "Antal deltagere: $participants\n\n"
    . "Mød gerne op 10 minutter før din spilletid.\n\n"
    . "Hvis du har brug for at aflyse eller ændre din booking, kan du kontakte os på e-mail eller telefon.\n\n"
    . "Vi glæder os til at se dig!\n\n"
    . "Venlig hilsen\n"
    . "Laser Game Center Oksbøl";

try {
    $mail->send();
} catch (Exception $e) {
    error_log('PHPMailer: ' . $mail->ErrorInfo);
}

echo json_encode([
    'success'   => true,
    'date'      => $dateFormatted,
    'time'      => $startFormatted,
    'end_time'  => $endFormatted,
    'num_games' => $num_games,
]);