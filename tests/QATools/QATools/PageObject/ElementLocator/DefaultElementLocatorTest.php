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


use QATools\QATools\PageObject\Annotation\FindByAnnotation;
use Mockery as m;
use QATools\QATools\PageObject\ElementLocator\DefaultElementLocator;
use QATools\QATools\PageObject\ElementLocator\IElementLocator;

class DefaultElementLocatorTest extends \PHPUnit_Framework_TestCase
{

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

	protected function setUp()
	{
		parent::setUp();

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
	public function testGetSelectorSuccess(array $selectors)
	{
		$this->expectFindByAnnotations($selectors);

		foreach ( $selectors as $selector ) {
			$this->searchContext->shouldReceive('findAll')->with('se', $selector)->andReturn(array('OK'));
		}

		$this->assertCount(count($selectors), $this->locator->findAll());
	}

	public function selectorProvider()
	{
		return array(
			array(array(array('xpath' => 'xpath1'))),
			array(array(array('xpath' => 'xpath1'), array('xpath' => 'xpath2'))),
		);
	}

	/**
	 * @dataProvider getSelectorFailureDataProvider
	 *
	 * @expectedException \QATools\QATools\PageObject\Exception\AnnotationException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\AnnotationException::TYPE_REQUIRED
	 * @expectedExceptionMessage @find-by must be specified in the property "OK" DocBlock or in class "PageClass" DocBlock
	 */
	public function testGetSelectorFailure($annotations)
	{
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
