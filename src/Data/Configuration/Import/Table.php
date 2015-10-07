<?php
namespace DasRed\Translation\Db\Extractor\Data\Configuration\Import;

use DasRed\Translation\Db\Extractor\Data\Configuration\Import\Exception\PrimaryFieldIsNotDefined;
use DasRed\Translation\Db\Extractor\Collection\Object;
use DasRed\Translation\Db\Extractor\Collection\EntryInterface;
use Zend\Config\Config;

class Table extends Object implements EntryInterface
{

	/**
	 *
	 * @var string
	 */
	protected $name;

	/**
	 *
	 * @param Config $fieldConfig
	 * @return Field
	 */
	public function create(Config $fieldConfig)
	{
		$field = $this->createFieldInstance($fieldConfig->name);

		if ($fieldConfig->offsetExists('isPrimary') === true)
		{
			$field->setPrimary($fieldConfig->offsetGet('isPrimary'));
		}

		if ($fieldConfig->offsetExists('default') === true)
		{
			$field->setDefault($fieldConfig->offsetGet('default'));
		}

		$this->append($field);

		return $field;
	}

	/**
	 *
	 * @param string $name
	 * @return Field
	 */
	protected function createFieldInstance($name)
	{
		return new Field($this, $name);
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
	 *
	 * @return Field
	 * @throws PrimaryFieldIsNotDefined
	 */
	public function getPrimaryField()
	{
		$field = $this->find(function (Field $field)
		{
			return $field->isPrimary();
		});

		if ($field === null)
		{
			throw new PrimaryFieldIsNotDefined($this);
		}

		return $field;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see ArrayIterator::offsetSet()
	 * @param Field $newval
	 */
	public function offsetSet($index, $newval)
	{
		parent::offsetSet($index, $newval);
		$newval->setTable($this);
	}

	/**
	 * @param string $name
	 * @return self
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 *
	 * @param Field $value
	 * @throws \InvalidArgumentException
	 * @return self
	 */
	protected function validate($value)
	{
		if (($value instanceof Field) === false)
		{
			throw new \InvalidArgumentException('$value must be an instance of ' . Field::class . '!');
		}

		return parent::validate($value);
	}
}