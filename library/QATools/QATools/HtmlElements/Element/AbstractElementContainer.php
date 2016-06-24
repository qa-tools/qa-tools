<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\HtmlElements\Element;


use QATools\QATools\HtmlElements\Exception\TypifiedElementException;
use Behat\Mink\Element\NodeElement;
use QATools\QATools\PageObject\Element\IElementContainer;
use QATools\QATools\PageObject\Element\WebElement;
use QATools\QATools\PageObject\IPageFactory;

/**
 * The base class to be used for making blocks of elements on top of a typified element.
 *
 * To make a class that will represent a block of elements (e.g. web form) create a descendant of this class.
 */
abstract class AbstractElementContainer extends AbstractTypifiedElement implements IElementContainer
{

	/**
	 * Specifies wrapped WebElement.
	 *
	 * @param WebElement   $wrapped_element Element to be wrapped.
	 * @param IPageFactory $page_factory    Page factory.
	 */
	public function __construct(WebElement $wrapped_element, IPageFactory $page_factory)
	{
		parent::__construct($wrapped_element, $page_factory);

		$page_factory->initElementContainer($this);
		$page_factory->initElements($this, $page_factory->createDecorator($this));
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
		$wrapped_element = WebElement::fromNodeElement($node_element, $page_factory);

		return new static($wrapped_element, $page_factory);
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
		return $this->getWrappedElement()->findAll($selector, $locator);
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
		return $this->getWrappedElement()->find($selector, $locator);
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
		$container = $this;
		$wrapped_callback = function () use ($container, $callback) {
			return call_user_func($callback, $container);
		};

		return $this->getWrappedElement()->waitFor($timeout, $wrapped_callback);
	}

}
