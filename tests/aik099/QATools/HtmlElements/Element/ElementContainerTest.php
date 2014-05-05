<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElements\Element;


use aik099\QATools\HtmlElements\Element\ElementContainer;
use Mockery as m;

class ElementContainerTest extends TypifiedElementTest
{

	/**
	 * Prepares mocks for object creation.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\tests\\aik099\\QATools\\HtmlElements\\Fixture\\Element\\ElementContainerChild';
		}

		parent::setUp();
	}

	/**
	 * Occurs before element creation in setUp.
	 *
	 * @return void
	 */
	protected function setUpBeforeCreateElement()
	{
		parent::setUpBeforeCreateElement();

		$two_times_tests = array('testFromNodeElement', 'testToString', 'testFill', 'testGetPageFactory');
		$times = in_array($this->getName(), $two_times_tests) ? 2 : 1;

		$this->pageFactory->shouldReceive('initElementContainer')->times($times)->andReturn($this->pageFactory);
		$this->pageFactory->shouldReceive('initElements')->times($times)->andReturn($this->pageFactory);

		$decorator = m::mock('\\aik099\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->times($times)->andReturn($decorator);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testFindAll()
	{
		$expected = 'C';
		$this->webElement->shouldReceive('findAll')->with('A', 'B')->once()->andReturn($expected);
		$this->webElement->shouldReceive('find')->with('A', 'B')->once()->andReturn($expected);

		$this->assertSame($expected, $this->typifiedElement->findAll('A', 'B'));
		$this->assertSame($expected, $this->typifiedElement->find('A', 'B'));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetPageFactory()
	{
		$element = $this->createElement();
		$method = new \ReflectionMethod(get_class($element), 'getPageFactory');
		$method->setAccessible(true);

		$this->assertSame($this->pageFactory, $method->invoke($element));
	}

	/**
	 * Create element.
	 *
	 * @return ElementContainer
	 */
	protected function createElement()
	{
		return new $this->elementClass($this->webElement, $this->pageFactory);
	}

}
