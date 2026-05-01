<?php
// I don't know man, noget security så browseren ved hvem der må kalde API'et.

function setCorsHeaders(string $methods = 'POST, OPTIONS'): void {
    // Hent den tilladte frontend-oprindelse fra env
    $origin = getenv('CORS_ORIGIN');
    if (!$origin) return; // Ingen oprindelse konfigureret — spring CORS-headers over
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Methods: ' . $methods);
    header('Access-Control-Allow-Headers: Content-Type');
}
