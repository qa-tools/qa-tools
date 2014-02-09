<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Element;

use Behat\Mink\Element\NodeElement;
use aik099\QATools\PageObject\How;
use aik099\QATools\PageObject\IPageFactory;

/**
 * The base class to be used for making blocks of elements.
 *
 * To make a class that will represent a block of elements (e.g. web form) create a descendant of this class.
 */
abstract class HtmlElement extends WebElement implements IHtmlElement
{

	/**
	 * Initializes html element.
	 *
	 * @param array        $selenium_selector Element selector.
	 * @param IPageFactory $page_factory      Page factory.
	 */
	public function __construct(array $selenium_selector, IPageFactory $page_factory)
	{
		parent::__construct($selenium_selector, $page_factory->getSession());

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
		$selenium_selector = array(How::XPATH => $node_element->getXpath());

		return new static($selenium_selector, $page_factory);
	}

}
