<?php
namespace DasRed\Translation\Db\Extractor;

class FilterFactory
{

	/**
	 *
	 * @param string $filter
	 * @param array $options
	 * @throws \InvalidArgumentException
	 * @return FilterInterface
	 */
	public function factory($filter, array $options = [])
	{
		$class = '\\DasRed\\Translation\\Db\\Extractor\\Filter\\' . ucfirst($filter);

		if (class_exists($class) === false)
		{
			throw new \InvalidArgumentException('Filter ' . $filter . ' does not exists.');
		}

		return new $class($options);
	}
}