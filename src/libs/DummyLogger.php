<?php declare(strict_types=1);

namespace App;

/**
 * Log manager.
 */
class DummyLogger
{

	const NAME_ALL_REQUESTS = 'request';

	const FILE_FORMAT = 'Y-m-d';

	/**
	 * One JSON per line
	 *
	 * @see http://jsonlines.org/
	 * @see https://github.com/wardi/jsonlines
	 */
	const FILE_EXTENSION = 'jsonl';
	const LINE_SEPARATOR = PHP_EOL;

	public static function log(string $name, $content): void {
		if (!preg_match('/^[a-zA-Z0-9_]{1,30}$/', $name)) {
			throw new \InvalidArgumentException('Invalid log name.');
		}
		$name = mb_strtolower($name);
		$path = sprintf('%s/log/%s', Config::FOLDER_DATA, $name);
		if (!file_exists($path)) {
			mkdir($path, 0750, true);
		}

		$writeLogObject = new \stdClass();
		$now = new \DateTimeImmutable();
		$writeLogObject->datetime = $now->format(DATE_ISO8601);
		$writeLogObject->log_id = LOG_ID;
		$writeLogObject->name = $name;
		$writeLogObject->content = $content;
		file_put_contents(
			sprintf('%s/%s_%s.%s', $path, $name, $now->format(self::FILE_FORMAT), self::FILE_EXTENSION),
			json_encode($writeLogObject) . self::LINE_SEPARATOR,
			FILE_APPEND
		);
	}
}
