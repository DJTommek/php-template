<?php declare(strict_types=1);

namespace App;

use Tracy\Debugger;
use Tracy\ILogger;
use unreal4u\TelegramAPI\Abstracts\TelegramMethods;
use unreal4u\TelegramAPI\HttpClientRequestHandler;
use unreal4u\TelegramAPI\TgLog;

class TelegramCustomWrapper
{
	/** @var TgLog */
	private $tgLog;
	/** @var \React\EventLoop\ExtEventLoop|\React\EventLoop\ExtEvLoop|\React\EventLoop\ExtLibeventLoop|\React\EventLoop\ExtLibevLoop|\React\EventLoop\ExtUvLoop|\React\EventLoop\LoopInterface|\React\EventLoop\StreamSelectLoop */
	private $loop;

	public function __construct(string $telegramBotToken)
	{
		$this->loop = \React\EventLoop\Factory::create();
		$this->tgLog = new TgLog($telegramBotToken, new HttpClientRequestHandler($this->loop));
	}

	public function run(TelegramMethods $objectToSend): \React\Promise\PromiseInterface
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
