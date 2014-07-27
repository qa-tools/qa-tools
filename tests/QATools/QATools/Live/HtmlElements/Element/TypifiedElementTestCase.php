<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\Live\HtmlElements\Element;


use Behat\Mink\Mink;
use Behat\Mink\Session;
use QATools\QATools\HtmlElements\Element\AbstractTypifiedElement;
use QATools\QATools\PageObject\Element\WebElement;
use QATools\QATools\PageObject\PageFactory;
use tests\QATools\QATools\Live\AbstractLiveTestCase;

class TypifiedElementTestCase extends AbstractLiveTestCase
{

	/**
	 * Session manager.
	 *
	 * @var Mink
	 */
	protected static $mink;

	/**
	 * Session.
	 *
	 * @var Session
	 */
	protected $session;

	/**
	 * Element class name.
	 *
	 * @var string
	 */
	protected $elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\AbstractTypifiedElement';

	protected function setUp()
	{
		$this->pageFactoryClass = '\\QATools\\QATools\\HtmlElements\\TypifiedPageFactory';
		parent::setUp();
	}

	/**
	 * Creates element.
	 *
	 * @param array $selector Selector.
	 *
	 * @return AbstractTypifiedElement
	 */
	protected function createElement(array $selector)
	{
		$web_element = new WebElement($selector, $this->session);

		return new $this->elementClass($web_element, $this->pageFactory);
	}

}
