<?php
namespace DasRed\Translation\Db\Extractor\Configuration\Import\Table;

use DasRed\Translation\Db\Extractor\Configuration\Import\Table;
use DasRed\Translation\Db\Extractor\Configuration\Import\Field\Content as ContentField;
use DasRed\Translation\Db\Extractor\Configuration\Import\Table\Exception\LinkFieldIsNotDefined;
use DasRed\Translation\Db\Extractor\Configuration\Import\Table\Exception\ContentFieldIsNotDefined;
use DasRed\Translation\Db\Extractor\Configuration\Import\Table\Exception\LocaleFieldIsNotDefined;
use Zend\Config\Config;

class Content extends Table
{

	/**
	 *
	 * @param Config $fieldConfig
	 * @return ContentField
	 */
	public function create(Config $fieldConfig)
	{
		/* @var $field ContentField */
		$field = parent::create($fieldConfig);

		if ($fieldConfig->offsetExists('isLink') === true)
		{
			$field->setLink($fieldConfig->offsetGet('isLink'));
		}

		if ($fieldConfig->offsetExists('isLocale') === true)
		{
			$field->setLocale($fieldConfig->offsetGet('isLocale'));
		}

		if ($fieldConfig->offsetExists('isContent') === true)
		{
			$field->setContent($fieldConfig->offsetGet('isContent'));
		}

		return $field;
	}

	/**
	 *
	 * @param string $name
	 * @return ContentField
	 */
	protected function createFieldInstance($name)
	{
		return new ContentField($this, $name);
	}

	/**
	 *
	 * @return ContentField
	 * @throws ContentFieldIsNotDefined
	 */
	public function getContentField()
	{
		$field = $this->find(function (ContentField $field)
		{
			return $field->isContent();
		});

		if ($field === null)
		{
			throw new ContentFieldIsNotDefined($this);
		}

		return $field;
	}

	/**
	 *
	 * @return ContentField
	 * @throws LinkFieldIsNotDefined
	 */
	public function getLinkField()
	{
		$field = $this->find(function (ContentField $field)
		{
			return $field->isLink();
		});

		if ($field === null)
		{
			throw new LinkFieldIsNotDefined($this);
		}

		return $field;
	}

	/**
	 *
	 * @return ContentField
	 * @throws LocaleFieldIsNotDefined
	 */
	public function getLocaleField()
	{
		$field = $this->find(function (ContentField $field)
		{
			return $field->isLocale();
		});

		if ($field === null)
		{
			throw new LocaleFieldIsNotDefined($this);
		}

		return $field;
	}

	/**
	 *
	 * @param ContentField $value
	 * @throws \InvalidArgumentException
	 * @return self
	 */
	protected function validate($value)
	{
		if (($value instanceof ContentField) === false)
		{
			throw new \InvalidArgumentException('$value must be an instance of ' . ContentField::class . '!');
		}

		return parent::validate($value);
	}
}
