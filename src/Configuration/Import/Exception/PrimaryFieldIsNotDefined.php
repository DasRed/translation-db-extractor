<?php
namespace DasRed\Translation\Db\Extractor\Configuration\Import\Exception;

use DasRed\Translation\Db\Extractor\Configuration\Import\Exception;
use DasRed\Translation\Db\Extractor\Configuration\Import\Table;

class PrimaryFieldIsNotDefined extends Exception
{

	/**
	 *
	 * @param Table $table
	 */
	public function __construct(Table $table)
	{
		parent::__construct('For the table "' . $table->getName() . '" is no primary field defined.');
	}
}