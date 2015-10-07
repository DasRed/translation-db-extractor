<?php
namespace DasRed\Translation\Db\Extractor\Import;

use DasRed\Translation\Db\Extractor\Collection\EntryInterface;

class Entry implements EntryInterface
{

	/**
	 *
	 * @var string
	 */
	protected $content;

	/**
	 *
	 * @var string
	 */
	protected $idLevel3;

	/**
	 *
	 * @var string
	 */
	protected $locale;

	/**
	 *
	 * @param string $idLevel3
	 * @param string $locale
	 * @param string $content
	 */
	public function __construct($idLevel3, $locale, $content)
	{
		$this->setIdLevel3($idLevel3)->setLocale($locale)->setContent($content);
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 *
	 * @return string
	 */
	public function getFieldName()
	{
		$data = explode('.', $this->getIdLevel3());

		array_shift($data);
		return array_shift($data);
	}

	/**
	 *
	 * @return string
	 */
	public function getId()
	{
		$data = explode('.', $this->getIdLevel3());

		array_shift($data);
		array_shift($data);
		return array_shift($data);
	}

	/**
	 * @return string
	 */
	public function getIdLevel3()
	{
		return $this->idLevel3;
	}

	/**
	 * @return string
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * (non-PHPdoc)
	 * @see \DasRed\Translation\Db\Extractor\Collection\EntryInterface::getOffsetKey()
	 */
	public function getOffsetKey()
	{
		return $this->getIdLevel3();
	}

	/**
	 *
	 * @return string
	 */
	public function getTableName()
	{
		$data = explode('.', $this->getIdLevel3());

		return array_shift($data);
	}

	/**
	 * @param string $content
	 * @return self
	 */
	public function setContent($content)
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * @param string $idLevel3
	 * @return self
	 */
	public function setIdLevel3($idLevel3)
	{
		$this->idLevel3 = $idLevel3;

		return $this;
	}

	/**
	 * @param string $locale
	 * @return self
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;

		return $this;
	}
}
