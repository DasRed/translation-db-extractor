<?php
namespace DasRed\Translation\Db\Extractor\Configuration\Map;

use DasRed\Translation\Db\Extractor\Configuration\Map\Entry;
use DasRed\Translation\Db\Extractor\Collection\Object;

class TableCollection extends Object
{

	/**
	 *
	 * @param string $tableName
	 * @param string $fieldName
	 * @param string $idFieldName
	 * @param string $linkFieldName
	 * @return Entry
	 */
	public function create($tableName, $fieldName, $idFieldName, $linkFieldName = null)
	{
		if ($this->offsetExists($tableName) === false)
		{
			$this->append(new FieldCollection($tableName));
		}

		/* @var $fieldCollection FieldCollection */
		$fieldCollection = $this->offsetGet($tableName);
		return $fieldCollection->create($fieldName, $idFieldName, $linkFieldName);
	}

	/**
	 *
	 * @param FieldCollection $value
	 * @throws \InvalidArgumentException
	 * @return self
	 */
	protected function validate($value)
	{
		if (($value instanceof FieldCollection) === false)
		{
			throw new \InvalidArgumentException('$value must be an instance of ' . FieldCollection::class . '!');
		}

		return parent::validate($value);
	}
}