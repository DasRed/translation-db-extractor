<?php
namespace DasRed\Translation\Db\Extractor\Filter;

use DasRed\Translation\Db\Extractor\FilterInterface;

class Collection extends \DasRed\Translation\Db\Extractor\Collection implements FilterInterface
{
	/**
	 *
	 * @param array $options
	 * @param array $array
	 */
	public function __construct(array $options = [], array $array = [])
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
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filter()
	 */
	public function filter($value, $id = null)
	{
		/* @var $filter FilterInterface */
		foreach ($this as $filter)
		{
			if ($filter->filter($value, $id) === true)
			{
				return true;
			}
		}

		return false;
	}
}