<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\HtmlElements\Element;


use Behat\Mink\Selector\Xpath\Escaper;
use Mockery as m;
use QATools\QATools\HtmlElements\Element\AbstractTypifiedElement;
use QATools\QATools\PageObject\Element\WebElement;
use tests\QATools\QATools\TestCase;

class AbstractTypifiedElementTest extends TestCase
{

	const WEB_ELEMENT_CLASS = '\\QATools\\QATools\\PageObject\\Element\\WebElement';

	/**
	 * Element class.
	 *
	 * @var string
	 */
	protected $elementClass;

	/**
	 * Web Element.
	 *
	 * @var WebElement
	 */
	protected $webElement;

	/**
	 * Typified element.
	 *
	 * @var AbstractTypifiedElement
	 */
	protected $typifiedElement;

	/**
	 * List of tests where not to mock getTagName in setUp.
	 *
	 * @var array List of tests.
	 */
	protected $ignoreExpectTypifiedNodeCheck = array();

	/**
	 * Expected tag for web element.
	 *
	 * @var string
	 */
	protected $expectedTagName = 'input';

	/**
	 * List of expected attributes.
	 *
	 * @var array
	 */
	protected $expectedAttributes = array();

	/**
	 * Mocked XPATH escaper.
	 *
	 * @var Escaper
	 */
	protected $escaper;

	protected function setUp()
	{
		parent::setUp();

		$this->escaper = m::mock('Behat\\Mink\\Selector\\Xpath\\Escaper');

		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\tests\\QATools\\QATools\\HtmlElements\\Fixture\\Element\\TypifiedElementChild';
		}

		$this->webElement = m::mock(self::WEB_ELEMENT_CLASS);
		$this->webElement->shouldReceive('getSession')->withNoArgs()->andReturn($this->session);
		$this->webElement->shouldReceive('getXpathEscaper')->withNoArgs()->andReturn($this->escaper);

		if ( !in_array($this->getName(false), $this->ignoreExpectTypifiedNodeCheck) ) {
			$this->expectWebElementGetTagName($this->expectedTagName);
			$this->expectDriverGetTagName($this->expectedTagName);
			$this->expectWebElementGetAttribute($this->expectedAttributes);
			$this->expectDriverGetAttribute($this->expectedAttributes);

			$this->setUpBeforeCreateElement();

			$this->typifiedElement = $this->createElement();
		}
		else {
			$this->setUpBeforeCreateElement();
		}
	}

	/**
	 * Occurs before element creation in setUp.
	 *
	 * @return void
	 */
	protected function setUpBeforeCreateElement()
	{

	}

	public function testConstructor()
	{
		$this->assertSame($this->webElement, $this->typifiedElement->getWrappedElement());
	}

	public function testFromNodeElement()
	{
		$node_element = $this->createNodeElement();

		/* @var $element_class AbstractTypifiedElement */
		$element_class = $this->elementClass;
		$element = $element_class::fromNodeElement($node_element, $this->pageFactory);

		$this->assertInstanceOf($element_class, $element);
		$this->assertInstanceOf(self::WEB_ELEMENT_CLASS, $element->getWrappedElement());
		$this->assertEquals($node_element->getXpath(), $element->getXpath());
	}

	public function testSetName()
	{
		$expected = 'OK';
		$this->assertSame($this->typifiedElement, $this->typifiedElement->setName($expected));
		$this->assertEquals($expected, $this->typifiedElement->getName());
	}

	/**
	 * @group legacy
	 */
	public function testGetSession()
	{
		$this->assertSame($this->session, $this->typifiedElement->getSession());
	}

	/**
	 * @dataProvider simpleMethodDataProvider
	 */
	public function testSimpleMethod($method_name, $expected)
	{
		$this->webElement->shouldReceive($method_name)->once()->andReturn($expected);

		$this->assertSame($expected, $this->typifiedElement->$method_name());
	}

	public function simpleMethodDataProvider()
	{
		return array(
			array('isVisible', true),
			array('isValid', true),
			array('getXpath', 'XPATH'),
		);
	}

	public function testGetXpathEscaper()
	{
		$this->assertInstanceOf('\\Behat\\Mink\\Selector\\Xpath\\Escaper', $this->typifiedElement->getXpathEscaper());
	}

	public function testAttribute()
	{
		$expected = 'B';
		$this->webElement->shouldReceive('hasAttribute')->with('A')->once()->andReturn($expected);
		$this->webElement->shouldReceive('getAttribute')->with('A')->once()->andReturn($expected);

		$this->assertSame($expected, $this->typifiedElement->hasAttribute('A'));
		$this->assertSame($expected, $this->typifiedElement->getAttribute('A'));
	}

	public function testToString()
	{
		$element = $this->createElement();
		$this->webElement->shouldReceive('getXpath')->andReturn('XPATH');

		$expected = 'element (class: ' . get_class($element) . '; xpath: XPATH)';
		$this->assertEquals($expected, (string)$element);
	}

	/**
	 * Create element.
	 *
	 * @return AbstractTypifiedElement
	 */
	protected function createElement()
	{
		return new $this->elementClass($this->webElement, $this->pageFactory);
	}

	/**
	 * Mocks getTagName in the web element.
	 *
	 * @param string $tag_name Returned tag name.
	 *
	 * @return void
	 */
	protected function expectWebElementGetTagName($tag_name)
	{
		$this->webElement->shouldReceive('getTagName')->withNoArgs()->andReturn($tag_name)->byDefault();
	}

	/**
	 * Mocks getAttribute in the web element.
	 *
	 * @param array $attributes Mocked attributes.
	 *
	 * @return void
	 */
	protected function expectWebElementGetAttribute(array $attributes)
	{
		foreach ( $attributes as $attribute => $value ) {
			$this->webElement->shouldReceive('getAttribute')->with($attribute)->andReturn($value)->byDefault();
		}
	}

	/**
	 * Mocks element.
	 *
	 * @param array $methods Methods to mock.
	 *
	 * @return AbstractTypifiedElement
	 */
	protected function mockElement(array $methods = array())
	{
		$method_string = $methods ? '[' . implode(',', $methods) . ']' : '';

		return m::mock($this->elementClass . $method_string, array($this->webElement, $this->pageFactory));
	}

}
