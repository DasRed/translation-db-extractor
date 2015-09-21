<?php
namespace DasRed\Translation\Db\Extractor\Data\Configuration\Export;

use DasRed\Translation\Db\Extractor\Collection;
use DasRed\Translation\Db\Extractor\Data\Configuration\Export\Entry;

class FieldCollection extends Collection
{

	/**
	 *
	 * @var string
	 */
	protected $tableName;

	/**
	 *
	 * @param string $tableName
	 * @param array $array
	 */
	public function __construct($tableName, array $array = [])
	{
		$this->setTableName($tableName);

		parent::__construct($array);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see ArrayIterator::append()
	 * @param Entry $value
	 */
	public function append($value)
	{
		$this->validate($value)->offsetSet($value->getFieldName(), $value);

		return $this;
	}

	/**
	 *
	 * @param string $fieldName
	 * @param string $idFieldName
	 * @return Entry
	 */
	public function create($fieldName, $idFieldName)
	{
		$entry = new Entry($this->getTableName(), $fieldName, $idFieldName);

		$this->offsetSet($fieldName, $entry);

		return $entry;
	}

	/**
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return $this->tableName;
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
	 * @param string $tableName
	 * @return self
	 */
	public function setTableName($tableName)
	{
		$this->tableName = $tableName;

		return $this;
	}

	/**
	 *
	 * @param Entry $value
	 * @throws \InvalidArgumentException
	 * @return self
	 */
	protected function validate($value)
	{
		if (($value instanceof Entry) === false)
		{
			throw new \InvalidArgumentException('$value must be an instance of ' . Entry::class . '!');
		}

		if ($value->getTableName() !== $this->getTableName())
		{
			throw new \InvalidArgumentException('$value must be setted to the table name ' . $this->getTableName() . '!');
		}

		return $this;
	}
}