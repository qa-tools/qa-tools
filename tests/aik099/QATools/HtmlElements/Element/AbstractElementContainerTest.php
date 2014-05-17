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


use aik099\QATools\HtmlElements\Element\AbstractElementContainer;
use Mockery as m;

class AbstractElementContainerTest extends AbstractTypifiedElementTest
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

		$two_times_tests = array('testFromNodeElement', 'testToString', 'testFill', 'testGetPageFactory', 'testWaitFor');
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
	 * Test description.
	 *
	 * @return void
	 */
	public function testWaitFor()
	{
		$web_element = $this->webElement;
		$this->webElement
			->shouldReceive('waitFor')
			->with(5000, m::type('callable'))
			->once()
			->andReturnUsing(function ($timeout, $callback) use ($web_element) {
				return call_user_func($callback, $web_element);
			});

		$self = $this;
		$expected_result = 'OK';
		$expected_element = $this->createElement();

		$actual_result = $expected_element->waitFor(5000, function ($actual_element) use ($self, $expected_element, $expected_result) {
			$self->assertSame($expected_element, $actual_element, 'typified element is given to callback');

			return $expected_result;
		});

		$this->assertEquals($expected_result, $actual_result, 'callback return value is returned from waitFor call');
	}

	/**
	 * Create element.
	 *
	 * @return AbstractElementContainer
	 */
	protected function createElement()
	{
		return new $this->elementClass($this->webElement, $this->pageFactory);
	}

}
