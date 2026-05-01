<?php
// Indlæser .env variabler fra .env.local hvis filen findes.
if (file_exists(__DIR__ . '/.env.local')) {
    // Læs alle linjer fra filen, ignorer tomme linjer
    $lines = file(__DIR__ . '/.env.local', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Spring kommentarer og linjer uden '=' over
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!getenv($name)) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}
