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


use Behat\Mink\Element\NodeElement;
use aik099\QATools\PageObject\Elements\IHtmlElement;
use aik099\QATools\PageObject\Elements\IWebElement;
use aik099\QATools\PageObject\Elements\WebElement;
use aik099\QATools\PageObject\IPageFactory;

/**
 * The base class to be used for making blocks of elements on top of a typified element.
 *
 * To make a class that will represent a block of elements (e.g. web form) create a descendant of this class.
 */
abstract class HtmlElement extends TypifiedElement implements IHtmlElement
{

	/**
	 * Specifies wrapped WebElement.
	 *
	 * @param IWebElement  $wrapped_element Element to be wrapped.
	 * @param IPageFactory $page_factory    Page factory.
	 */
	public function __construct(IWebElement $wrapped_element, IPageFactory $page_factory)
	{
		parent::__construct($wrapped_element);

		$page_factory->initHtmlElement($this)->initElements($this, $page_factory->createDecorator($this));
	}

	/**
	 * Creates WebElement instance based on existing NodeElement instance.
	 *
	 * @param NodeElement  $node_element Node element.
	 * @param IPageFactory $page_factory Page factory.
	 *
	 * @return IWebElement
	 */
	public static function fromNodeElement(NodeElement $node_element, IPageFactory $page_factory = null)
	{
		$wrapped_element = WebElement::fromNodeElement($node_element);

		return new static($wrapped_element, $page_factory);
	}

}
