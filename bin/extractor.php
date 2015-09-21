<?php
use Zend\Console\Console;
use Zend\Console\ColorInterface;
use DasRed\Zend\Console\Getopt;
use DasRed\Translation\Db\Extractor\Command\Exception\InvalidArguments;
use DasRed\Translation\Db\Extractor\Command\Factory;

set_error_handler(function ($errno, $errstr, $errfile, $errline, array $errcontext)
{
	throw new Exception($errstr, $errno);
});

require_once __DIR__ . '/../autoload.php';

// consoleoptions
$console = Console::getInstance();
$opt = (new Getopt([
	'help|h' => 'Display this help message'
]))->setOptions([
	Getopt::CONFIG_CUMULATIVE_PARAMETERS => true
]);

$message = 'operation' . PHP_EOL;
$message .= PHP_EOL;

$message .= $console->colorize('data Operations:', ColorInterface::YELLOW) . PHP_EOL;
$message .= $console->colorize(' data export <configuration> <xliff> [filter [filter [...]]]', ColorInterface::GREEN) . '  export all data from database into given xliff file. Filters are: duplicate.' . PHP_EOL;
$message .= $console->colorize(' data import <configuration> <xliff>', ColorInterface::GREEN) . '                          import the data in xliff file into database.' . PHP_EOL;

try
{
	$opt->parse();

	if ($opt->help)
	{
		throw new \Exception('wants help');
	}

	if (!$opt->version && count($opt->getRemainingArgs()) < 2)
	{
		throw new \Exception('missing remaining args');
	}
}
catch (\Exception $exception)
{
	echo $opt->getUsageMessage($message);
	exit(1);
}

try
{
	$executor = (new Factory($console))->factory($opt->getRemainingArgs());
	if ($executor->execute() === false)
	{
		$console->writeLine('Operation failed.', ColorInterface::LIGHT_YELLOW, ColorInterface::LIGHT_RED);
		exit(1);
	}
}
catch (InvalidArguments $exception)
{
	$console->writeLine('Invalid arguments for operation.', ColorInterface::LIGHT_YELLOW, ColorInterface::LIGHT_RED);
	echo PHP_EOL . $opt->getUsageMessage($message);
	exit(1);
}
catch (\Exception $exception)
{
	$console->writeLine($exception->getMessage(), ColorInterface::LIGHT_YELLOW, ColorInterface::LIGHT_RED);
	exit(1);
}

exit(0);