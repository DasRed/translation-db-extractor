<?php
namespace DasRed\Translation\Db\Extractor\Configuration\Map;

use DasRed\Translation\Db\Extractor\Collection\EntryInterface;

class Entry implements EntryInterface
{

	/**
	 *
	 * @var string
	 */
	protected $fieldName;

	/**
	 *
	 * @var mixed
	 */
	protected $id;

	/**
	 *
	 * @var string
	 */
	protected $idFieldName;

	/**
	 *
	 * @var string
	 */
	protected $linkFieldName;

	/**
	 *
	 * @var string
	 */
	protected $tableName;

	/**
	 *
	 * @param string $tableName
	 * @param string $fieldName
	 * @param string $idFieldName
	 * @param string $linkFieldName
	 */
	public function __construct($tableName, $fieldName, $idFieldName, $linkFieldName = null)
	{
		$this->setTableName($tableName)->setFieldName($fieldName)->setIdFieldName($idFieldName)->setLinkFieldName($linkFieldName);
	}

	/**
	 *
	 * @return string
	 */
	public function getFieldName()
	{
		return $this->fieldName;
	}

	/**
	 *
	 * @throws \Exception
	 * @return string
	 */
	public function getId()
	{
		if ($this->id === null)
		{
			throw new \Exception('Id is not setted.');
		}

		return $this->id;
	}

	/**
	 *
	 * @return string
	 */
	public function getIdFieldName()
	{
		return $this->idFieldName;
	}

	/**
	 * @return string
	 */
	public function getIdLevel1()
	{
		return $this->getTableName();
	}

	/**
	 * @return string
	 */
	public function getIdLevel2()
	{
		return $this->getIdLevel1() . '.' . $this->getFieldName();
	}

	/**
	 * @return string
	 */
	public function getIdLevel3()
	{
		return $this->getIdLevel2() . '.' . $this->getId();
	}

	/**
	 * @return string
	 */
	public function getIdLevelMax()
	{
		return $this->getIdLevel3();
	}

	/**
	 * @return string
	 */
	public function getLinkFieldName()
	{
		return $this->linkFieldName;
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\Collection\EntryInterface::getOffsetKey()
	 */
	public function getOffsetKey()
	{
		return $this->getFieldName();
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
	 * @param string $fieldName
	 * @return self
	 */
	protected function setFieldName($fieldName)
	{
		$this->fieldName = $fieldName;

		return $this;
	}

	/**
	 * @param mixed $id
	 * @return self
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 *
	 * @param array $row
	 * @throws \Exception
	 * @return self
	 */
	public function setIdFromRow(array $row)
	{
		if (array_key_exists($this->getIdFieldName(), $row) === false)
		{
			throw new \Exception('Id fieldname ' . $this->getIdFieldName() . ' is not defined in row.');
		}

		return $this->setId($row[$this->getIdFieldName()]);
	}

	/**
	 *
	 * @param string $idFieldName
	 * @return self
	 */
	protected function setIdFieldName($idFieldName)
	{
		$this->idFieldName = $idFieldName;

		return $this;
	}

	/**
	 * @param string $linkFieldName
	 * @return self
	 */
	public function setLinkFieldName($linkFieldName)
	{
		$this->linkFieldName = $linkFieldName;

		return $this;
	}

	/**
	 *
	 * @param string $tableName
	 * @return self
	 */
	protected function setTableName($tableName)
	{
		$this->tableName = $tableName;

		return $this;
	}
}
