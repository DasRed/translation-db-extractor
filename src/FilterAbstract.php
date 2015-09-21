<?php
namespace DasRed\Translation\Db\Extractor;

use DasRed\Translation\Db\Extractor\Data\Configuration\Export\Entry;
use Zend\Config\Config;

abstract class FilterAbstract implements FilterInterface
{

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 *
	 * @param array $config
	 */
	public function __construct(Config $config = null)
	{
		if ($config instanceof Config)
		{
			$this->setConfig($config);
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::findReference()
	 */
	public function findReference($value)
	{
		return null;
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filterById()
	 */
	public function filterById(Entry $entry, array $row, $value)
	{
		return false;
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filterByValue()
	 */
	public function filterByValue(Entry $entry, array $row, $value)
	{
		return false;
	}

	/**
	 * @return Config
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 *
	 * @param Config $config
	 * @return self
	 */
	protected function setConfig(Config $config)
	{
		$this->config = $config;

		return $this;
	}
}
