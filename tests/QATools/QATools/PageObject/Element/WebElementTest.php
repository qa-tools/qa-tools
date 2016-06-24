<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Element;


use Behat\Mink\Element\NodeElement;
use Mockery as m;
use QATools\QATools\PageObject\Element\WebElement;
use tests\QATools\QATools\TestCase;

class WebElementTest extends TestCase
{

	/**
	 * Element class.
	 *
	 * @var string
	 */
	protected $elementClass = '\\QATools\\QATools\\PageObject\\Element\\WebElement';

	public function testConstructor()
	{
		$element = $this->createElement();

		$this->assertEquals('XPATH', $element->getXpath());
		$this->assertSame($this->session, $element->getSession());
	}

	public function testFromNodeElement()
	{
		/* @var $element_class WebElement */
		$element_class = $this->elementClass;

		$node_element = $this->createNodeElement();
		$element = $element_class::fromNodeElement($node_element, $this->pageFactory);
		/* @var $element WebElement */

		$this->assertInstanceOf($element_class, $element);
		$this->assertEquals($node_element->getXpath(), $element->getXpath());
	}

	public function testToString()
	{
		$element = $this->createElement();

		$expected = 'element (class: ' . get_class($element) . '; xpath: XPATH)';
		$this->assertEquals($expected, (string)$element);
	}

	/**
	 * @medium
	 */
	public function testWaitFor()
	{
		$element = $this->createElement();

		$start = microtime(true);

		$element->waitFor(1, function () {
			return false;
		});

		$this->assertGreaterThanOrEqual(1, microtime(true) - $start);
	}

	public function testGetXpathEscaper()
	{
		$element = $this->createElement();

		$this->assertInstanceOf('\\Behat\\Mink\\Selector\\Xpath\\Escaper', $element->getXpathEscaper());
	}

	/**
	 * Create element.
	 *
	 * @return WebElement
	 */
	protected function createElement()
	{
		return new $this->elementClass(new NodeElement('XPATH', $this->session), $this->pageFactory);
	}

}
