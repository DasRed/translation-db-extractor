<?php
namespace DasRed\Translation\Db\Extractor\Command\Executor\Data;

use Zend\Console\ColorInterface;
use Zend\ProgressBar\Adapter\Console;
use Zend\ProgressBar\ProgressBar;
use DasRed\Translation\Db\Extractor\Command\Executor\DataAbstract;
use DasRed\Translation\Db\Extractor\Data\Configuration\Export\FieldCollection;

class Export extends DataAbstract
{

	/**
	 *
	 * @var \DOMDocument
	 */
	protected $xml;

	/**
	 * @var \DOMElement[]
	 */
	protected $xmlFileBodyElement = [];

	/**
	 * @var \DOMElement[]
	 */
	protected $xmlFileElement = [];

	/**
	 * @var \DOMElement[]
	 */
	protected $xmlFileHeaderElement = [];

	/*
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Command\ExecutorAbstract::execute()
	 */
	public function execute()
	{
		try
		{
			/* @var $fieldCollection \DasRed\Translation\Db\Extractor\Data\Configuration\Export\FieldCollection */
			foreach ($this->getConfiguration()->getExportMap() as $fieldCollection)
			{
				// outputting
				$this->getConsole()->write('Reading table ');
				$this->getConsole()->write($fieldCollection->getTableName(), ColorInterface::LIGHT_YELLOW);
				$this->getConsole()->write(': ');

				// load data from database
				$result = $this->getConnection()->query('SELECT * FROM `' . $fieldCollection->getTableName() . '`')->fetchAll(\PDO::FETCH_ASSOC);
				$count = count($result);
				// nothing to do
				if ($count === 0)
				{
					$this->getConsole()->writeLine('Done', ColorInterface::LIGHT_GREEN);
					continue;
				}

				$progressBar = new ProgressBar(new Console([
					'finishAction' => Console::FINISH_ACTION_CLEAR_LINE
				]), 0, $count);
				$progressBar->update(0);

				// loop over the data from database
				for ($i = 0; $i < $count; $i++)
				{
					// handle every line
					$this->handleRowFromDatabaseForFieldCollection($fieldCollection, $result[$i]);
					$progressBar->next();
				}

				$progressBar->finish();

				// outputting
				$this->getConsole()->writeLine('Done', ColorInterface::LIGHT_GREEN);
			}

			// write the XML file
			$this->getXml()->save($this->getFilenameXLIFF(), LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

			$this->getConsole()->writeLine('XLIFF created.', ColorInterface::BLACK, ColorInterface::LIGHT_GREEN);
		}
		catch (\Exception $exception)
		{
			$this->getConsole()->writeLine('XLIFF can not be created.', ColorInterface::LIGHT_YELLOW, ColorInterface::LIGHT_RED);
			return false;
		}

		return true;
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
			$this->xml->load($this->getConfiguration()->getXmlTemplateFile());
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
	 * @param string $id
	 * @return \DOMElement
	 */
	protected function getXmlFileBodyElement($id)
	{
		if (array_key_exists($id, $this->xmlFileBodyElement) === false)
		{
			$this->xmlFileBodyElement[$id] = $this->getXml()->createElement('body');
			$this->getXmlFileElement($id)->appendChild($this->xmlFileBodyElement[$id]);
		}

		return $this->xmlFileBodyElement[$id];
	}

	/**
	 *
	 * @param string $id
	 * @return \DOMElement
	 */
	protected function getXmlFileElement($id)
	{
		if (array_key_exists($id, $this->xmlFileElement) === false)
		{
			// create the file element
			$this->xmlFileElement[$id] = $xmlFileElement = $this->getXml()->createElement('file');
			$this->getXmlDocumentElement()->appendChild($xmlFileElement);
			$xmlFileElement->setAttribute('source-language', $this->getConfiguration()->getSourceLanguage());
			$xmlFileElement->setAttribute('datatype', 'database');
			$xmlFileElement->setAttribute('original', $id);
		}

		return $this->xmlFileElement[$id];
	}

	/**
	 * @param string $id
	 * @return \DOMElement
	 */
	protected function getXmlFileHeaderElement($id)
	{
		if (array_key_exists($id, $this->xmlFileHeaderElement) === false)
		{
			$this->xmlFileHeaderElement[$id] = $this->getXml()->createElement('header');
			$this->getXmlFileElement($id)->insertBefore($this->xmlFileHeaderElement[$id], $this->getXmlFileBodyElement($id));
		}

		return $this->xmlFileHeaderElement[$id];
	}

	/**
	 *
	 * @param FieldCollection $fieldCollection
	 * @param array $row
	 * @return self
	 */
	protected function handleRowFromDatabaseForFieldCollection(FieldCollection $fieldCollection, array $row)
	{
		if ($this->getConfiguration()->getFilterExport()->filterByRow($fieldCollection, $row) === true)
		{
			return $this;
		}

		foreach ($row as $fieldName => $value)
		{
			// convert to UTF-8
			if (mb_detect_encoding($value) !== 'UTF-8')
			{
				$value = mb_convert_encoding($value, 'UTF-8');
			}

			// find the entry. if not exists, go next
			if ($fieldCollection->offsetExists($fieldName) === false)
			{
				continue;
			}

			// get the entry
			$entry = $fieldCollection->offsetGet($fieldName);
			try
			{
				// test and set the ID Value
				$entry->setIdFromRow($row);
			}
			catch (\Exception $exception)
			{
				continue;
			}

			// filter by value
			if ($this->getConfiguration()->getFilterExport()->filterByValue($entry, $row, $value) === true)
			{
				continue;
			}

			// filter Before Node Creation
			if ($this->getConfiguration()->getFilterExport()->filterById($entry, $row, $value) === true)
			{
				$idReferenceLevel3 = $this->getConfiguration()->getFilterExport()->findReference($value);
				$idReferenceLevel2 = substr($idReferenceLevel3, 0, strrpos($idReferenceLevel3, '.'));

				$xmlFileHeaderReferenceElement = $this->getXml()->createElement('reference');
				$xmlFileHeaderReferenceElement->appendChild($this->getXml()->createElement('internal-file', json_encode([
					'duplicateId' => $entry->getIdLevel3(),
					'referenceId' => $idReferenceLevel3
				])));
				$this->getXmlFileHeaderElement($idReferenceLevel2)->appendChild($xmlFileHeaderReferenceElement);
				continue;
			}

			// create the trans-unit
			$xmlFileBodyTransUnitElement = $this->getXml()->createElement('trans-unit');
			$xmlFileBodyTransUnitElement->setAttribute('id', $entry->getIdLevel3());
			$this->getXmlFileBodyElement($entry->getIdLevel2())->appendChild($xmlFileBodyTransUnitElement);

			// create the source element
			$xmlFileBodyTransUnitSourceElement = $this->getXml()->createElement('source', htmlspecialchars($value));
			$xmlFileBodyTransUnitSourceElement->setAttribute('xml:lang', $this->getConfiguration()->getSourceLanguage());
			$xmlFileBodyTransUnitElement->appendChild($xmlFileBodyTransUnitSourceElement);

			// create the target element
			$xmlFileBodyTransUnitTargetElement = $this->getXml()->createElement('target', htmlspecialchars($value));
			$xmlFileBodyTransUnitTargetElement->setAttribute('xml:lang', $this->getConfiguration()->getSourceLanguage());
			$xmlFileBodyTransUnitElement->appendChild($xmlFileBodyTransUnitTargetElement);
		}

		return $this;
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