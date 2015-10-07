<?php
namespace DasRed\Translation\Db\Extractor\Database;

use DasRed\Translation\Db\Extractor\ConfigurationAbstract;
use DasRed\Translation\Db\Extractor\Database\Connection\Factory;

trait ConnectionAwareTrait
{

	/**
	 *
	 * @var \PDO
	 */
	protected $connection;

	/**
	 * @return ConfigurationAbstract
	 */
	abstract protected function getConfiguration();

	/**
	 * @return \PDO
	 */
	public function getConnection()
	{
		if ($this->connection === null)
		{
			$this->connection = (new Factory())->factory($this->getConfiguration());
		}

		return $this->connection;
	}

	public function setConnection(\PDO $connection)
	{
		$this->connection = $connection;

		return $this;
	}
}