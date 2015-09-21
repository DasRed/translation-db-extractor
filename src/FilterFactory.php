<?php
namespace DasRed\Translation\Db\Extractor;

class FilterFactory
{

	/**
	 *
	 * @param string $filter
	 * @throws \InvalidArgumentException
	 * @return FilterInterface
	 */
	public function factory($filter)
	{
		$class = '\\DasRed\\Translation\\Db\\Extractor\\Filter\\' . ucfirst($filter);

		if (class_exists($class) === false)
		{
			throw new \InvalidArgumentException('Filter ' . $filter . ' does not exists.');
		}

		return new $class();
	}
}