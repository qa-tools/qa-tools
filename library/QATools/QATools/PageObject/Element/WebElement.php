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


use QATools\QATools\PageObject\IPageFactory;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use QATools\QATools\PageObject\How;

/**
 * Regular element on a page, that is initialized using Selenium-style selector.
 *
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class WebElement extends NodeElement implements IWebElement, INodeElementAware
{

	/**
	 * Selenium selector, used to locate an element at construction time.
	 *
	 * @var array
	 */
	protected $seleniumSelector;

	/**
	 * Initializes web element.
	 *
	 * @param array   $selenium_selector Element selector.
	 * @param Session $session           Session.
	 */
	public function __construct(array $selenium_selector, Session $session)
	{
		$this->seleniumSelector = $selenium_selector;

		parent::__construct($this->seleniumSelectorToXpath($session), $session);
	}

	/**
	 * Creates Element instance based on existing NodeElement instance.
	 *
	 * @param NodeElement  $node_element Node element.
	 * @param IPageFactory $page_factory Page factory.
	 *
	 * @return static
	 */
	public static function fromNodeElement(NodeElement $node_element, IPageFactory $page_factory = null)
	{
		$selenium_selector = array(How::XPATH => $node_element->getXpath());

		return new static($selenium_selector, $node_element->getSession());
	}

	/**
	 * Returns 'xpath' selector, used to locate this WebElement.
	 *
	 * @param Session $session Session.
	 *
	 * @return string
	 */
	protected function seleniumSelectorToXpath(Session $session)
	{
		return $session->getSelectorsHandler()->selectorToXpath('se', $this->seleniumSelector);
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
