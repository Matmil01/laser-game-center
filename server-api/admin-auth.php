<?php
// Alle API-endpoints der kræver admin-adgang.

require_once __DIR__ . '/load-env.php';
// Hent password fra .env
define('ADMIN_PASSWORD', getenv('ADMIN_PASSWORD') ?: '');

// rate-limit til at forhindre brute-force på admin
// Maks MAX_ATTEMPTS forsøg per IP inden for WINDOW_SECONDS sekunder.
function checkLoginRateLimit(): void {
    define('MAX_ATTEMPTS',  10);
    define('WINDOW_SECONDS', 60);

    $ip      = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $key     = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $ip);
    $file    = sys_get_temp_dir() . '/lgc_rl_' . $key . '.json';

    $now     = time();
    $data    = [];
    if (file_exists($file)) {
        $raw  = file_get_contents($file);
        $data = json_decode($raw, true) ?: [];
    }

    // Fjern forsøg der er ældre end window
    $data = array_values(array_filter($data, fn($t) => ($now - $t) < WINDOW_SECONDS));

    if (count($data) >= MAX_ATTEMPTS) {
        http_response_code(429);
        echo json_encode(['error' => 'For mange forsøg. Vent et øjeblik og prøv igen.']);
        exit;
    }

    $data[] = $now;
    file_put_contents($file, json_encode($data), LOCK_EX);
}

// Skriv til audit_log-tabel. Kræver at $pdo er tilgængelig i det kaldende scope.
function auditLog(PDO $pdo, string $action, string $detail = ''): void {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    try {
        $pdo->prepare('INSERT INTO audit_log (action, detail, ip) VALUES (?, ?, ?)')
            ->execute([$action, $detail, $ip]);
    } catch (Exception $e) {
        error_log('auditLog failed: ' . $e->getMessage());
    }
}

// Tjek at client har sendt den rigtige admin-password.
// Hvis ikke, giv fejl og stop script.
function requireAdminAuth(): void {
    if (ADMIN_PASSWORD === '') {
        http_response_code(500);
        echo json_encode(['error' => 'Server configuration error: ADMIN_PASSWORD not set']);
        exit;
    }
    $data     = json_decode(file_get_contents('php://input'), true);
    $provided = $data['pw'] ?? '';
    $GLOBALS['_BODY'] = $data;
    if (!hash_equals(ADMIN_PASSWORD, $provided)) {
        checkLoginRateLimit();
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}
