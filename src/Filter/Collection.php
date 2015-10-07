<?php
namespace DasRed\Translation\Db\Extractor\Filter;

use Zend\Config\Config;
use DasRed\Translation\Db\Extractor\FilterInterface;
use DasRed\Translation\Db\Extractor\Configuration\Map\Entry;
use DasRed\Translation\Db\Extractor\Configuration\Map\FieldCollection;

class Collection extends \DasRed\Translation\Db\Extractor\Collection implements FilterInterface
{

	/**
	 *
	 * @param array $config
	 * @param array $array
	 */
	public function __construct(Config $config = null, array $array = [])
	{
		parent::__construct($array);
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::findReference()
	 */
	public function findReference($value)
	{
		/* @var $filter FilterInterface */
		foreach ($this as $filter)
		{
			$reference = $filter->findReference($value);
			if ($reference !== null)
			{
				return $reference;
			}
		}

		return null;
	}

	/**
	 *
	 * @param string $method
	 * @param unknown ...$parameters
	 * @return boolean
	 */
	protected function filter($method, ...$parameters)
	{
		/* @var $filter FilterInterface */
		foreach ($this as $filter)
		{
			if ($filter->$method(...$parameters) === true)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filterById()
	 */
	public function filterById(Entry $entry, array $row, $value)
	{
		return $this->filter('filterById', $entry, $row, $value);
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filterByRow()
	 */
	public function filterByRow(FieldCollection $fieldCollection, array $row)
	{
		return $this->filter('filterByRow', $fieldCollection, $row);
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filterByValue()
	 */
	public function filterByValue(Entry $entry, array $row, $value)
	{
		return $this->filter('filterByValue', $entry, $row, $value);
	}
}