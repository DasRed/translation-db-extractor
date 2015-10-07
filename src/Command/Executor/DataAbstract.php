<?php
namespace DasRed\Translation\Db\Extractor\Command\Executor;

use Zend\Config\Factory;
use DasRed\Translation\Db\Extractor\Command\ExecutorAbstract;
use DasRed\Translation\Db\Extractor\Data\ConfigurationAbstract;
use DasRed\Translation\Db\Extractor\Database\ConnectionAwareInterface;
use DasRed\Translation\Db\Extractor\Database\ConnectionAwareTrait;
use Zend\Config\Config;

abstract class DataAbstract extends ExecutorAbstract implements ConnectionAwareInterface
{
	use ConnectionAwareTrait;

	/**
	 * @var Configuration
	 */
	protected $configuration;

	/**
	 *
	 * @var \DOMDocument
	 */
	protected $xml;

	/**
	 *
	 * @param Config $config
	 * @return ConfigurationAbstract
	 */
	abstract protected function createConfiguration(Config $config);

	/**
	 * @return ConfigurationAbstract
	 */
	protected function getConfiguration()
	{
		if ($this->configuration === null)
		{
			$this->configuration = $this->createConfiguration(Factory::fromFile($this->getFilenameConfiguration(), true));
		}

		return $this->configuration;
	}

	/**
	 * @return string
	 */
	protected function getFilenameConfiguration()
	{
		return $this->getArguments()[0];
	}

	/**
	 * @return string
	 */
	protected function getFilenameXLIFF()
	{
		return $this->getArguments()[1];
	}


	/**
	 * @return \DOMDocument
	 */
	protected function getXml()
	{
		if ($this->xml === null)
		{
			$this->xml = new \DOMDocument();
			$this->xml->formatOutput = true;
			$this->xml->preserveWhiteSpace = true;
			$this->xml->load($this->getXmlFileToLoad());
		}

		return $this->xml;
	}

	/**
	 * @return \DOMElement
	 */
	protected function getXmlDocumentElement()
	{
		return $this->getXml()->documentElement;
	}

	/**
	 * @return string
	 */
	abstract protected function getXmlFileToLoad();


}