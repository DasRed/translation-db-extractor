<?php
namespace DasRed\Translation\Db\Extractor\Configuration;

use Zend\Config\Config;
use DasRed\Translation\Db\Extractor\ConfigurationAbstract;
use DasRed\Translation\Db\Extractor\Configuration\Import\Table;

class Import extends ConfigurationAbstract
{

	/**
	 *
	 * @var Table\Content
	 */
	protected $tableContent;

	/**
	 *
	 * @var Table
	 */
	protected $tableLink;

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\ConfigurationAbstract::getConfigFilter()
	 */
	protected function getConfigFilter(Config $config)
	{
		if ($config->offsetExists('filter') === false || $config->filter->offsetExists('import') === false)
		{
			return new Config([]);
		}

		return $config->filter->import;
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\ConfigurationAbstract::getConfigMap()
	 */
	protected function getConfigMap(Config $config)
	{
		return $config->import->map;
	}

	/**
	 * @return Table\Content
	 */
	public function getTableContent()
	{
		if ($this->tableContent === null)
		{
			$this->tableContent = new Table\Content();
		}

		return $this->tableContent;
	}

	/**
	 * @return Table
	 */
	public function getTableLink()
	{
		if ($this->tableLink === null)
		{
			$this->tableLink = new Table();
		}

		return $this->tableLink;
	}

	/**
	 *
	 * @param Config $config
	 * @return self
	 */
	protected function setConfigValues(Config $config)
	{
		parent::setConfigValues($config);

		// table link
		$this->getTableLink()->setName($config->import->link->name);

		/* @var $fieldConfig Config */
		foreach ($config->import->link->field as $fieldConfig)
		{
			$this->getTableLink()->create($fieldConfig);
		}

		// table content
		$this->getTableContent()->setName($config->import->content->name);

		/* @var $fieldConfig Config */
		foreach ($config->import->content->field as $fieldConfig)
		{
			$this->getTableContent()->create($fieldConfig);
		}

		return $this;
	}
}
