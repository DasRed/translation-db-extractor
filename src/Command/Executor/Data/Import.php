<?php
namespace DasRed\Translation\Db\Extractor\Command\Executor\Data;

use Zend\Console\ColorInterface;
use DasRed\Translation\Db\Extractor\Command\Executor\DataAbstract;
use DasRed\Translation\Db\Extractor\Data\Configuration\Import as ImportConfiguration;
use Zend\Config\Config;

class Import extends DataAbstract
{

	/**
	 *
	 * @var \DOMDocument
	 */
	protected $xml;

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\Command\Executor\DataAbstract::createConfiguration()
	 * @return ImportConfiguration
	 */
	protected function createConfiguration(Config $config)
	{
		return new ImportConfiguration($config);
	}

	/*
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Command\ExecutorAbstract::execute()
	 */
	public function execute()
	{
		try
		{
			$a = $this->getConfiguration();

			// Step 1 ... read XLIFF File into Structure
			// Step 1.1 ... read trans-unit elements
			// Step 1.2 ... read duplicate elements
			// Step 1.3 ... filter
			// Step 2 ... write to database

			$this->getConsole()->writeLine('XLIFF parsed and database updated.', ColorInterface::BLACK, ColorInterface::LIGHT_GREEN);
		}
		catch (\Exception $exception)
		{
			$this->getConsole()->writeLine('XLIFF not parsed and database not updated.', ColorInterface::LIGHT_YELLOW, ColorInterface::LIGHT_RED);
			return false;
		}

		return true;
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\Command\Executor\DataAbstract::getConfiguration()
	 * @return ImportConfiguration
	 */
	protected function getConfiguration()
	{
		return parent::getConfiguration();
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\Command\Executor\DataAbstract::getXmlFileToLoad()
	 */
	protected function getXmlFileToLoad()
	{
		return $this->getFilenameXLIFF();
	}

	/*
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Command\ExecutorAbstract::validateArguments()
	 */
	protected function validateArguments($arguments)
	{
		if (count($arguments) != 2)
		{
			return false;
		}

		return parent::validateArguments($arguments);
	}
}