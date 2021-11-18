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


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use QATools\QATools\PageObject\Annotation\FindByAnnotation;
use Mockery as m;
use QATools\QATools\PageObject\ElementLocator\DefaultElementLocator;
use QATools\QATools\PageObject\ElementLocator\IElementLocator;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectException;

class DefaultElementLocatorTest extends TestCase
{

	use MockeryPHPUnitIntegration, ExpectException;

	const PROPERTY_CLASS = '\\QATools\\QATools\\PageObject\\Property';

	const FIND_BY_CLASS = '\\QATools\\QATools\\PageObject\\Annotation\\FindByAnnotation';

	/**
	 * Locator class.
	 *
	 * @var string
	 */
	protected $locatorClass = '\\QATools\\QATools\\PageObject\\ElementLocator\\DefaultElementLocator';

	/**
	 * Search context class.
	 *
	 * @var string
	 */
	protected $searchContextClass = '\\QATools\\QATools\\PageObject\\ISearchContext';

	/**
	 * Property.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $property;

	/**
	 * Annotation manager.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $annotationManager;

	/**
	 * Search context.
	 *
	 * @var \Mockery\MockInterface
	 */
	protected $searchContext;

	/**
	 * Locator.
	 *
	 * @var DefaultElementLocator
	 */
	protected $locator;

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		$this->property = m::mock(self::PROPERTY_CLASS);
		$this->annotationManager = m::mock('\\mindplay\\annotations\\AnnotationManager');
		$this->searchContext = m::mock($this->searchContextClass);

		$this->locator = $this->createLocator();
	}

	public function testGetSearchContext()
	{
		$this->assertSame($this->searchContext, $this->locator->getSearchContext());
	}

	public function testFindOne()
	{
		$locator = $this->createLocator(array('findAll'));
		$locator->shouldReceive('findAll')->andReturn(array('OK'));

		$this->assertEquals('OK', $locator->find());
	}

	public function testFindNone()
	{
		$locator = $this->createLocator(array('findAll'));
		$locator->shouldReceive('findAll')->andReturn(array());

		$this->assertNull($locator->find());
	}

	/**
	 * @dataProvider selectorProvider
	 */
	public function testGetSelectorSuccess(array $selectors, $is_array, $is_collection)
	{
		$this->expectFindByAnnotations($selectors);

		$this->property->shouldReceive('isDataTypeArray')->andReturn($is_array);
		$this->property->shouldReceive('isDataTypeCollection')->andReturn($is_collection);

		foreach ( $selectors as $selector ) {
			$this->searchContext->shouldReceive('findAll')->with('se', $selector)->andReturn(array('OK'));
		}

		$this->assertCount(count($selectors), $this->locator->findAll());
	}

	public function selectorProvider()
	{
		return array(
			array(array(array('xpath' => 'xpath1')), false, false),
			array(array(array('xpath' => 'xpath1')), true, false),
			array(array(array('xpath' => 'xpath1')), false, true),
			array(array(array('xpath' => 'xpath1'), array('xpath' => 'xpath2')), true, false),
			array(array(array('xpath' => 'xpath1'), array('xpath' => 'xpath2')), false, true),
		);
	}

	public function testGetSelectorMultipleNotArrayOrCollection()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\ElementException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\ElementException::TYPE_MULTIPLE_ELEMENTS_FOUND);
		$this->expectExceptionMessage('The "SingleElement" used on "TestPage::button" property expects finding 1 element, but 2 elements were found.');

		$selector = array('xpath' => 'xpath1');

		$this->expectFindByAnnotations(array($selector));

		$this->property->shouldReceive('isDataTypeArray')->andReturn(false);
		$this->property->shouldReceive('isDataTypeCollection')->andReturn(false);
		$this->property->shouldReceive('getRawDataType')->andReturn('SingleElement');
		$this->property->shouldReceive('__toString')->andReturn('TestPage::button');

		$this->searchContext->shouldReceive('findAll')->with('se', $selector)->andReturn(array('OK1', 'OK2'));

		$this->locator->findAll();
	}

	/**
	 * @dataProvider getSelectorFailureDataProvider
	 */
	public function testGetSelectorFailure($annotations)
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\AnnotationException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\AnnotationException::TYPE_REQUIRED);
		$this->expectExceptionMessage('@find-by must be specified in the property "OK" DocBlock or in class "PageClass" DocBlock');

		$this->property->shouldReceive('__toString')->andReturn('OK');
		$this->property->shouldReceive('getDataType')->andReturn('PageClass');
		$this->property->shouldReceive('getAnnotationsFromPropertyOrClass')->with('@find-by')->andReturn($annotations);

		$this->assertCount(0, $this->locator->findAll());
	}

	public function getSelectorFailureDataProvider()
	{
		return array(
			array(array()),
			array(array(m::mock('\\mindplay\\annotations\\Annotation'))),
		);
	}

	public function testToString()
	{
		$expected = 'OK';
		$this->expectFindByAnnotations('OK');

		$this->assertEquals(var_export(array(array('se' => $expected)), true), (string)$this->locator);
	}

	/**
	 * Adds expectation for @find-by annotation.
	 *
	 * @param mixed $selectors Selector.
	 *
	 * @return FindByAnnotation
	 */
	protected function expectFindByAnnotations($selectors)
	{
		$selectors = (array)$selectors;

		$annotations = array();

		foreach ( $selectors as $selector ) {
			$annotation = m::mock(self::FIND_BY_CLASS);
			$annotation->shouldReceive('getSelector')->andReturn($selector);

			$annotations[] = $annotation;
		}

		$this->property->shouldReceive('getAnnotationsFromPropertyOrClass')->with('@find-by')->andReturn($annotations);

		return $annotations;
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
		if ( $mock_methods ) {
			$class = $this->locatorClass . '[' . implode(',', $mock_methods) . ']';

			return m::mock($class, array($this->property, $this->searchContext, $this->annotationManager));
		}

		return new $this->locatorClass($this->property, $this->searchContext, $this->annotationManager);
	}

}
