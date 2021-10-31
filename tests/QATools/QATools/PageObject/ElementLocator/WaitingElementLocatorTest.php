<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\ElementLocator;


use QATools\QATools\PageObject\Annotation\TimeoutAnnotation;
use Mockery as m;
use QATools\QATools\PageObject\ElementLocator\IElementLocator;

class WaitingElementLocatorTest extends DefaultElementLocatorTest
{

	const TIMEOUT = 5;

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		$this->locatorClass = '\\QATools\\QATools\\PageObject\\ElementLocator\\WaitingElementLocator';

		parent::setUpTest();
	}

	/**
	 * @dataProvider selectorProvider
	 */
	public function testGetSelectorsSuccessWithTimeout(array $selectors, $is_array, $is_collection)
	{
		$search_context = $this->searchContext;
		$node_element = m::mock('\\Behat\\Mink\\Element\\NodeElement');

		$this->expectFindByAnnotations($selectors);

		$this->property->shouldReceive('isDataTypeArray')->andReturn($is_array);
		$this->property->shouldReceive('isDataTypeCollection')->andReturn($is_collection);

		foreach ( $selectors as $selector ) {
			$this->searchContext->shouldReceive('findAll')->with('se', $selector)->andReturn(array($node_element));
		}

		$this->searchContext
			->shouldReceive('waitFor')
			->with(self::TIMEOUT, m::type('callable'))
			->once()
			->andReturnUsing(function ($timeout, $callback) use ($search_context) {
				return call_user_func($callback, $search_context);
			});

		$found_elements = $this->locator->findAll();

		$this->assertCount(count($selectors), $found_elements);
		$this->assertSame($node_element, $found_elements[0]);
	}

	public function testGetSelectorFailureWithTimeout()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\AnnotationException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\AnnotationException::TYPE_REQUIRED);
		$this->expectExceptionMessage('@find-by must be specified in the property "OK" DocBlock or in class "PageClass" DocBlock');

		$this->property->shouldReceive('__toString')->andReturn('OK');
		$this->property->shouldReceive('getDataType')->andReturn('PageClass');
		$this->property->shouldReceive('getAnnotationsFromPropertyOrClass')->with('@find-by')->andReturn(array());

		$search_context = $this->searchContext;

		$this->searchContext
			->shouldReceive('waitFor')
			->with(5, m::type('callable'))
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
