<?php
namespace DasRed\Translation\Db\Extractor\Configuration\Import\Field;

use DasRed\Translation\Db\Extractor\Configuration\Import\Field;

class Content extends Field
{

	/**
	 * @var boolean
	 */
	protected $content = false;

	/**
	 * @var boolean
	 */
	protected $link = false;

	/**
	 * @var boolean
	 */
	protected $locale = false;

	/**
	 * @return boolean
	 */
	public function isContent()
	{
		return $this->content;
	}

	/**
	 * @return boolean
	 */
	public function isLink()
	{
		return $this->link;
	}

	/**
	 * @return boolean
	 */
	public function isLocale()
	{
		return $this->locale;
	}

	/**
	 * @param boolean $content
	 * @return self
	 */
	public function setContent($content)
	{
		$this->content = (bool)$content;

		return $this;
	}

	/**
	 * @param boolean $link
	 * @return self
	 */
	public function setLink($link)
	{
		$this->link = (bool)$link;

		return $this;
	}

	/**
	 * @param boolean $locale
	 * @return self
	 */
	public function setLocale($locale)
	{
		$this->locale = (bool)$locale;

		return $this;
	}
}
