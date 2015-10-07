<?php
namespace DasRed\Translation\Db\Extractor\Filter;

use DasRed\Translation\Db\Extractor\FilterAbstract;
use DasRed\Translation\Db\Extractor\Data\Configuration\Map\Entry;

class EmptyValue extends FilterAbstract
{

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filterByValue()
	 */
	public function filterByValue(Entry $entry, array $row, $value)
	{
		// empty, nothing to do
		if (empty($value) === true)
		{
			return true;
		}

		return false;
	}
}