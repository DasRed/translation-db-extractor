<?php
namespace DasRed\Translation\Db\Extractor\Data\Configuration\Import\Table\Exception;

use DasRed\Translation\Db\Extractor\Data\Configuration\Import\Table\Exception;
use DasRed\Translation\Db\Extractor\Data\Configuration\Import\Table\Content;

class LocaleFieldIsNotDefined extends Exception
{

	/**
	 *
	 * @param Content $table
	 */
	public function __construct(Content $table)
	{
		parent::__construct('For the table "' . $table->getName() . '" is no locale field defined.');
	}
}