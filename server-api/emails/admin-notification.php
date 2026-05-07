<?php
// Template-variabler forventet fra det kaldende scope:
// $name, $email, $phone, $dateFormatted, $startFormatted, $endFormatted, $num_games, $participants, $note
?>
<div style="font-family: Arial, sans-serif; color: #222; background: #fffbe6; padding: 24px; border-radius: 8px; max-width: 520px; margin: 0 auto;">
	<h2 style="color: #b85c00;">Ny booking modtaget</h2>
	<table style="margin: 16px 0 24px 0; font-size: 15px;">
		<tr><td><strong>Navn:</strong></td><td><?= htmlspecialchars($name) ?></td></tr>
		<tr><td><strong>E-mail:</strong></td><td><?= htmlspecialchars($email) ?></td></tr>
		<tr><td><strong>Telefon:</strong></td><td><?= htmlspecialchars($phone) ?></td></tr>
		<tr><td><strong>Dato:</strong></td><td><?= $dateFormatted ?></td></tr>
		<tr><td><strong>Tid:</strong></td><td><?= $startFormatted ?> – <?= $endFormatted ?></td></tr>
		<tr><td><strong>Antal spil:</strong></td><td><?= $num_games ?></td></tr>
		<tr><td><strong>Antal deltagere:</strong></td><td><?= $participants ?></td></tr>
		<?php if ($note !== ''): ?>
		<tr><td><strong>Note:</strong></td><td><?= htmlspecialchars($note) ?></td></tr>
		<?php endif; ?>
	</table>
</div>
