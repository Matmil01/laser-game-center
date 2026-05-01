<?php
// API til at læse og opdatere indstillinger (kontaktinfo, aktuelt-sektion m.m.).

$envLoader = __DIR__ . '/load-env.php';
if (file_exists($envLoader)) require_once $envLoader;
require 'cors.php';
header('Content-Type: application/json');
setCorsHeaders('GET, POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

require 'db.php';

// Returner alle som { key: value }

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query('SELECT `key`, `value` FROM settings');
    $out  = [];
    // Byg et array af key/value-par
    foreach ($stmt->fetchAll() as $row) {
        $out[$row['key']] = $row['value'];
    }
    echo json_encode($out);
    exit;
}

// Gem indstillinger

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'admin-auth.php';
    requireAdminAuth();

    $body    = $GLOBALS['_BODY'] ?? json_decode(file_get_contents('php://input'), true);

    $allowed = [
        'cvr'          => 20,
        'address'      => 200,
        'email'        => 200,
        'phone'        => 30,
        'aktuelt_title'   => 100,
        'aktuelt_text'    => 500,
        'aktuelt_icon'    => 10,
        'aktuelt_color'   => 20,
        'aktuelt_visible' => 1,
    ];

    $stmt = $pdo->prepare(
        'INSERT INTO settings (`key`, `value`) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)'
    );

    foreach ($allowed as $key => $maxLen) {
        if (!array_key_exists($key, $body)) continue;
        // Trim og afkort værdien til maks-længden
        $value = mb_substr(trim((string) $body[$key]), 0, $maxLen);
        // Valider farveformat (#RRGGBB) for aktuelt_color
        if ($key === 'aktuelt_color' && !preg_match('/^#[0-9A-Fa-f]{6}$/', $value)) continue;
        $stmt->execute([$key, $value]);
    }

    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);