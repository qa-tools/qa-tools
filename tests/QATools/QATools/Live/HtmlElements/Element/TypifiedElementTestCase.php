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


use Behat\Mink\Element\NodeElement;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use QATools\QATools\HtmlElements\Element\AbstractTypifiedElement;
use QATools\QATools\PageObject\Element\WebElement;
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

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		$this->pageFactoryClass = '\\QATools\\QATools\\HtmlElements\\TypifiedPageFactory';
		parent::setUpTest();
	}

	/**
	 * Creates element.
	 *
	 * @param string $how   How class constant.
	 * @param string $using Using value.
	 *
	 * @return AbstractTypifiedElement
	 */
	protected function createElement($how, $using)
	{
		$xpath = $this->pageFactory->translateToXPath($how, $using);

		$web_element = new WebElement(new NodeElement($xpath, $this->session), $this->pageFactory);

		return new $this->elementClass($web_element, $this->pageFactory);
	}

}
