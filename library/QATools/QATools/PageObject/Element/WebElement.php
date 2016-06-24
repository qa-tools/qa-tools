<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject\Element;


use Behat\Mink\Element\ElementInterface;
use Behat\Mink\Selector\Xpath\Escaper;
use QATools\QATools\PageObject\Exception\ElementException;
use QATools\QATools\PageObject\IPageFactory;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;

/**
 * Regular element on a page, that is initialized using Selenium-style selector.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 *
 * @method string getXpath() Returns XPath for handled element.
 * @method NodeElement getParent() Returns parent element to the current one.
 * @method string getTagName() Returns current node tag name.
 * @method string|boolean|array getValue() Returns the value of the form field or option element.
 * @method void setValue($value) Sets the value of the form field.
 * @method boolean hasAttribute($name) Checks whether element has attribute with specified name.
 * @method string|null getAttribute($name) Returns specified attribute value.
 * @method boolean hasClass($className) Checks whether an element has a named CSS class.
 * @method void click() Clicks current node.
 * @method void press() Presses current button.
 * @method void doubleClick() Double-clicks current node.
 * @method void rightClick() Right-clicks current node.
 * @method void check() Checks current node if it's a checkbox field.
 * @method void uncheck() Unchecks current node if it's a checkbox field.
 * @method boolean isChecked() Checks whether current node is checked if it's a checkbox or radio field.
 * @method void selectOption($option, $multiple = false) Selects specified option for select field or specified radio button in the group.
 * @method boolean isSelected() Checks whether current node is selected if it's a option field.
 * @method void attachFile($path) Attach file to current node if it's a file input.
 * @method boolean isVisible() Checks whether current node is visible on page.
 * @method void mouseOver() Simulates a mouse over on the element.
 * @method void dragTo(ElementInterface $destination) Drags current node onto other node.
 * @method void focus() Brings focus to element.
 * @method void blur() Removes focus from element.
 * @method void keyPress($char, $modifier = null) Presses specific keyboard key.
 * @method void keyDown($char, $modifier = null) Pressed down specific keyboard key.
 * @method void keyUp($char, $modifier = null) Pressed up specific keyboard key.
 * @method void submit() Submits the form.
 *
 * @method NodeElement|null findById($id) Finds element by its id.
 * @method boolean hasLink($locator) Checks whether element has a link with specified locator.
 * @method NodeElement|null findLink($locator) Finds link with specified locator.
 * @method void clickLink($locator) Clicks link with specified locator.
 * @method boolean hasButton($locator) Checks whether element has a button (input[type=submit|image|button|reset], button) with specified locator.
 * @method NodeElement|null findButton($locator) Finds button (input[type=submit|image|button|reset], button) with specified locator.
 * @method void pressButton($locator) Presses button (input[type=submit|image|button|reset], button) with specified locator.
 * @method boolean hasField($locator) Checks whether element has a field (input, textarea, select) with specified locator.
 * @method NodeElement|null findField($locator) Finds field (input, textarea, select) with specified locator.
 * @method void fillField($locator, $value) Fills in field (input, textarea, select) with specified locator.
 * @method boolean hasCheckedField($locator) Checks whether element has a checkbox with specified locator, which is checked.
 * @method boolean hasUncheckedField($locator) Checks whether element has a checkbox with specified locator, which is unchecked.
 * @method void checkField($locator) Checks checkbox with specified locator.
 * @method void uncheckField($locator) Unchecks checkbox with specified locator.
 * @method boolean hasSelect($locator) Checks whether element has a select field with specified locator.
 * @method void selectFieldOption($locator, $value, $multiple = false) Selects option from select field with specified locator.
 * @method boolean hasTable($locator) Checks whether element has a table with specified locator.
 * @method void attachFileToField($locator, $path) Attach file to file field with specified locator.
 *
 * @method boolean has($selector, $locator) Checks whether element with specified selector exists inside the current element.
 * @method boolean isValid() Checks if an element still exists in the DOM.
 * @method string getText() Returns element text (inside tag).
 * @method string getHtml() Returns element inner html.
 * @method string getOuterHtml() Returns element outer html.
 */
class WebElement implements IWebElement, INodeElementAware
{

	/**
	 * Wrapped element.
	 *
	 * @var NodeElement
	 */
	private $_wrappedElement;

	/**
	 * Stores instance of used page factory.
	 *
	 * @var IPageFactory
	 */
	private $_pageFactory;

	/**
	 * The XPath escaper.
	 *
	 * @var Escaper
	 */
	private $_xpathEscaper;

	/**
	 * Initializes web element.
	 *
	 * @param NodeElement  $wrapped_element Wrapped element.
	 * @param IPageFactory $page_factory    Page factory.
	 */
	public function __construct(NodeElement $wrapped_element, IPageFactory $page_factory)
	{
		$this->_wrappedElement = $wrapped_element;
		$this->_pageFactory = $page_factory;
		$this->_xpathEscaper = new Escaper();
	}

	/**
	 * Creates Element instance based on existing NodeElement instance.
	 *
	 * @param NodeElement  $node_element Node element.
	 * @param IPageFactory $page_factory Page factory.
	 *
	 * @return static
	 */
	public static function fromNodeElement(NodeElement $node_element, IPageFactory $page_factory)
	{
		return new static($node_element, $page_factory);
	}

	/**
	 * Finds first element with specified selector.
	 *
	 * @param string       $selector Selector engine name.
	 * @param string|array $locator  Selector locator.
	 *
	 * @return NodeElement|null
	 */
	public function find($selector, $locator)
	{
		return $this->_wrappedElement->find($selector, $locator);
	}

	/**
	 * Finds all elements with specified selector.
	 *
	 * @param string       $selector Selector engine name.
	 * @param string|array $locator  Selector locator.
	 *
	 * @return NodeElement[]
	 */
	public function findAll($selector, $locator)
	{
		return $this->_wrappedElement->findAll($selector, $locator);
	}

	/**
	 * Waits for an element(-s) to appear and returns it.
	 *
	 * @param integer  $timeout  Maximal allowed waiting time in seconds.
	 * @param callable $callback Callback, which result is both used as waiting condition and returned.
	 *                           Will receive reference to `this element` as first argument.
	 *
	 * @return mixed
	 */
	public function waitFor($timeout, $callback)
	{
		return $this->_wrappedElement->waitFor($timeout, $callback);
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
		if ( !method_exists($this->_wrappedElement, $method) && !method_exists($this->_wrappedElement, '__call') ) {
			$message = sprintf('"%s" method is not available on the %s', $method, get_class($this->_wrappedElement));

			throw new ElementException($message, ElementException::TYPE_UNKNOWN_METHOD);
		}

		return call_user_func_array(array($this->_wrappedElement, $method), $arguments);
	}

	/**
	 * Returns the XPath escaper.
	 *
	 * @return Escaper
	 */
	public function getXpathEscaper()
	{
		return $this->_xpathEscaper;
	}

	/**
	 * Returns element session.
	 *
	 * @return     Session
	 * @deprecated Accessing the session from the element is deprecated as of 1.2 and will be impossible in 2.0.
	 */
	public function getSession()
	{
		@trigger_error(
			sprintf('The method %s is deprecated as of 1.2 and will be removed in 2.0', __METHOD__),
			E_USER_DEPRECATED
		);

		return $this->_pageFactory->getSession();
	}

	/**
	 * Returns page factory, used during object creation.
	 *
	 * @return IPageFactory
	 */
	protected function getPageFactory()
	{
		return $this->_pageFactory;
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
