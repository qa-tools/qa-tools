<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\HtmlElements\Element;


use Behat\Mink\Element\NodeElement;
use aik099\QATools\PageObject\Element\IElementContainer;
use aik099\QATools\PageObject\Element\WebElement;
use aik099\QATools\PageObject\IPageFactory;

/**
 * The base class to be used for making blocks of elements on top of a typified element.
 *
 * To make a class that will represent a block of elements (e.g. web form) create a descendant of this class.
 */
abstract class ElementContainer extends TypifiedElement implements IElementContainer
{

	/**
	 * Stores instance of used page factory.
	 *
	 * @var IPageFactory
	 */
	private $_pageFactory;

	/**
	 * Specifies wrapped WebElement.
	 *
	 * @param WebElement   $wrapped_element Element to be wrapped.
	 * @param IPageFactory $page_factory    Page factory.
	 */
	public function __construct(WebElement $wrapped_element, IPageFactory $page_factory)
	{
		parent::__construct($wrapped_element);

		$this->_pageFactory = $page_factory;
		$this->_pageFactory->initElementContainer($this);
		$this->_pageFactory->initElements($this, $page_factory->createDecorator($this));
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
	 * Creates WebElement instance based on existing NodeElement instance.
	 *
	 * @param NodeElement  $node_element Node element.
	 * @param IPageFactory $page_factory Page factory.
	 *
	 * @return static
	 */
	public static function fromNodeElement(NodeElement $node_element, IPageFactory $page_factory = null)
	{
		$wrapped_element = WebElement::fromNodeElement($node_element);

		return new static($wrapped_element, $page_factory);
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

}
