<?php
// Alle API-endpoints der kræver admin-adgang.

require_once __DIR__ . '/load-env.php';
// Hent password fra .env
define('ADMIN_PASSWORD', getenv('ADMIN_PASSWORD') ?: '');

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
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}
