<?php
namespace DasRed\Translation\Db\Extractor\Filter;

use DasRed\Translation\Db\Extractor\FilterAbstract;
use DasRed\Translation\Db\Extractor\Data\Configuration\Export\Entry;
use Zend\Config\Config;

class Regex extends FilterAbstract
{

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filterByValue()
	 */
	public function filterByValue(Entry $entry, array $row, $value)
	{
		// find the options
		$options = $this->getOptionsByEntry($entry);
		if (count($options) === 0)
		{
			return false;
		}

		foreach ($options as $option)
		{
			// find field in row
			if (array_key_exists($option->field, $row) === false)
			{
				continue;
			}

			// match
			if ((bool)preg_match($option->regex, $row[$option->field]) === true)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * @return Config
	 */
	public function getMatches()
	{
		if ($this->getConfig()->offsetExists('matches') === false)
		{
			return null;
		}

		return $this->getConfig()->matches;
	}

	/**
	 *
	 * @param Entry $entry
	 * @return Config[]
	 */
	public function getOptionsByEntry(Entry $entry)
	{
		if ($this->getMatches() === null)
		{
			return [];
		}

		$matches = [];
		/* @var $options Config */
		foreach ($this->getMatches() as $options)
		{
			if ($options->offsetExists('table') === false || $options->offsetExists('field') === false || $options->offsetExists('regex') === false)
			{
				continue;
			}

			if ($options->table === $entry->getTableName())
			{
				$matches[] = $options;
			}
		}

		return $matches;
	}
}