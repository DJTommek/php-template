<?php
declare(strict_types=1);
require_once __DIR__ . '/src/config.php';
// $db = Factory::Database();
?>
	<h1>PHP Template</h1>
	<p>
		Hello world! <?= Icons::CHECKED; ?>
	</p>
	<h2>Module Telegram</h2>
	<form method="GET" action="modules/telegramSendMessage.php" target="_blank">
		<label>
			If you want to send test message, setup 'TELEGRAM_BOT_TOKEN' in data/config.local.php and put some Telegram chat ID here:<br>
			<input type="text" name="telegramChatId" value="1489532856">
			<button type="submit">Odeslat</button>
		</label>
		<p>
			Note: User has to allow your bot to talk in that chat.<br>
			For example if chat ID is your private chat, you need to start conversation with that bot.<br>
			To send message to group chat or channel, bot has to be in this chat and have at least send message permission.
		</p>
	</form>
<?php
