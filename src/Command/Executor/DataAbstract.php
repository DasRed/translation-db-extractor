<?php
namespace DasRed\Translation\Db\Extractor\Command\Executor;

use Zend\Config\Factory;
use DasRed\Translation\Db\Extractor\Command\ExecutorAbstract;
use DasRed\Translation\Db\Extractor\Data\Configuration;
use DasRed\Translation\Db\Extractor\Database\ConnectionAwareInterface;
use DasRed\Translation\Db\Extractor\Database\ConnectionAwareTrait;

abstract class DataAbstract extends ExecutorAbstract implements ConnectionAwareInterface
{
	use ConnectionAwareTrait;

	/**
	 * @var Configuration
	 */
	protected $configuration;

	/**
	 * @return Configuration
	 */
	protected function getConfiguration()
	{
		if ($this->configuration === null)
		{
			$this->configuration = new Configuration(Factory::fromFile($this->getFilenameConfiguration(), true));
		}

		return $this->configuration;
	}

	/**
	 * @return string
	 */
	protected function getFilenameConfiguration()
	{
		return $this->getArguments()[0];
	}

	/**
	 * @return string
	 */
	protected function getFilenameXLIFF()
	{
		return $this->getArguments()[1];
	}
}