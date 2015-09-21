<?php
namespace DasRed\Translation\Db\Extractor\Data;

use Zend\Config\Config;
use DasRed\Translation\Db\Extractor\ConfigurationAbstract;
use DasRed\Translation\Db\Extractor\Data\Configuration\Export\TableCollection;
use DasRed\Translation\Db\Extractor\Filter\Collection as FilterCollection;
use DasRed\Translation\Db\Extractor\FilterFactory;

class Configuration extends ConfigurationAbstract
{

	/**
	 * @var Config
	 */
	protected $database;

	/**
	 *
	 * @var TableCollection
	 */
	protected $exportMap;

	/**
	 * @var FilterCollection
	 */
	protected $filter;

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
	 * @return Config
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
	 * @return FilterCollection
	 */
	public function getFilter()
	{
		if ($this->filter === null)
		{
			$this->filter = new FilterCollection();
		}

		return $this->filter;
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
	 * @return string
	 */
	public function getXmlTemplateFile()
	{
		return __DIR__ . '/../../config/template.xml';
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

		// filter list
		if ($config->general->offsetExists('filter') === true)
		{
			$filterFactory = new FilterFactory();

			/* @var $filterSetting Config */
			foreach ($config->general->filter as $filterSetting)
			{
				if ($filterSetting->offsetExists('name') === false)
				{
					continue;
				}

				$filterOptions = $filterSetting->offsetExists('options') === true ? $filterSetting->options : [];
				$this->getFilter()->append($filterFactory->factory($filterSetting->name, $filterOptions));
			}
		}

		return $this;
	}

	/**
	 *
	 * @param Config $database
	 * @return self
	 */
	protected function setDatabase(Config $database)
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