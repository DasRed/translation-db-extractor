<?php
namespace DasRed\Translation\Db\Extractor\Command\Exception;

use DasRed\Translation\Db\Extractor\Command\Exception;

class InvalidCommandOperation extends Exception
{
	/**
	 *
	 * @param string $command
	 */
	public function __construct($command, $operation)
	{
		parent::__construct('Operation "' . $operation . '" not found for command "' . $command . '".');
	}
}