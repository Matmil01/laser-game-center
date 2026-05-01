<?php
// Forbindelse til SQL-databasen.

// Sæt tidszonen til dansk tid
date_default_timezone_set('Europe/Copenhagen');

// Indlæs fra .env.local hvis filen findes
$envLoader = __DIR__ . '/load-env.php';
if (file_exists($envLoader)) require_once $envLoader;

// Hent databaseoplysninger fra env
$host     = getenv('DB_HOST');
$dbname   = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}