<?php declare(strict_types=1);

DEFINE('LOG_ID', rand(10000, 99999));

$vendorAutoloadFilePath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($vendorAutoloadFilePath)) {
	require_once $vendorAutoloadFilePath;
} else {
	throw new \Exception(sprintf('Missing vendor autoload in "%s". Tip: Did you try to run "composer install" command?', $vendorAutoloadFilePath));
}

$localConfigFilePath = __DIR__ . '/../data/config.local.php';
if (file_exists($localConfigFilePath)) {
	require_once $localConfigFilePath;
} else {
	throw new \Exception(sprintf('Missing local config in "%s".', $localConfigFilePath));
}

Tracy\Debugger::enable(\App\Config::DEVELOPMENT_IPS, \App\Config::FOLDER_DATA . '/tracy-log/');
Tracy\Debugger::$strictMode = true;
Tracy\Debugger::$logSeverity = E_NOTICE | E_WARNING;

if (is_null(\App\Config::TRACY_DEBUGGER_EMAIL) === false) {
	Tracy\Debugger::$email = \App\Config::TRACY_DEBUGGER_EMAIL;
}

// Note: this might a lot of data to log but it's ok for alpha/beta phase. Probably should be removed in stable or production.
\App\DummyLogger::log(\App\DummyLogger::NAME_ALL_REQUESTS, $_SERVER);
