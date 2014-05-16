<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Proxy;


use aik099\QATools\PageObject\Element\AbstractElementCollection;
use aik099\QATools\PageObject\Element\IContainerAware;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use aik099\QATools\PageObject\Exception\ElementNotFoundException;
use aik099\QATools\PageObject\Exception\ElementException;
use aik099\QATools\PageObject\Exception\PageFactoryException;
use aik099\QATools\PageObject\IPageFactory;
use aik099\QATools\PageObject\ISearchContext;
use Behat\Mink\Element\NodeElement;

/**
 * Class for lazy-proxy creation to ensure, that elements are really accessed only at moment, when user needs them.
 *
 * @method \Mockery\Expectation shouldReceive
 *
 * @link http://bit.ly/14TbcR9
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
	 * Container, where element is located.
	 *
	 * @var ISearchContext
	 */
	protected $container;

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
	 * Locates element using the locator.
	 *
	 * @return NodeElement|null
	 * @throws ElementNotFoundException When element wasn't found on the page.
	 */
	protected function locateElement()
	{
		$elements = $this->locateElements();

		return count($elements) ? current($elements) : null;
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
	 * Determines if a class to proxy in fact is an element collection.
	 *
	 * @return boolean
	 */
	protected function isElementCollection()
	{
		return is_subclass_of($this->className, 'aik099\\QATools\\PageObject\\Element\\AbstractElementCollection');
	}

	/**
	 * Sets proxy's container into each of given elements.
	 *
	 * @param AbstractElementCollection $elements Elements.
	 *
	 * @return void
	 */
	protected function injectContainer(AbstractElementCollection $elements)
	{
		$container = $this->getContainer();

		/** @var IContainerAware $element */
		foreach ( $elements as $element ) {
			$element->setContainer($container);
		}
	}

}
