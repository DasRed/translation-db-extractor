<?php
namespace DasRed\Translation\Db\Extractor;

use Zend\Config\Config;

class FilterFactory
{

	/**
	 *
	 * @param string $filter
	 * @param Config $config
	 * @throws \InvalidArgumentException
	 * @return FilterInterface
	 */
	public function factory($filter, Config $config = null)
	{
		$class = '\\DasRed\\Translation\\Db\\Extractor\\Filter\\' . ucfirst($filter);

		if (class_exists($class) === false)
		{
			throw new \InvalidArgumentException('Filter ' . $filter . ' does not exists.');
		}

		return new $class($config);
	}
}