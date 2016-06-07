<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\HtmlElements\Proxy;


use QATools\QATools\HtmlElements\Element\AbstractTypifiedElementCollection;
use QATools\QATools\HtmlElements\Element\ITypifiedElement;
use QATools\QATools\PageObject\Element\AbstractElementCollection;

/**
 * Class for lazy-proxy creation to ensure, that TypifiedElementCollection are
 * really accessed only at moment, when user needs them.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 *
 * @link http://bit.ly/qa-tools-page-factory-lazy-initialization
 */
class TypifiedElementCollectionProxy extends TypifiedElementProxy
{

	/**
	 * Offset to set.
	 *
	 * @param mixed $index  The offset to assign the value to.
	 * @param mixed $newval The value to set.
	 *
	 * @return void
	 * @throws \InvalidArgumentException When invalid element given.
	 */
	public function offsetSet($index, $newval)
	{
		$this->getObject()->offsetSet($index, $newval);
	}

	/**
	 * Whether an offset exists.
	 *
	 * @param mixed $index An offset to check for.
	 *
	 * @return boolean
	 */
	public function offsetExists($index)
	{
		return $this->getObject()->offsetExists($index);
	}

	/**
	 * Offset to unset.
	 *
	 * @param mixed $index The offset to unset.
	 *
	 * @return void
	 */
	public function offsetUnset($index)
	{
		$this->getObject()->offsetUnset($index);
	}

	/**
	 * Offset to retrieve.
	 *
	 * @param mixed $index The offset to retrieve.
	 *
	 * @return mixed|null
	 */
	public function offsetGet($index)
	{
		return $this->getObject()->offsetGet($index);
	}

	/**
	 * Count elements of an object.
	 *
	 * @return integer
	 */
	public function count()
	{
		return $this->getObject()->count();
	}

	/**
	 * Returns the array iterator.
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return $this->getObject()->getIterator();
	}

	/**
	 * Locates object inside proxy.
	 *
	 * @return void
	 */
	protected function locateObject()
	{
		if ( $this->locatorUsed ) {
			return;
		}

		// NodeElement + TargetElement = Proxy.
		$this->locatorUsed = true;

		/** @var AbstractTypifiedElementCollection $object */
		$object = call_user_func(
			array($this->className, 'fromNodeElements'),
			$this->locateElements(),
			null,
			$this->pageFactory
		);
		$object->setName($this->getName());

		AbstractElementCollection::offsetSet(null, $object);

		$iterator = $this->getIterator();

		/** @var ITypifiedElement $element */
		foreach ( $iterator as $element ) {
			$element->setName($this->getName());
		}
	}

	/**
	 * Returns class instance, that was placed inside a proxy.
	 *
	 * @return AbstractTypifiedElementCollection
	 */
	public function getObject()
	{
		$this->locateObject();

		return \ArrayObject::getIterator()->current();
	}

}
