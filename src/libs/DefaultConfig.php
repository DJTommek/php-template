<?php declare(strict_types=1);

/**
 * Warning: Never update this file directly, always update config.local.php in data folder!
 *
 * You can override any constant from this file if you want.
 */
class DefaultConfig
{
	const FOLDER_DATA = __DIR__;

	const DB_SERVER = 'localhost';
	const DB_USER = 'dbuser';
	const DB_PASS = 'dbpass';
	const DB_NAME = 'dbschema';

	const DEVELOPMENT_IPS = [
		'12.34.56.78',
	];

	// Put your email if you want to receive emails about errors and exceptions. See https://tracy.nette.org/guide for more info.
	const TRACY_DEBUGGER_EMAIL = null;  // null to disable
	// const TRACY_DEBUGGER_EMAIL = 'admin@your-domain.com';

	// Telegram bot token generated from BotFather: https://t.me/BotFather
	const TELEGRAM_BOT_TOKEN = '123456789:afsddfsggfergfgsadfdiswefqjdfbjfddt';
}
