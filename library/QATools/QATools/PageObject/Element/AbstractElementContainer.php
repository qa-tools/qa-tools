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


use QATools\QATools\PageObject\Exception\ElementException;
use QATools\QATools\PageObject\How;
use QATools\QATools\PageObject\IPageFactory;
use Behat\Mink\Element\NodeElement;

/**
 * The base class to be used for making blocks of elements.
 *
 * To make a class that will represent a block of elements (e.g. web form) create a descendant of this class.
 */
abstract class AbstractElementContainer extends WebElement implements IElementContainer
{

	/**
	 * Initializes html element.
	 *
	 * @param NodeElement  $wrapped_element Wrapped element.
	 * @param IPageFactory $page_factory    Page factory.
	 */
	public function __construct(NodeElement $wrapped_element, IPageFactory $page_factory)
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
		return new static($node_element, $page_factory);
	}

}
