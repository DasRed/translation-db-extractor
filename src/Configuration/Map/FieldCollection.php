<?php
namespace DasRed\Translation\Db\Extractor\Configuration\Map;

use DasRed\Translation\Db\Extractor\Configuration\Map\Entry;
use DasRed\Translation\Db\Extractor\Collection\Object;
use DasRed\Translation\Db\Extractor\Collection\EntryInterface;

class FieldCollection extends Object implements EntryInterface
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
	 *
	 * @param string $fieldName
	 * @param string $idFieldName
	 * @return Entry
	 */
	public function create($fieldName, $idFieldName, $linkFieldName = null)
	{
		$entry = new Entry($this->getTableName(), $fieldName, $idFieldName, $linkFieldName);

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
	 *
	 * @return string
	 */
	public function getOffsetKey()
	{
		return $this->getTableName();
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

		return parent::validate($value);
	}
}