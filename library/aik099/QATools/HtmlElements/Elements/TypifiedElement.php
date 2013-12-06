<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\HtmlElements\Elements;


use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Selector\SelectorsHandler;
use Behat\Mink\Session;
use aik099\QATools\PageObject\Elements\IWebElement;
use aik099\QATools\PageObject\Elements\WebElement;
use aik099\QATools\PageObject\ISearchContext;

/**
 * The base class to be used for making classes representing typified elements (i.e web page controls such as
 * text inputs, buttons or more complex elements).
 *
 * @method \Mockery\Expectation shouldReceive
 */
abstract class TypifiedElement implements IWebElement, INamed
{

	/**
	 * Name of the element.
	 *
	 * @var string
	 */
	private $_name;

	/**
	 * Wrapped element.
	 *
	 * @var WebElement
	 */
	private $_wrappedElement;

	/**
	 * Specifies wrapped WebElement.
	 *
	 * @param IWebElement $wrapped_element Element to be wrapped.
	 */
	public function __construct(IWebElement $wrapped_element)
	{
		$this->_wrappedElement = $wrapped_element;
	}

	/**
	 * Creates TypifiedElement instance based on existing NodeElement instance.
	 *
	 * @param NodeElement $node_element Node element.
	 *
	 * @return TypifiedElement
	 */
	public static function fromNodeElement(NodeElement $node_element)
	{
		$wrapped_element = WebElement::fromNodeElement($node_element);

		return new static($wrapped_element);
	}

	/**
	 * Returns wrapped element.
	 *
	 * @return WebElement
	 */
	public function getWrappedElement()
	{
		return $this->_wrappedElement;
	}

	/**
	 * Sets a name of an element.
	 *
	 * This method is used by initialization mechanism and is not intended to be used directly.
	 *
	 * @param string $name Name to set.
	 *
	 * @return self
	 */
	public function setName($name)
	{
		$this->_name = $name;

		return $this;
	}

	/**
	 * Returns name of the entity.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * Finds all elements with specified selector.
	 *
	 * @param string $selector Selector engine name.
	 * @param string $locator  Selector locator.
	 *
	 * @return NodeElement[]
	 */
	public function findAll($selector, $locator)
	{
		return $this->getWrappedElement()->findAll($selector, $locator);
	}

	/**
	 * Finds first element with specified selector.
	 *
	 * @param string $selector Selector engine name.
	 * @param string $locator  Selector locator.
	 *
	 * @return NodeElement|null
	 */
	public function find($selector, $locator)
	{
		return $this->getWrappedElement()->find($selector, $locator);
	}

	/**
	 * Returns wrapped element session.
	 *
	 * @return Session
	 */
	public function getSession()
	{
		return $this->getWrappedElement()->getSession();
	}

	/**
	 * Checks whether current node is visible on page.
	 *
	 * @return boolean
	 */
	public function isVisible()
	{
		return $this->getWrappedElement()->isVisible();
	}

	/**
	 * Checks whether element has attribute with specified name.
	 *
	 * @param string $name Attribute name.
	 *
	 * @return boolean
	 */
	public function hasAttribute($name)
	{
		return $this->getWrappedElement()->hasAttribute($name);
	}

	/**
	 * Returns specified attribute value.
	 *
	 * @param string $name Attribute name.
	 *
	 * @return mixed|null
	 */
	public function getAttribute($name)
	{
		return $this->getWrappedElement()->getAttribute($name);
	}

	/**
	 * Returns XPath for handled element.
	 *
	 * @return string
	 */
	public function getXpath()
	{
		return $this->getWrappedElement()->getXpath();
	}

	/**
	 * Returns current node tag name.
	 *
	 * @return string
	 */
	public function getTagName()
	{
		return $this->getWrappedElement()->getTagName();
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
		$this->getWrappedElement()->setContainer($container);

		return $this;
	}

	/**
	 * Returns container, where element is located.
	 *
	 * @return ISearchContext
	 */
	public function getContainer()
	{
		return $this->getWrappedElement()->getContainer();
	}

	/**
	 * Returns selectors handler.
	 *
	 * @return SelectorsHandler
	 */
	protected function getSelectorsHandler()
	{
		return $this->getSession()->getSelectorsHandler();
	}

	/**
	 * Checks, that Selenium driver is used.
	 *
	 * @return boolean
	 */
	protected function isSeleniumDriver()
	{
		return $this->getSession()->getDriver() instanceof Selenium2Driver;
	}

	/**
	 * Returns string representation of element.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return 'element (class: ' . get_class($this) . '; xpath: ' . $this->getXpath() . ')';
	}

}
