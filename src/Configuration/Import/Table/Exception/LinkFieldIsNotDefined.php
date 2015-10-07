<?php
namespace DasRed\Translation\Db\Extractor\Configuration\Import\Table\Exception;

use DasRed\Translation\Db\Extractor\Configuration\Import\Table\Exception;
use DasRed\Translation\Db\Extractor\Configuration\Import\Table\Content;

class LinkFieldIsNotDefined extends Exception
{

	/**
	 *
	 * @param Content $table
	 */
	public function __construct(Content $table)
	{
		parent::__construct('For the table "' . $table->getName() . '" is no link field defined.');
	}
}