<?php
namespace DasRed\Translation\Db\Extractor\Collection;

use DasRed\Translation\Db\Extractor\Collection;

class Object extends Collection
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
	 * cloning
	 */
	public function __clone()
	{
		$fields = $this->getArrayCopy();
		$this->exchangeArray([]);

		/* @var $field Field */
		foreach ($fields as $field)
		{
			$this->append(clone $field);
		}
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see ArrayObject::append()
	 * @param EntryInterface $value
	 */
	public function append($value)
	{
		$this->validate($value)->offsetSet($value->getOffsetKey(), $value);

		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see ArrayObject::offsetSet()
	 * @param EntryInterface $newval
	 */
	public function offsetSet($index, $newval)
	{
		$this->validate($newval);

		parent::offsetSet($newval->getOffsetKey(), $newval);
	}

	/**
	 *
	 * @param EntryInterface $value
	 * @throws \InvalidArgumentException
	 * @return self
	 */
	protected function validate($value)
	{
		if (($value instanceof EntryInterface) === false)
		{
			throw new \InvalidArgumentException('$value must be an instance of ' . EntryInterface::class . '!');
		}

		return $this;
	}
}
