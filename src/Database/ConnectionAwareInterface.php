<?php
namespace DasRed\Translation\Db\Extractor\Database;

interface ConnectionAwareInterface
{
	/**
	 * @return \PDO
	 */
	public function getConnection();
}