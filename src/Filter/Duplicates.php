<?php
namespace DasRed\Translation\Db\Extractor\Filter;

use DasRed\Translation\Db\Extractor\FilterInterface;

class Duplicates implements FilterInterface
{

	/**
	 *
	 * @var string[]
	 */
	protected $list = [];

	/**
	 *
	 * @param array $options
	 */
	public function __construct(array $options = [])
	{
		// nothing to do
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::findReference()
	 */
	public function findReference($value)
	{
		$hash = sha1($value);

		if (array_key_exists($hash, $this->list) === false)
		{
			return null;
		}

		return $this->list[$hash]['id'];
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filter()
	 */
	public function filter($value, $id = null)
	{
		if ($id === null)
		{
			throw new \InvalidArgumentException('Parameter id can not be null for ' . self::class);
		}

		$hash = sha1($value);

		if (array_key_exists($hash, $this->list) === true)
		{
			$this->list[$hash]['ids'][] = $id;

			return true;
		}

		$this->list[$hash] = [
			'value' => $value,
			'id' => $id,
			'ids' => [
				$id
			]
		];

		return false;
	}
}