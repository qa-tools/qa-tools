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
	 * Stores instance of used page factory.
	 *
	 * @var IPageFactory
	 */
	private $_pageFactory;

	/**
	 * Initializes html element.
	 *
	 * @param array        $selenium_selector Element selector.
	 * @param IPageFactory $page_factory      Page factory.
	 */
	public function __construct(array $selenium_selector, IPageFactory $page_factory)
	{
		parent::__construct($selenium_selector, $page_factory->getSession());

		$this->_pageFactory = $page_factory;
		$this->_pageFactory->initElementContainer($this);
		$this->_pageFactory->initElements($this, $this->_pageFactory->createDecorator($this));
	}

	/**
	 * Creates Element instance based on existing NodeElement instance.
	 *
	 * @param NodeElement  $node_element Node element.
	 * @param IPageFactory $page_factory Page factory.
	 *
	 * @return static
	 * @throws ElementException When page factory is missing.
	 */
	public static function fromNodeElement(NodeElement $node_element, IPageFactory $page_factory = null)
	{
		if ( !isset($page_factory) ) {
			throw new ElementException(
				'Page factory is required to create this element',
				ElementException::TYPE_PAGE_FACTORY_REQUIRED
			);
		}

		$selenium_selector = array(How::XPATH => $node_element->getXpath());

		return new static($selenium_selector, $page_factory);
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

}
