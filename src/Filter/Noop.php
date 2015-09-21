<?php
namespace DasRed\Translation\Db\Extractor\Filter;

use DasRed\Translation\Db\Extractor\FilterInterface;

class Noop implements FilterInterface
{
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
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filter()
	 */
	public function filter($value, $id = null)
	{
		return false;
	}
}