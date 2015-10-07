<?php
namespace DasRed\Translation\Db\Extractor\Command\Executor\Data;

use Zend\Console\ColorInterface;
use DasRed\Translation\Db\Extractor\Command\Executor\DataAbstract;
use DasRed\Translation\Db\Extractor\Configuration\Import as ImportConfiguration;
use Zend\Config\Config;
use Zend\ProgressBar\ProgressBar;
use Zend\ProgressBar\Adapter\Console;
use DasRed\Translation\Db\Extractor\Import\Collection;
use DasRed\Translation\Db\Extractor\Import\Entry as ImportEntry;
use DasRed\Translation\Db\Extractor\Configuration\Map\Entry as MapEntry;

class Import extends DataAbstract
{

	/**
	 * @var Collection
	 */
	protected $data;

	/**
	 *
	 * @var \DOMDocument
	 */
	protected $xml;

	/**
	 * @var \DOMXPath
	 */
	protected $xpath;

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
			$this->parseTargetList()->parseDuplicateList()->updateDatabase();

			$this->getConsole()->writeLine('XLIFF parsed and database updated.', ColorInterface::BLACK, ColorInterface::LIGHT_GREEN);
		}
		catch (\Exception $exception)
		{
			$this->getConsole()->writeLine('XLIFF not parsed and database not updated.', ColorInterface::LIGHT_YELLOW, ColorInterface::LIGHT_RED);
			$this->getConsole()->writeLine();
			$this->getConsole()->writeLine($exception->getMessage() . PHP_EOL . PHP_EOL . $exception->getTraceAsString(), ColorInterface::LIGHT_YELLOW, ColorInterface::LIGHT_RED);
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
	 * @return Collection
	 */
	protected function getData()
	{
		if ($this->data === null)
		{
			$this->data = new Collection();
		}

		return $this->data;
	}

	/**
	 * @return \DOMDocument
	 */
	protected function getXml()
	{
		if ($this->xml === null)
		{
			$xmlOld = parent::getXml();
			$xmlString = $xmlOld->saveXML();
			$xmlString = str_replace([
				'xmlns="urn:oasis:names:tc:xliff:document:1.2"',
				'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
				'xsi:schemaLocation="urn:oasis:names:tc:xliff:document:1.2 xliff-core-1.2-strict.xsd"'
			], '', $xmlString);

			$this->xml = new \DOMDocument();
			$this->xml->formatOutput = true;
			$this->xml->preserveWhiteSpace = true;
			$this->xml->loadXML($xmlString);
		}

		return $this->xml;
	}

	/**
	 *
	 * @param ImportEntry $entry
	 * @param MapEntry $field
	 * @return int|bool
	 */
	protected function getTextLinkIdForEntry(ImportEntry $entry, MapEntry $field)
	{
		// get text link id form source
		$textLinkId = $this->getConnection()->query('
			SELECT `' . $field->getLinkFieldName() . '`
			FROM `' . $field->getTableName() . '`
			WHERE `' . $field->getIdFieldName() . '` = ' . $this->getConnection()->quote($entry->getId()) . '
		')->fetchColumn();

		// no text link id... create a text link
		if ($textLinkId === null)
		{
			// create text link id
			if ($this->getConnection()->exec($this->getConfiguration()->getTableLink()->getSQLInsert()) === false)
			{
				return false;
			}
			$textLinkId = $this->getConnection()->lastInsertId();

			// update text link id
			$textLinkId = $this->getConnection()->exec('
				UPDATE `' . $field->getTableName() . '`
				SET `' . $field->getLinkFieldName() . '` = ' . $textLinkId . '
				WHERE `' . $field->getIdFieldName() . '` = ' . $this->getConnection()->quote($entry->getId()) . '
			');
		}

		return $textLinkId;
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\Command\Executor\DataAbstract::getXmlFileToLoad()
	 */
	protected function getXmlFileToLoad()
	{
		return $this->getFilenameXLIFF();
	}

	/**
	 * @return \DOMXPath
	 */
	protected function getXPath()
	{
		if ($this->xpath === null)
		{
			$this->xpath = new \DOMXPath($this->getXml());
		}

		return $this->xpath;
	}

	/**
	 * @return self
	 */
	protected function parseDuplicateList()
	{
		$this->getConsole()->write('Reading duplicates: ');

		$elements = $this->getXPath()->evaluate('//reference/internal-file');

		$progressBar = new ProgressBar(new Console([
			'finishAction' => Console::FINISH_ACTION_CLEAR_LINE
		]), 0, $elements->length);
		$progressBar->update(0);

		/* @var $element \DOMElement */
		foreach ($elements as $element)
		{
			$value = json_decode(htmlspecialchars_decode($element->nodeValue), true);
			if (array_key_exists('duplicateId', $value) === true && array_key_exists('referenceId', $value) === true)
			{
				$this->getData()->createDuplicate($value['referenceId'], $value['duplicateId']);
			}

			$progressBar->next();
		}

		$progressBar->finish();

		$this->getConsole()->writeLine('Done', ColorInterface::LIGHT_GREEN);

		return $this;
	}

	/**
	 * @return self
	 */
	protected function parseTargetList()
	{
		$this->getConsole()->write('Reading content: ');

		$elements = $this->getXPath()->evaluate('//trans-unit/target');

		$progressBar = new ProgressBar(new Console([
			'finishAction' => Console::FINISH_ACTION_CLEAR_LINE
		]), 0, $elements->length);
		$progressBar->update(0);

		/* @var $element \DOMElement */
		foreach ($elements as $element)
		{
			$this->getData()->create($element);
			$progressBar->next();
		}

		$progressBar->finish();

		$this->getConsole()->writeLine('Done', ColorInterface::LIGHT_GREEN);

		return $this;
	}

	/**
	 * @return self
	 */
	protected function updateDatabase()
	{
		$this->getConsole()->write('Updating database: ');

		$progressBar = new ProgressBar(new Console([
			'finishAction' => Console::FINISH_ACTION_CLEAR_LINE
		]), 0, $this->getData()->count());
		$progressBar->update(0);

		/* @var $entry ImportEntry */
		foreach ($this->getData() as $entry)
		{
			// find table
			if ($this->getConfiguration()->getMap()->offsetExists($entry->getTableName()) === false)
			{
				$progressBar->next();
				continue;
			}

			/* @var $table \DasRed\Translation\Db\Extractor\Configuration\Map\FieldCollection */
			$table = $this->getConfiguration()->getMap()->offsetGet($entry->getTableName());

			// find field
			if ($table->offsetExists($entry->getFieldName()) === false)
			{
				$progressBar->next();
				continue;
			}

			/* @var $field MapEntry */
			$field = $table->offsetGet($entry->getFieldName());

			// get text link id form source
			$textLinkId = $this->getTextLinkIdForEntry($entry, $field);
			if ($textLinkId === false)
			{
				$progressBar->next();
				continue;
			}

			// update now the text content
			$this->updateTextContentEntry($entry, $textLinkId);

			$progressBar->next();
		}

		$progressBar->finish();

		$this->getConsole()->writeLine('Done', ColorInterface::LIGHT_GREEN);

		return $this;
	}

	/**
	 *
	 * @param ImportEntry $entry
	 * @param int $textLinkId
	 * @return self
	 */
	protected function updateTextContentEntry(ImportEntry $entry, $textLinkId)
	{
		$primaryField = $this->getConfiguration()->getTableContent()->getPrimaryField();
		$linkField = $this->getConfiguration()->getTableContent()->getLinkField();
		$localeField = $this->getConfiguration()->getTableContent()->getLocaleField();
		$contentField = $this->getConfiguration()->getTableContent()->getContentField();

		$primaryField->setValue(null);
		$linkField->setValue($textLinkId);
		$localeField->setValue($this->getConnection()->quote($entry->getLocale()));
		$contentField->setValue($this->getConnection()->quote($entry->getContent()));

		// find text content entry
		$contentId = $this->getConnection()->query('
			SELECT	`' . $primaryField->getName() . '`
			FROM	`' . $this->getConfiguration()->getTableContent()->getName() . '`
			WHERE	`' . $linkField->getName() . '`	= ' . $textLinkId . '	AND
					`' . $localeField->getName() . '`	= ' . $this->getConnection()->quote($entry->getLocale()) . '
		')->fetchColumn();

		$primaryField->setValue($contentId !== false ? $contentId : null);

		$sql = $this->getConfiguration()->getTableContent()->getSQLStore();
		$this->getConnection()->exec($sql);

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