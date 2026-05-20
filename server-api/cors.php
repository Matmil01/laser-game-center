<?php
// CORS (Cross-Origin Resource Sharing) er en sikkerhedsmekanisme i browseren.
// Når en hjemmeside forsøger at kalde et API på et andet domæne, tjekker browseren
// om serveren aktivt tillader det via disse headers — ellers blokerer browseren kaldet.
// Her sætter vi headeren "Access-Control-Allow-Origin" til kun at tillade vores eget frontend-domæne.

function setCorsHeaders(string $methods = 'POST, OPTIONS'): void {
    // Hent den tilladte frontend-oprindelse fra env
    $origin = getenv('CORS_ORIGIN');
    if (!$origin) return; // Ingen oprindelse konfigureret — spring CORS-headers over

    if (!preg_match('#^https?://[a-zA-Z0-9\-\.]+(:\d+)?$#', $origin)) {
        return;
    }

    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Methods: ' . $methods);
    header('Access-Control-Allow-Headers: Content-Type');
    header('Vary: Origin');
}
