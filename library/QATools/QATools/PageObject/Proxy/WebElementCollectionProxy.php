<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Proxy;


use QATools\QATools\PageObject\Element\WebElementCollection;

/**
 * Class for lazy-proxy creation to ensure, that WebElementCollection are
 * really accessed only at moment, when user needs them.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 *
 * @link http://bit.ly/14TbcR9
 */
class WebElementCollectionProxy extends WebElementProxy
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
	 * Sets proxy's container to the collection.
	 *
	 * @return void
	 */
	protected function injectContainer()
	{
		$this->getObject()->setContainer($this->getContainer());
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

		// NodeElement + TargetElement(setContainer) = Proxy.
		$this->locatorUsed = true;

		$object = call_user_func(
			array($this->className, 'fromNodeElements'), $this->locateElements(), null, $this->pageFactory
		);

		\ArrayObject::offsetSet(null, $object);
		$this->injectContainer();
	}

	/**
	 * Returns class instance, that was placed inside a proxy.
	 *
	 * @return WebElementCollection
	 */
	public function getObject()
	{
		$this->locateObject();

		return \ArrayObject::getIterator()->current();
	}

}
