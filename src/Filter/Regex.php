<?php
namespace DasRed\Translation\Db\Extractor\Filter;

use Zend\Config\Config;
use DasRed\Translation\Db\Extractor\FilterAbstract;
use DasRed\Translation\Db\Extractor\Data\Configuration\Map\FieldCollection;

class Regex extends FilterAbstract
{
	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterAbstract::filterByRow()
	 */
	public function filterByRow(FieldCollection $fieldCollection, array $row)
	{
		// find the options
		$options = $this->getOptionsByTableName($fieldCollection->getTableName());
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
			if ($this->isMatched($option->match, $row[$option->field]) === true)
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
	 * @param string $tableName
	 * @return Config[]
	 */
	public function getOptionsByTableName($tableName)
	{
		if ($this->getMatches() === null)
		{
			return [];
		}

		$matches = [];
		/* @var $options Config */
		foreach ($this->getMatches() as $options)
		{
			if ($options->offsetExists('table') === false || $options->offsetExists('field') === false || $options->offsetExists('match') === false)
			{
				continue;
			}

			if ($options->table === $tableName)
			{
				$matches[] = $options;
			}
		}

		return $matches;
	}

	/**
	 *
	 * @param mixed $pattern
	 * @param string $value
	 * @return boolean
	 */
	protected function isMatched($pattern, $value)
	{
		return (bool)preg_match($pattern, (string)$value);
	}
}