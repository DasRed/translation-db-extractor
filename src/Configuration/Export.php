<?php
namespace DasRed\Translation\Db\Extractor\Configuration;

use Zend\Config\Config;
use DasRed\Translation\Db\Extractor\ConfigurationAbstract;

class Export extends ConfigurationAbstract
{
	/**
	 *
	 * @var string
	 */
	protected $xmlTemplateFile;

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\ConfigurationAbstract::getConfigFilter()
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
	 * @see \DasRed\Translation\Db\Extractor\ConfigurationAbstract::getConfigMap()
	 */
	protected function getConfigMap(Config $config)
	{
		return $config->export->map;
	}

	/**
	 * @return string
	 */
	public function getXmlTemplateFile()
	{
		return $this->xmlTemplateFile;
	}

	/**
	 *
	 * @param Config $config
	 * @return self
	 */
	protected function setConfigValues(Config $config)
	{
		parent::setConfigValues($config);

		$this->setXmlTemplateFile($config->export->template);

		return $this;
	}

	/**
	 *
	 * @param string $xmlTemplateFile
	 * @return self
	 */
	protected function setXmlTemplateFile($xmlTemplateFile)
	{
		$this->xmlTemplateFile = $xmlTemplateFile;

		return $this;
	}
}
