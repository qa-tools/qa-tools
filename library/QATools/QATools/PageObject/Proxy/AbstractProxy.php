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


use QATools\QATools\PageObject\Element\AbstractElementCollection;
use QATools\QATools\PageObject\ElementLocator\IElementLocator;
use QATools\QATools\PageObject\Exception\ElementNotFoundException;
use QATools\QATools\PageObject\Exception\ElementException;
use QATools\QATools\PageObject\IPageFactory;
use Behat\Mink\Element\NodeElement;

/**
 * Class for lazy-proxy creation to ensure, that elements are really accessed only at moment, when user needs them.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 *
 * @link http://bit.ly/qa-tools-page-factory-lazy-initialization
 */
abstract class AbstractProxy extends AbstractElementCollection implements IProxy
{

	/**
	 * Class name to proxy.
	 *
	 * @var string
	 */
	protected $className = '';

	/**
	 * Object to proxy.
	 *
	 * @var mixed
	 */
	protected $object;

	/**
	 * Locator, that can be used to find associated elements on a page.
	 *
	 * @var IElementLocator
	 */
	protected $locator;

	/**
	 * Determines if a locator was used to locate the elements.
	 *
	 * @var boolean
	 */
	protected $locatorUsed = false;

	/**
	 * Page Factory, that allows to create more elements on demand.
	 *
	 * @var IPageFactory
	 */
	protected $pageFactory;

	/**
	 * Initializes proxy for the element.
	 *
	 * @param IElementLocator $locator      Element selector.
	 * @param IPageFactory    $page_factory Page factory.
	 */
	public function __construct(IElementLocator $locator, IPageFactory $page_factory = null)
	{
		$this->locator = $locator;
		$this->pageFactory = $page_factory;

		parent::__construct();
	}

	/**
	 * Sets class name, used inside the proxy.
	 *
	 * @param string $class_name Class name to proxy.
	 *
	 * @return self
	 */
	public function setClassName($class_name)
	{
		$this->className = $class_name;

		return $this;
	}

	/**
	 * Proxies read access for properties to the sub-object.
	 *
	 * @param string $property Property to proxy.
	 *
	 * @return mixed
	 * @throws ElementException When sub-object doesn't have a specific property.
	 */
	public function __get($property)
	{
		$sub_object = $this->getObject();

		if ( !property_exists($sub_object, $property) ) {
			if ( method_exists($sub_object, '__get') ) {
				try {
					// Reading of dynamic property in proxied object was successful.
					return $sub_object->$property;
				}
				catch ( \Exception $e ) {
					$trace = $e->getTrace();

					// Reading of dynamic property in proxied object failed (outside of "__get" method of that object).
					if ( $trace[0]['function'] !== '__get' ) {
						throw $e;
					}
				}
			}

			$message = sprintf('"%s" property is not available on the %s', $property, get_class($sub_object));
			throw new ElementException($message, ElementException::TYPE_UNKNOWN_PROPERTY);
		}

		return $sub_object->$property;
	}

	/**
	 * Proxies write access for properties to the sub-object.
	 *
	 * @param string $property Property to proxy.
	 * @param mixed  $value    Property value.
	 *
	 * @return void
	 * @throws ElementException When sub-object doesn't have a specific property.
	 */
	public function __set($property, $value)
	{
		$sub_object = $this->getObject();

		if ( !property_exists($sub_object, $property) ) {
			if ( method_exists($sub_object, '__set') ) {
				try {
					// Writing of dynamic method in proxied object was successful.
					$sub_object->$property = $value;

					return;
				}
				catch ( \Exception $e ) {
					$trace = $e->getTrace();

					// Writing of dynamic property in proxied object failed (outside of "__set" method of that object).
					if ( $trace[0]['function'] !== '__set' ) {
						throw $e;
					}
				}
			}

			$message = sprintf('"%s" property is not available on the %s', $property, get_class($sub_object));
			throw new ElementException($message, ElementException::TYPE_UNKNOWN_PROPERTY);
		}

		$sub_object->$property = $value;
	}

	/**
	 * Proxies all methods to sub-object.
	 *
	 * @param string $method    Method to proxy.
	 * @param array  $arguments Method arguments.
	 *
	 * @return mixed
	 * @throws ElementException When sub-object doesn't have a specified method.
	 */
	public function __call($method, array $arguments)
	{
		$sub_object = $this->getObject();

		if ( !method_exists($sub_object, $method) ) {
			if ( method_exists($sub_object, '__call') ) {
				try {
					// Call to dynamic method in proxied object was successful.
					return call_user_func_array(array($sub_object, $method), $arguments);
				}
				catch ( \Exception $e ) {
					$trace = $e->getTrace();

					// Call to dynamic method in proxied object failed (outside of "__call" method of that object).
					if ( $trace[0]['function'] !== '__call' ) {
						throw $e;
					}
				}
			}

			// Dynamic method in proxied object doesn't exist (known upfront or after calling missing method).
			$message = sprintf('"%s" method is not available on the %s', $method, get_class($sub_object));
			throw new ElementException($message, ElementException::TYPE_UNKNOWN_METHOD);
		}

		return call_user_func_array(array($sub_object, $method), $arguments);
	}

	/**
	 * Locates element using the locator.
	 *
	 * @return NodeElement
	 */
	protected function locateElement()
	{
		$elements = $this->locateElements();

		return $elements[0];
	}

	/**
	 * Locates elements using the locator.
	 *
	 * @return NodeElement[]
	 * @throws ElementNotFoundException When element wasn't found on the page.
	 */
	protected function locateElements()
	{
		$elements = $this->locator->findAll();

		if ( empty($elements) ) {
			throw new ElementNotFoundException('No elements found by selector: ' . (string)$this->locator);
		}

		return $elements;
	}

	/**
	 * Offset to set.
	 *
	 * @param mixed $index  The offset to assign the value to.
	 * @param mixed $newval The value to set.
	 *
	 * @return void
	 */
	public function offsetSet($index, $newval)
	{
		$this->locateObject();

		parent::offsetSet($index, $newval);
	}

	/**
	 * Whether a offset exists.
	 *
	 * @param mixed $index An offset to check for.
	 *
	 * @return boolean
	 */
	public function offsetExists($index)
	{
		$this->locateObject();

		return parent::offsetExists($index);
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
		$this->locateObject();

		parent::offsetUnset($index);
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
		$this->locateObject();

		return parent::offsetGet($index);
	}

	/**
	 * Count elements of an object.
	 *
	 * @return integer
	 */
	public function count()
	{
		$this->locateObject();

		return parent::count();
	}

	/**
	 * Returns the array iterator.
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		$this->locateObject();

		return parent::getIterator();
	}

	/**
	 * Locates object inside proxy.
	 *
	 * @return mixed
	 */
	protected abstract function locateObject();

	/**
	 * Returns class instance, that was placed inside a proxy.
	 *
	 * @return mixed
	 */
	public function getObject()
	{
		$this->locateObject();

		return $this->getIterator()->current();
	}

}
