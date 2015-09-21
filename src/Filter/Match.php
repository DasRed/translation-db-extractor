<?php
namespace DasRed\Translation\Db\Extractor\Filter;

class Match extends Regex
{

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\Filter\Regex::isMatched()
	 */
	protected function isMatched($pattern, $value)
	{
		return (bool)fnmatch($pattern, $value, FNM_CASEFOLD);
	}
}