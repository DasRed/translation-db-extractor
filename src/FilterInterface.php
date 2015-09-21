<?php
namespace DasRed\Translation\Db\Extractor;

use DasRed\Translation\Db\Extractor\Data\Configuration\Export\Entry;
use Zend\Config\Config;

interface FilterInterface
{

	/**
	 * @param Config $config
	 */
	public function __construct(Config $config = null);

	/**
	 *
	 * @param string $value
	 * @return string|null
	 */
	public function findReference($value);

	/**
	 * @param Entry $entry
	 * @param array $row
	 * @param string $value
	 * @return bool
	 */
	public function filterById(Entry $entry, array $row, $value);

	/**
	 * @param Entry $entry
	 * @param array $row
	 * @param string $value
	 * @return bool
	 */
	public function filterByValue(Entry $entry, array $row, $value);
}
