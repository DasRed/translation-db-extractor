<?php
namespace DasRed\Translation\Db\Extractor\Filter;

use DasRed\Translation\Db\Extractor\FilterInterface;
use DasRed\Translation\Db\Extractor\Data\Configuration\Export\Entry;
use Zend\Config\Config;

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
	 * @param Entry $entry
	 * @param array $row
	 * @param string $value
	 */
	protected function filter($method, Entry $entry, array $row, $value)
	{
		/* @var $filter FilterInterface */
		foreach ($this as $filter)
		{
			if ($filter->$method($entry, $row, $value) === true)
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
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filterByValue()
	 */
	public function filterByValue(Entry $entry, array $row, $value)
	{
		return $this->filter('filterByValue', $entry, $row, $value);
	}
}