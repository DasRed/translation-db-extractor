<?php
namespace DasRed\Translation\Db\Extractor\Database\Connection;

use DasRed\Translation\Db\Extractor\Data\Configuration;

class Factory
{

	/**
	 *
	 * @param Configuration $configuration
	 * @return \PDO
	 */
	public function factory(Configuration $configuration)
	{
		$dsn = $configuration->getDatabase()->driver;
		$dsn .= ':host=' . $configuration->getDatabase()->host;
		$dsn .= $configuration->getDatabase()->offsetExists('port') === true ? ';port=' . $configuration->getDatabase()->port : '';
		$dsn .= ';dbname=' . $configuration->getDatabase()->schema;

		return new \PDO($dsn, $configuration->getDatabase()->username, $configuration->getDatabase()->password, $configuration->getDatabase()->options->toArray());
	}
}
