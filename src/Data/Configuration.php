<?php
namespace DasRed\Translation\Db\Extractor\Data;

use Zend\Config\Config;
use DasRed\Translation\Db\Extractor\Data\Configuration\Map\TableCollection;
use DasRed\Translation\Db\Extractor\FilterFactory;
use DasRed\Translation\Db\Extractor\Filter\Collection as FilterCollection;

abstract class ConfigurationAbstract
{

	/**
	 * @var Config
	 */
	protected $database;
	/**
	 * @var FilterCollection
	 */
	protected $filter;

	/**
	 *
	 * @var TableCollection
	 */
	protected $map;

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
	 * @param Config $config
	 * @return Config
	 */
	abstract protected function getConfigFilter(Config $config);

	/**
	 * @param Config $config
	 * @return Config
	 */
	abstract protected function getConfigMap(Config $config);

	/**
	 * @return Config
	 */
	public function getDatabase()
	{
		return $this->database;
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
	 * @return TableCollection
	 */
	public function getMap()
	{
		if ($this->map === null)
		{
			$this->map = new TableCollection();
		}

		return $this->map;
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

		// map definitions
		/* @var $fieldList Config */
		foreach ($this->getConfigMap($config) as $tableName => $fieldList)
		{
			/* @var $idFieldList Config */
			foreach ($fieldList as $fieldName => $idFieldList)
			{
				// create with link field name
				if ($idFieldList instanceof Config)
				{
					foreach ($idFieldList as $idFieldName => $linkFieldName)
					{
						$this->getMap()->create($tableName, $fieldName, $idFieldName, $linkFieldName);
					}
				}
				// create without link field name
				else
				{
					$this->getMap()->create($tableName, $fieldName, $idFieldList);
				}
			}
		}

		// filter definitions
		$filterFactory = new FilterFactory();

		/* @var $filterSetting Config */
		foreach ($this->getConfigFilter($config) as $filterSetting)
		{
			if ($filterSetting->offsetExists('name') === false)
			{
				continue;
			}

			$filterOptions = $filterSetting->offsetExists('options') === true ? $filterSetting->options : null;
			$this->getFilter()->append($filterFactory->factory($filterSetting->name, $filterOptions));
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