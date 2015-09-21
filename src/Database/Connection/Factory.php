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
		$settings = $configuration->getDatabase();
		$dsn = $settings['driver'];
		$dsn .= ':host=' . $settings['host'];
		$dsn .= empty($settings['port']) === false ? ';port=' . $settings['port'] : '';
		$dsn .= ';dbname=' . ['schema'];

		return new \PDO($dsn, $settings['username'], $settings['password'], $settings['options']);
	}
}
