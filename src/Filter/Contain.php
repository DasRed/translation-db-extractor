<?php
namespace DasRed\Translation\Db\Extractor\Filter;

use Zend\Config\Config;

class Contain extends Regex
{

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\Filter\Regex::isMatched()
	 */
	protected function isMatched($pattern, $value)
	{
		if (is_array($pattern) === true)
		{
			return in_array($value, $pattern);
		}
		elseif ($pattern instanceof Config)
		{
			return in_array($value, $pattern->toArray());
		}
		elseif (is_string($pattern) === true)
		{
			return stripos($pattern, $value) !== false;
		}

		return false;
	}
}