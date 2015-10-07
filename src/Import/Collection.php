<?php
namespace DasRed\Translation\Db\Extractor\Import;

use DasRed\Translation\Db\Extractor\Collection\Object;

class Collection extends Object
{

	/**
	 *
	 * @param \DOMElement $targetElement
	 * @return Entry
	 */
	public function create(\DOMElement $targetElement)
	{
		$parentNode = $targetElement->parentNode;

		// validate
		if (($parentNode instanceof \DOMElement) === false)
		{
			throw new \InvalidArgumentException('$targetElement must have a parent node!');
		}

		// validate
		if ($parentNode->hasAttribute('id') === false)
		{
			throw new \InvalidArgumentException('Parent node of $targetElement must have the attribute "id"!');
		}

		// validate
		if ($targetElement->hasAttribute('xml:lang') === false)
		{
			throw new \InvalidArgumentException('$targetElement must have the attribute "xml:lang"!');
		}

		// create
		$entry = new Entry($parentNode->getAttribute('id'), $targetElement->getAttribute('xml:lang'), htmlspecialchars_decode($targetElement->nodeValue));
		$this->append($entry);

		return $entry;
	}

	/**
	 *
	 * @param string $idLevel3Source
	 * @param string  $idLevel3Destination
	 * @return Entry|null
	 */
	public function createDuplicate($idLevel3Source, $idLevel3Destination)
	{
		if ($this->offsetExists($idLevel3Source) === false)
		{
			return null;
		}

		/* @var $entrySource Entry */
		$entrySource = $this->offsetGet($idLevel3Source);
		$entryDestination = new Entry($idLevel3Destination, $entrySource->getLocale(), $entrySource->getContent());

		$this->append($entryDestination);

		return $entryDestination;
	}

	/**
	 *
	 * @param Entry $value
	 * @throws \InvalidArgumentException
	 * @return self
	 */
	protected function validate($value)
	{
		if (($value instanceof Entry) === false)
		{
			throw new \InvalidArgumentException('$value must be an instance of ' . Entry::class . '!');
		}

		return parent::validate($value);
	}


}