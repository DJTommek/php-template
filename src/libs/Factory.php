<?php declare(strict_types=1);

namespace App;

class Factory
{
	/** @var array<mixed> */
	private static $objects = [];

	static function Database(): Database
	{
		if (!isset(self::$objects['database'])) {
			self::$objects['database'] = new Database(Config::DB_SERVER, Config::DB_NAME, Config::DB_USER, Config::DB_PASS);
		}
		return self::$objects['database'];
	}

	static function Telegram(): TelegramCustomWrapper
	{
		if (!isset(self::$objects['telegram'])) {
			self::$objects['telegram'] = new TelegramCustomWrapper(Config::TELEGRAM_BOT_TOKEN);
		}
		return self::$objects['telegram'];
	}
}
