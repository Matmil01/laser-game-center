<?php
// Template-variabler forventet fra det kaldende scope:
// $name, $dateFormatted, $startFormatted, $endFormatted, $num_games, $participants
?>
<div style="font-family: Arial, sans-serif; color: #222; background: #f9f9f9; padding: 24px; border-radius: 8px; max-width: 480px; margin: 0 auto;">
	<h2 style="color: #0a3c6e;">Hej <?= htmlspecialchars($name) ?>,</h2>
	<p>Din tid til lasertag er nu booket og bekræftet!</p>
	<table style="margin: 16px 0 24px 0; font-size: 15px;">
		<tr><td><strong>Dato:</strong></td><td><?= htmlspecialchars($dateFormatted) ?></td></tr>
		<tr><td><strong>Tid:</strong></td><td><?= htmlspecialchars($startFormatted) ?> – <?= htmlspecialchars($endFormatted) ?></td></tr>
		<tr><td><strong>Antal spil:</strong></td><td><?= (int)$num_games ?></td></tr>
		<tr><td><strong>Antal deltagere:</strong></td><td><?= (int)$participants ?></td></tr>
	</table>
	<p>Mød gerne op 10 minutter før din spilletid.</p>
	<p style="margin-top: 18px;">Hvis du har brug for at aflyse eller ændre din booking, kan du kontakte os på e-mail eller telefon.</p>
	<p style="margin-top: 28px;">Vi glæder os til at se dig!</p>
	<p style="margin-top: 32px; color: #0a3c6e; font-weight: bold;">Venlig hilsen<br>Laser Game Center Oksbøl</p>
</div>
