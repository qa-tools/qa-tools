<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject;


use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use aik099\QATools\PageObject\Element\IWebElement;
use aik099\QATools\PageObject\Element\WebElement;
use aik099\QATools\PageObject\Exception\ElementNotFoundException;
use aik099\QATools\PageObject\Exception\ElementException;
use Behat\Mink\Element\NodeElement;

/**
 * Class for lazy-proxy creation to ensure, that WebElements are really accessed only at moment, when user needs them.
 *
 * @method \Mockery\Expectation shouldReceive
 *
 * @link http://bit.ly/14TbcR9
 */
class WebElementProxy implements IProxy
{

	/**
	 * Class name to proxy (must implement IWebElement).
	 *
	 * @var string
	 */
	protected $className = '\\aik099\\QATools\\PageObject\\Element\\WebElement';

	/**
	 * WebElement object to proxy.
	 *
	 * @var WebElement
	 */
	protected $object;

	/**
	 * Locator, that can be used to find associated elements on a page.
	 *
	 * @var IElementLocator
	 */
	protected $locator;

	/**
	 * Page Factory, used to create an WebElementProxy.
	 *
	 * @var IPageFactory
	 */
	protected $pageFactory;

	/**
	 * Container, where element is located.
	 *
	 * @var ISearchContext
	 */
	protected $container;

	/**
	 * Initializes proxy for WebElement.
	 *
	 * @param IElementLocator $locator      Element selector.
	 * @param IPageFactory    $page_factory Page factory.
	 */
	public function __construct(IElementLocator $locator, IPageFactory $page_factory = null)
	{
		$this->locator = $locator;
		$this->pageFactory = $page_factory;
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
	 * Sets container, where element is located.
	 *
	 * @param ISearchContext|null $container Element container.
	 *
	 * @return self
	 */
	public function setContainer(ISearchContext $container = null)
	{
		$this->container = $container;

		return $this;
	}

	/**
	 * Returns container, where element is located.
	 *
	 * @return ISearchContext
	 */
	public function getContainer()
	{
		return $this->container;
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
			$message = sprintf('"%s" method is not available on the %s', $method, get_class($sub_object));

			throw new ElementException($message, ElementException::TYPE_UNKNOWN_METHOD);
		}

		return call_user_func_array(array($sub_object, $method), $arguments);
	}

	/**
	 * Returns class instance, that was placed inside a proxy.
	 *
	 * @return WebElement
	 */
	public function getObject()
	{
		if ( !is_object($this->object) ) {
			$element = $this->locateElement();

			$this->object = call_user_func(array($this->className, 'fromNodeElement'), $element, $this->pageFactory);
			$this->object->setContainer($this->getContainer());
		}

		return $this->object;
	}

	/**
	 * Locates element using the locator.
	 *
	 * @return NodeElement|null
	 * @throws ElementNotFoundException When element wasn't found on the page.
	 */
	protected function locateElement()
	{
		$element = $this->locator->find();

		if ( !is_object($element) ) {
			throw new ElementNotFoundException('Element not found by selector: ' . (string)$this->locator);
		}

		return $element;
	}

}
