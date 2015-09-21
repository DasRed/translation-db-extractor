<?php
namespace DasRed\Translation\Db\Extractor;

interface FilterInterface
{
	/**
	 *
	 * @param string $value
	 * @return string|null
	 */
	public function findReference($value);

	/**
	 * @param string $value
	 * @param mixed $id
	 * @return bool
	 */
	public function filter($value, $id = null);
}