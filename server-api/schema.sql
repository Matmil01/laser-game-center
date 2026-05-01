-- Tilgængeligheds-vinduer: admin sætter en åbningstidsramme (fx 10:00–18:00) for en given dato.
CREATE TABLE IF NOT EXISTS availability_windows (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    window_date DATE NOT NULL,
    start_time  TIME NOT NULL,
    end_time    TIME NOT NULL,
    UNIQUE KEY unique_window_date (window_date)
);

-- Bookinger: en kunde booker et starttidspunkt og et antal spil inden for en tidsramme.
-- Hvert spil varer 30 minutter; maks. 4 spil per booking.
CREATE TABLE IF NOT EXISTS bookings (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    window_id    INT          NOT NULL,
    start_time   TIME         NOT NULL,
    num_games    INT          NOT NULL DEFAULT 1,
    name         VARCHAR(255) NOT NULL,
    email        VARCHAR(255) NOT NULL,
    phone        VARCHAR(50)  NOT NULL,
    note         TEXT,
    participants INT          NOT NULL DEFAULT 4,
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (window_id) REFERENCES availability_windows(id),
    INDEX idx_window_time (window_id, start_time)
);

-- Indstillinger: key/value-par til kontaktoplysninger og aktuelt-indhold på forsiden.
CREATE TABLE IF NOT EXISTS settings (
    `key`   VARCHAR(100) PRIMARY KEY,
    `value` TEXT NOT NULL DEFAULT ''
);

-- Indsæt standardværdier ved første opsætning (springer over hvis de allerede findes)
INSERT IGNORE INTO settings (`key`, `value`) VALUES
    ('cvr',           ''),
    ('address',       ''),
    ('email',         ''),
    ('phone',         ''),
    ('aktuelt_title',   'AKTUELT'),
    ('aktuelt_text',    ''),
    ('aktuelt_icon',    'icon1'),
    ('aktuelt_color',   '#FF9D00'),
    ('aktuelt_visible', '1');
