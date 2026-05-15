<?php
// Template-variabler forventet fra det kaldende scope:
// $name, $dateFormatted, $startFormatted, $endFormatted, $num_games, $participants, $contactPhone
?><!DOCTYPE html>
<html lang="en">
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
          <td style="padding-bottom:28px;background:#000000;">
            <h1 style="margin:0;color:#ffffff;font-family:'Quantico',Arial,sans-serif;font-size:22px;font-weight:700;text-transform:uppercase;letter-spacing:3px;">
              Hi <?= htmlspecialchars($name) ?>!
            </h1>
          </td>
        </tr>

        <!-- Confirmation box – red neon border -->
        <tr>
          <td style="border:2px solid #FF0000;box-shadow:0 0 18px 3px #FF0000;background:#000000;padding:28px 24px;" align="center">

            <!-- Green checkmark circle -->
            <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto 18px auto;">
              <tr>
                <td width="64" height="64" align="center" valign="middle"
                    style="width:64px;height:64px;border:2px solid #00FF00;box-shadow:0 0 18px 3px #00FF00;border-radius:50%;">
                  <span style="color:#00FF00;font-size:32px;font-family:Arial,sans-serif;font-weight:700;line-height:60px;">&#10003;</span>
                </td>
              </tr>
            </table>

            <!-- Confirmed text -->
            <p style="margin:0 0 24px 0;color:#ffffff;font-family:'Quantico',Arial,sans-serif;font-size:17px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">
              Your booking is confirmed!
            </p>

            <!-- Details grid -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:'Quantico',Arial,sans-serif;font-size:14px;">
              <tr>
                <td width="50%" align="left" style="padding:10px 10px 12px 10px;border-bottom:1px solid #2a2a2a;color:#ffffff;background:#000000;">
                  <span style="color:#888888;font-size:11px;text-transform:uppercase;letter-spacing:1px;">Date:</span><br>
                  <strong style="font-size:15px;"><?= htmlspecialchars($dateFormatted) ?></strong>
                </td>
                <td width="50%" align="left" style="padding:10px 10px 12px 10px;border-bottom:1px solid #2a2a2a;color:#ffffff;background:#000000;">
                  <span style="color:#888888;font-size:11px;text-transform:uppercase;letter-spacing:1px;">Games:</span><br>
                  <strong style="font-size:15px;"><?= (int)$num_games ?></strong>
                </td>
              </tr>
              <tr>
                <td align="left" style="padding:12px 10px 10px 10px;color:#ffffff;background:#000000;">
                  <span style="color:#888888;font-size:11px;text-transform:uppercase;letter-spacing:1px;">Time:</span><br>
                  <strong style="font-size:15px;"><?= htmlspecialchars($startFormatted) ?> &#8211; <?= htmlspecialchars($endFormatted) ?></strong>
                </td>
                <td align="left" style="padding:12px 10px 10px 10px;color:#ffffff;background:#000000;">
                  <span style="color:#888888;font-size:11px;text-transform:uppercase;letter-spacing:1px;">Players:</span><br>
                  <strong style="font-size:15px;"><?= (int)$participants ?> people</strong>
                </td>
              </tr>
            </table>

          </td>
        </tr>

        <!-- Spacer -->
        <tr><td style="height:20px;font-size:20px;line-height:20px;background:#000000;">&nbsp;</td></tr>

        <!-- Info box – cyan neon border -->
        <tr>
          <td style="border:2px solid #00C3FF;box-shadow:0 0 18px 3px #00C3FF;background:#000000;padding:20px 24px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <!-- Icon -->
                <td width="52" valign="top" align="center" style="padding-right:16px;padding-top:2px;">
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td width="40" height="40" align="center" valign="middle"
                          style="width:40px;height:40px;border:2px solid #00C3FF;box-shadow:0 0 10px 2px #00C3FF;border-radius:50%;">
                        <span style="color:#00C3FF;font-size:20px;font-weight:700;font-family:Arial,sans-serif;line-height:36px;">!</span>
                      </td>
                    </tr>
                  </table>
                </td>
                <!-- Text -->
                <td valign="top" style="color:#ffffff;font-family:'Quantico',Arial,sans-serif;font-size:14px;line-height:1.65;">
                  <p style="margin:0 0 8px 0;">Please arrive 10 minutes before your session.</p>
                  <p style="margin:0;color:#aaaaaa;">If you need to cancel or change your booking, please contact us by email or phone.</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Spacer -->
        <tr><td style="height:36px;font-size:36px;line-height:36px;background:#000000;">&nbsp;</td></tr>

        <!-- Closing line -->
        <tr>
          <td align="center" style="padding-bottom:36px;background:#000000;">
            <p style="margin:0;color:#ffffff;font-family:'Quantico',Arial,sans-serif;font-size:17px;font-weight:700;text-transform:uppercase;letter-spacing:3px;">
              We look forward to seeing you!
            </p>
          </td>
        </tr>

        <!-- Logo GIF -->
        <tr>
          <td align="center" style="padding-bottom:28px;background:#000000;">
            <img src="https://laser.matmil.dk/icons/logoGif.gif"
                 alt="Lasergame Center"
                 width="200"
                 height="auto"
                 border="0"
                 style="display:block;width:200px;height:auto;margin:0 auto;background-color:#000000;" />
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td align="center" style="border-top:1px solid #1e1e1e;padding-top:18px;color:#555555;background:#000000;font-size:12px;font-family:Arial,sans-serif;line-height:1.6;">
            &#169; <?= date('Y') ?> Lasergame Center<?php if ($contactPhone): ?> &nbsp;|&nbsp; <?= htmlspecialchars($contactPhone) ?><?php endif; ?> &nbsp;|&nbsp; Mønstervej 60, 6854 Henne
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
