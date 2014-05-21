<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\PageObject\Element;


use Mockery as m;
use aik099\QATools\PageObject\Element\WebElement;
use tests\aik099\QATools\TestCase;

class WebElementTest extends TestCase
{

	/**
	 * Element class.
	 *
	 * @var string
	 */
	protected $elementClass = '\\aik099\\QATools\\PageObject\\Element\\WebElement';

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

	public function testSetContainer()
	{
		$element = $this->createElement();
		$container = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');

		$this->assertSame($element, $element->setContainer($container));
		$this->assertSame($container, $element->getContainer());
	}

	public function testGetContainerFallback()
	{
		$expected = 'OK';
		$element = $this->createElement();
		$this->session->shouldReceive('getPage')->once()->andReturn($expected);

		$this->assertEquals($expected, $element->getContainer());
	}

	public function testToString()
	{
		$element = $this->createElement();

		$expected = 'element (class: ' . get_class($element) . '; xpath: XPATH)';
		$this->assertEquals($expected, (string)$element);
	}

	/**
	 * Create element.
	 *
	 * @return WebElement
	 */
	protected function createElement()
	{
		return new $this->elementClass(array('xpath' => 'XPATH'), $this->session);
	}

}
