<?php
namespace DasRed\Translation\Db\Extractor;

class Collection extends \ArrayObject
{

	/**
	 * Applies the given predicate p to all elements of this collection,
	 * returning false to abort iteration in the callback
	 *
	 * @param Closure $p
	 *        	The predicate.
	 * @return self
	 */
	public function each(\Closure $p)
	{
		$index = 0;
		foreach ($this as $key => $element)
		{
			if ($p($element, $key, $index) === false)
			{
				return $this;
			}
			$index++;
		}

		return $this;
	}

	/**
	 *
	 * @param \Closure $p
	 * @return mixed
	 */
	public function find(\Closure $p)
	{
		$index = 0;
		foreach ($this as $key => $element)
		{
			if ($p($element, $key, $index) === true)
			{
				return $element;
			}
			$index++;
		}

		return null;
	}
}