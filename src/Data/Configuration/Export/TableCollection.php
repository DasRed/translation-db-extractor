<?php
namespace DasRed\Translation\Db\Extractor\Data\Configuration\Export;

use DasRed\Translation\Db\Extractor\Collection;
use DasRed\Translation\Db\Extractor\Data\Configuration\Export\Entry;

class TableCollection extends Collection
{

	/**
	 *
	 * @param array $array
	 */
	public function __construct($array = [])
	{
		parent::__construct($array);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see ArrayIterator::append()
	 * @param FieldCollection $value
	 */
	public function append($value)
	{
		$this->validate($value)->offsetSet($value->getTableName(), $value);

		return $this;
	}

	/**
	 *
	 * @param string $tableName
	 * @param string $fieldName
	 * @param string $idFieldName
	 * @return Entry
	 */
	public function create($tableName, $fieldName, $idFieldName)
	{
		if ($this->offsetExists($tableName) === false)
		{
			$this->append(new FieldCollection($tableName));
		}

		/* @var $fieldCollection FieldCollection */
		$fieldCollection = $this->offsetGet($tableName);
		return $fieldCollection->create($fieldName, $idFieldName);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see ArrayIterator::offsetSet()
	 */
	public function offsetSet($index, $newval)
	{
		$this->validate($newval);

		return parent::offsetSet($index, $newval);
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

		return $this;
	}
}