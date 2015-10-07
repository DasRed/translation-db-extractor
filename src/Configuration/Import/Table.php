<?php
namespace DasRed\Translation\Db\Extractor\Configuration\Import;

use DasRed\Translation\Db\Extractor\Configuration\Import\Exception\PrimaryFieldIsNotDefined;
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
	 * @return string
	 */
	public function getSQLInsert()
	{
		$sql = [];

		/* @var $field Field */
		foreach ($this as $field)
		{
			$value = $field->getValue();
			if ($value === null)
			{
				$value = $field->getDefault();
			}
			if ($value === null)
			{
				continue;
			}

			$sql[] = '`' . $field->getName() . '` = ' . $value;
		}

		return 'INSERT INTO `' . $this->getName() . '` SET ' . implode(',', $sql);
	}

	/**
	 * @return string
	 */
	public function getSQLStore()
	{
		if ($this->getPrimaryField()->getValue() === null)
		{
			return $this->getSQLInsert();
		}

		return $this->getSQLUpdate();
	}

	/**
	 * @return string
	 */
	public function getSQLUpdate()
	{
		$sql = [];

		$primaryField = $this->getPrimaryField();

		/* @var $field Field */
		foreach ($this as $field)
		{
			if ($field === $primaryField)
			{
				continue;
			}

			$value = $field->getValue();
			if ($value === null)
			{
				$value = $field->getDefault();
			}
			if ($value === null)
			{
				continue;
			}

			$sql[] = '`' . $field->getName() . '` = ' . $value;
		}

		return 'UPDATE `' . $this->getName() . '` SET ' . implode(',', $sql) . ' WHERE `' . $primaryField->getName() . '` = ' . $primaryField->getValue();
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