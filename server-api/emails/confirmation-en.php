<?php
// Template-variabler forventet fra det kaldende scope:
// $name, $dateFormatted, $startFormatted, $endFormatted, $num_games, $participants
?>
<div style="font-family: Arial, sans-serif; color: #222; background: #f9f9f9; padding: 24px; border-radius: 8px; max-width: 480px; margin: 0 auto;">
  <h2 style="color: #0a3c6e;">Hi <?= htmlspecialchars($name) ?>,</h2>
  <p>Your lasertag session is now booked and confirmed!</p>
  <table style="margin: 16px 0 24px 0; font-size: 15px;">
    <tr><td><strong>Date:</strong></td><td><?= $dateFormatted ?></td></tr>
    <tr><td><strong>Time:</strong></td><td><?= $startFormatted ?> – <?= $endFormatted ?></td></tr>
    <tr><td><strong>Number of games:</strong></td><td><?= $num_games ?></td></tr>
    <tr><td><strong>Participants:</strong></td><td><?= $participants ?></td></tr>
  </table>
  <p>Please arrive 10 minutes before your session.</p>
  <p style="margin-top: 18px;">If you need to cancel or change your booking, please contact us by email or phone.</p>
  <p style="margin-top: 28px;">We look forward to seeing you!</p>
  <p style="margin-top: 32px; color: #0a3c6e; font-weight: bold;">Best regards,<br>Laser Game Center Oksbøl</p>
</div>
