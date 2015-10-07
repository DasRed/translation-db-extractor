<?php
namespace DasRed\Translation\Db\Extractor\Database\Connection;

use DasRed\Translation\Db\Extractor\ConfigurationAbstract;

class Factory
{

	/**
	 *
	 * @param ConfigurationAbstract $configuration
	 * @return \PDO
	 */
	public function factory(ConfigurationAbstract $configuration)
	{
		$dsn = $configuration->getDatabase()->driver;
		$dsn .= ':host=' . $configuration->getDatabase()->host;
		$dsn .= $configuration->getDatabase()->offsetExists('port') === true ? ';port=' . $configuration->getDatabase()->port : '';
		$dsn .= ';dbname=' . $configuration->getDatabase()->schema;

		return new \PDO($dsn, $configuration->getDatabase()->username, $configuration->getDatabase()->password, $configuration->getDatabase()->options->toArray());
	}
}
