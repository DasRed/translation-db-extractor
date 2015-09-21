<?php
namespace DasRed\Translation\Db\Extractor\Filter;

use DasRed\Translation\Db\Extractor\FilterAbstract;
use DasRed\Translation\Db\Extractor\Data\Configuration\Export\Entry;

class Duplicates extends FilterAbstract
{

	/**
	 *
	 * @var string[]
	 */
	protected $list = [];

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
	 * @see \DasRed\Translation\Db\Extractor\FilterInterface::filterById()
	 */
	public function filterById(Entry $entry, array $row, $value)
	{
		$id = $entry->getIdLevelMax();
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