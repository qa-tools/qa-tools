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


use QATools\QATools\HtmlElements\Element\AbstractElementContainer;
use Mockery as m;
use tests\QATools\QATools\HtmlElements\Fixture\Element\ElementContainerChild;

class AbstractElementContainerTest extends AbstractTypifiedElementTest
{

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\tests\\QATools\\QATools\\HtmlElements\\Fixture\\Element\\ElementContainerChild';
		}

		parent::setUpTest();
	}

	/**
	 * Occurs before element creation in setUpTest.
	 *
	 * @return void
	 */
	protected function setUpBeforeCreateElement()
	{
		parent::setUpBeforeCreateElement();

		$two_times_tests = array(
			'testFromNodeElement', 'testToString', 'testFill', 'testGetPageFactory', 'testWaitFor',
		);
		$times = in_array($this->getName(), $two_times_tests) ? 2 : 1;

		$this->pageFactory->shouldReceive('initElementContainer')->times($times)->andReturn($this->pageFactory);
		$this->pageFactory->shouldReceive('initElements')->times($times)->andReturn($this->pageFactory);

		$decorator = m::mock('\\QATools\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
		$this->pageFactory->shouldReceive('createDecorator')->times($times)->andReturn($decorator);
	}

	public function testFindAll()
	{
		$expected = 'C';
		$this->webElement->shouldReceive('findAll')->with('A', 'B')->once()->andReturn($expected);
		$this->webElement->shouldReceive('find')->with('A', 'B')->once()->andReturn($expected);

		$this->assertSame($expected, $this->typifiedElement->findAll('A', 'B'));
		$this->assertSame($expected, $this->typifiedElement->find('A', 'B'));
	}

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
	 * @return AbstractElementContainer
	 */
	protected function createElement()
	{
		return new $this->elementClass($this->webElement, $this->pageFactory);
	}

}
