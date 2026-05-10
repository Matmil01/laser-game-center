<?php
// Template-variabler forventet fra det kaldende scope:
// $name, $dateFormatted, $startFormatted, $endFormatted, $num_games, $participants
?>
<div style="font-family: Arial, sans-serif; color: #222; background: #f9f9f9; padding: 24px; border-radius: 8px; max-width: 480px; margin: 0 auto;">
  <h2 style="color: #0a3c6e;">Hallo <?= htmlspecialchars($name) ?>,</h2>
  <p>Ihre Lasertag-Sitzung ist jetzt gebucht und bestätigt!</p>
  <table style="margin: 16px 0 24px 0; font-size: 15px;">
    <tr><td><strong>Datum:</strong></td><td><?= htmlspecialchars($dateFormatted) ?></td></tr>
    <tr><td><strong>Uhrzeit:</strong></td><td><?= htmlspecialchars($startFormatted) ?> – <?= htmlspecialchars($endFormatted) ?></td></tr>
    <tr><td><strong>Anzahl der Spiele:</strong></td><td><?= (int)$num_games ?></td></tr>
    <tr><td><strong>Teilnehmer:</strong></td><td><?= (int)$participants ?></td></tr>
  </table>
  <p>Bitte erscheinen Sie 10 Minuten vor Ihrer Spielzeit.</p>
  <p style="margin-top: 18px;">Wenn Sie Ihre Buchung stornieren oder ändern möchten, kontaktieren Sie uns bitte per E-Mail oder Telefon.</p>
  <p style="margin-top: 28px;">Wir freuen uns auf Ihren Besuch!</p>
  <p style="margin-top: 32px; color: #0a3c6e; font-weight: bold;">Mit freundlichen Grüßen,<br>Laser Game Center Oksbøl</p>
</div>
