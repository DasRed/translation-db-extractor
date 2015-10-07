<?php
namespace DasRed\Translation\Db\Extractor\Data\Configuration;

use Zend\Config\Config;
use DasRed\Translation\Db\Extractor\Data\ConfigurationAbstract;

class Export extends ConfigurationAbstract
{

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\Data\ConfigurationAbstract::getConfigFilter()
	 */
	protected function getConfigFilter(Config $config)
	{
		if ($config->offsetExists('filter') === false || $config->filter->offsetExists('export') === false)
		{
			return new Config([]);
		}

		return $config->filter->export;
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\Data\ConfigurationAbstract::getConfigMap()
	 */
	protected function getConfigMap(Config $config)
	{
		return $config->export;
	}

	/**
	 * @return string
	 */
	public function getXmlTemplateFile()
	{
		return __DIR__ . '/../../../config/template.xml';
	}

	/**
	 *
	 * @param Config $config
	 * @return self
	 */
	protected function setConfigValues(Config $config)
	{
		parent::setConfigValues($config);

		return $this;
	}
}
