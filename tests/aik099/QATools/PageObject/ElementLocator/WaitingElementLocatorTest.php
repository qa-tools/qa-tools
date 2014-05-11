<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\PageObject\ElementLocator;


use aik099\QATools\PageObject\Annotation\TimeoutAnnotation;
use Mockery as m;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;

class WaitingElementLocatorTest extends DefaultElementLocatorTest
{

	const TIMEOUT = 5000;

	/**
	 * Prepares page.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->locatorClass = '\\aik099\\QATools\\PageObject\\ElementLocator\\WaitingElementLocator';

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetSelectorSuccessWithTimeout()
	{
		$search_context = $this->searchContext;
		$node_element = m::mock('\\Behat\\Mink\\Element\\NodeElement');

		$expected = array('xpath' => 'xpath1');
		$this->expectFindByAnnotation($expected);
		$this->searchContext->shouldReceive('findAll')->with('se', $expected)->andReturn(array($node_element));

		$this->searchContext
			->shouldReceive('waitFor')
			->with(self::TIMEOUT, m::type('callable'))
			->once()
			->andReturnUsing(function ($timeout, $callback) use ($search_context) {
				return call_user_func($callback, $search_context);
			});

		$found_elements = $this->locator->findAll();

		$this->assertCount(1, $found_elements);
		$this->assertSame($node_element, $found_elements[0]);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\PageObject\Exception\AnnotationException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\AnnotationException::TYPE_REQUIRED
	 */
	public function testGetSelectorFailureWithTimeout()
	{
		$this->property->shouldReceive('__toString')->andReturn('OK');
		$this->property->shouldReceive('getDataType')->andReturnNull();
		$this->property->shouldReceive('getAnnotationsFromPropertyOrClass')->with('@find-by')->andReturn(array());

		$search_context = $this->searchContext;

		$this->searchContext
			->shouldReceive('waitFor')
			->with(5000, m::type('callable'))
			->once()
			->andReturnUsing(function ($timeout, $callback) use ($search_context) {
				return call_user_func($callback, $search_context);
			});

		$this->assertCount(0, $this->locator->findAll());
	}

	/**
	 * Creates locator.
	 *
	 * @param array $mock_methods Mock methods.
	 *
	 * @return IElementLocator
	 */
	protected function createLocator(array $mock_methods = array())
	{
		$timeout_annotations = array();

		if ( substr($this->getName(false), -11) == 'WithTimeout' ) {
			$timeout_annotation = new TimeoutAnnotation();
			$timeout_annotation->duration = self::TIMEOUT;
			$timeout_annotations = array($timeout_annotation);
		}

		$this->property
			->shouldReceive('getAnnotationsFromPropertyOrClass')
			->with('@timeout')
			->once()
			->andReturn($timeout_annotations);

		return parent::createLocator($mock_methods);
	}

}
