<?php
namespace DasRed\Translation\Db\Extractor\Data;

use Zend\Config\Config;
use DasRed\Translation\Db\Extractor\ConfigurationAbstract;
use DasRed\Translation\Db\Extractor\Data\Configuration\Export\TableCollection;

class Configuration extends ConfigurationAbstract
{
	/**
	 * @var array
	 */
	protected $database;

	/**
	 *
	 * @var TableCollection
	 */
	protected $exportMap;

	/**
	 *
	 * @var string
	 */
	protected $sourceLanguage;

	/**
	 *
	 * @param Config $config
	 */
	public function __construct(Config $config)
	{
		$this->setConfigValues($config);
	}

	/**
	 * @return string[]
	 */
	public function getDatabase()
	{
		return $this->database;
	}

	/**
	 *
	 * @return TableCollection
	 */
	public function getExportMap()
	{
		if ($this->exportMap === null)
		{
			$this->exportMap = new TableCollection();
		}

		return $this->exportMap;
	}

	/**
	 *
	 * @return string
	 */
	public function getSourceLanguage()
	{
		return $this->sourceLanguage;
	}

	/**
	 *
	 * @param Config $config
	 * @return self
	 */
	protected function setConfigValues(Config $config)
	{
		$this->setSourceLanguage($config->general->source->language)->setDatabase($config->database);

		/* @var $fieldList Config */
		foreach ($config->export as $tableName => $fieldList)
		{
			foreach ($fieldList as $fieldName => $idFieldName)
			{
				$this->getExportMap()->create($tableName, $fieldName, $idFieldName);
			}
		}

		return $this;
	}

	/**
	 *
	 * @param array $database
	 * @return self
	 */
	protected function setDatabase(array $database)
	{
		$this->database = $database;

		return $this;
	}

	/**
	 *
	 * @param string $sourceLanguage
	 * @return self
	 */
	protected function setSourceLanguage($sourceLanguage)
	{
		$this->sourceLanguage = $sourceLanguage;

		return $this;
	}
}