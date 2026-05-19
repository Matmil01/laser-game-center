<?php
// Admin-notifikation e-mail (dansk)
// Variabler sendes til template fra book.php: kundens navn, e-mail, telefon, dato, tidspunkt, antal spil, antal spillere og note.
?><!DOCTYPE html>
<html lang="da">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light dark">
<meta name="supported-color-schemes" content="light dark">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Quantico:wght@400;700&display=swap');
  :root { color-scheme: light dark; }
  body { background-color: #000000 !important; color: #ffffff !important; }
</style>
</head>
<body style="margin:0;padding:0;background:#000000;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#000000;">
  <tr>
    <td align="center" style="padding:40px 16px;background:#000000;">
      <table width="520" cellpadding="0" cellspacing="0" border="0" style="max-width:520px;width:100%;">

        <!-- Header -->
        <tr>
          <td style="padding-bottom:24px;background:#000000;">
            <h1 style="margin:0;color:#FF9D00;font-family:'Quantico',Arial,sans-serif;font-size:18px;font-weight:700;text-transform:uppercase;letter-spacing:3px;">Ny booking modtaget</h1>
          </td>
        </tr>

        <!-- Data table -->
        <tr>
          <td style="border:2px solid #FF9D00;box-shadow:0 0 14px 2px #FF9D00;background:#000000;padding:24px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:'Quantico',Arial,sans-serif;font-size:14px;">
              <tr><td style="color:#888888;font-size:11px;text-transform:uppercase;letter-spacing:1px;padding:8px 0 2px 0;background:#000000;" colspan="2">Kunde</td></tr>
              <tr><td style="padding:6px 16px 6px 0;white-space:nowrap;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;">Navn</td><td style="padding:6px 0;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;"><strong><?= htmlspecialchars($name) ?></strong></td></tr>
              <tr><td style="padding:6px 16px 6px 0;white-space:nowrap;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;">E-mail</td><td style="padding:6px 0;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;"><strong><?= htmlspecialchars($email) ?></strong></td></tr>
              <tr><td style="padding:6px 16px 6px 0;white-space:nowrap;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;">Telefon</td><td style="padding:6px 0;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;"><strong><?= htmlspecialchars($phone) ?></strong></td></tr>
              <tr><td style="color:#888888;font-size:11px;text-transform:uppercase;letter-spacing:1px;padding:16px 0 2px 0;background:#000000;" colspan="2">Booking</td></tr>
              <tr><td style="padding:6px 16px 6px 0;white-space:nowrap;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;">Dato</td><td style="padding:6px 0;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;"><strong><?= htmlspecialchars($dateFormatted) ?></strong></td></tr>
              <tr><td style="padding:6px 16px 6px 0;white-space:nowrap;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;">Tid</td><td style="padding:6px 0;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;"><strong><?= htmlspecialchars($startFormatted) ?> &#8211; <?= htmlspecialchars($endFormatted) ?></strong></td></tr>
              <tr><td style="padding:6px 16px 6px 0;white-space:nowrap;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;">Antal spil</td><td style="padding:6px 0;border-bottom:1px solid #1e1e1e;color:#ffffff;background:#000000;"><strong><?= (int)$num_games ?></strong></td></tr>
              <tr><td style="padding:6px 16px 6px 0;white-space:nowrap;color:#ffffff;background:#000000;<?= $note !== '' ? 'border-bottom:1px solid #1e1e1e;' : '' ?>">Antal deltagere</td><td style="padding:6px 0;color:#ffffff;background:#000000;<?= $note !== '' ? 'border-bottom:1px solid #1e1e1e;' : '' ?>"><strong><?= (int)$participants ?></strong></td></tr>
              <?php if ($note !== ''): ?>
              <tr><td style="padding:6px 16px 6px 0;white-space:nowrap;vertical-align:top;color:#ffffff;background:#000000;">Note</td><td style="padding:6px 0;color:#aaaaaa;background:#000000;"><?= htmlspecialchars($note) ?></td></tr>
              <?php endif; ?>
            </table>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
