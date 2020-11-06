<?php declare(strict_types=1);

namespace App;

use React\EventLoop\Factory;
use Tracy\Debugger;
use Tracy\ILogger;
use unreal4u\TelegramAPI\HttpClientRequestHandler;
use unreal4u\TelegramAPI\TgLog;

class TelegramCustomWrapper
{
	private $tgLog;
	private $loop;

	public function __construct($telegramBotToken)
	{
		$this->loop = Factory::create();
		$this->tgLog = new TgLog($telegramBotToken, new HttpClientRequestHandler($this->loop));
	}

	public function run($objectToSend): \React\Promise\PromiseInterface
	{
		$promise = $this->tgLog->performApiRequest($objectToSend);
		$this->loop->run();

		$promise->then(
			null,
			function (\Exception $exception) {
				Debugger::log($exception, ILogger::EXCEPTION);
			}
		);
		return $promise;
	}
}
