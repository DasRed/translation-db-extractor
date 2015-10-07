<?php
namespace DasRed\Translation\Db\Extractor;

use Zend\Config\Config;
use DasRed\Translation\Db\Extractor\Configuration\Map\Entry;
use DasRed\Translation\Db\Extractor\Configuration\Map\FieldCollection;

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
	 *
	 * @param FieldCollection $fieldCollection
	 * @param array $row
	 * @return bool
	 */
	public function filterByRow(FieldCollection $fieldCollection, array $row);

	/**
	 * @param Entry $entry
	 * @param array $row
	 * @param string $value
	 * @return bool
	 */
	public function filterByValue(Entry $entry, array $row, $value);
}
