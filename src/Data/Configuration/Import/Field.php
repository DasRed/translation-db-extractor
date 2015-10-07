<?php
namespace DasRed\Translation\Db\Extractor\Data\Configuration\Import;

use DasRed\Translation\Db\Extractor\Collection\EntryInterface;

class Field implements EntryInterface
{

	/**
	 *  @var string
	 */
	protected $default;

	/**
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @var boolean
	 */
	protected $primary = false;

	/**
	 * @var Table
	 */
	protected $table;

	/**
	 *
	 * @var string
	 */
	protected $value;

	/**
	 *
	 * @param Table $table
	 * @param string $name
	 */
	public function __construct(Table $table, $name)
	{
		$this->setTable($table)->setName($name);
	}

	/**
	 * @return string
	 */
	public function getDefault()
	{
		return $this->default;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getOffsetKey()
	{
		return $this->getName();
	}

	/**
	 * @return Table
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return bool
	 */
	public function isPrimary()
	{
		return $this->primary;
	}

	/**
	 * @param string $default
	 * @return self
	 */
	public function setDefault($default)
	{
		$this->default = $default;

		return $this;
	}

	/**
	 * @param string $name
	 * @return self
	 */
	protected function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @param bool $primary
	 * @return self
	 */
	public function setPrimary($primary)
	{
		$this->primary = (bool)$primary;

		return $this;
	}

	/**
	 * @param Table $table
	 * @return self
	 */
	public function setTable(Table $table)
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * @param string $value
	 * @return self
	 */
	public function setValue($value)
	{
		$this->value = $value;

		return $this;
	}
}