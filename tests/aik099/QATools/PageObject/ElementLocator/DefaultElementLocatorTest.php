<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\aik099\QATools\PageObject\ElementLocator;


use aik099\QATools\PageObject\Annotation\FindByAnnotation;
use Mockery as m;
use aik099\QATools\PageObject\ElementLocator\DefaultElementLocator;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;

class DefaultElementLocatorTest extends \PHPUnit_Framework_TestCase
{

	const PROPERTY_CLASS = '\\aik099\\QATools\\PageObject\\Property';

	const FIND_BY_CLASS = '\\aik099\\QATools\\PageObject\\Annotation\\FindByAnnotation';

	/**
	 * Locator class.
	 *
	 * @var string
	 */
	protected $locatorClass = '\\aik099\\QATools\\PageObject\\ElementLocator\\DefaultElementLocator';

	/**
	 * Search context class.
	 *
	 * @var string
	 */
	protected $searchContextClass = '\\aik099\\QATools\\PageObject\\ISearchContext';

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

	public function testGetSelectorSuccess()
	{
		$expected = array('xpath' => 'xpath1');
		$this->expectFindByAnnotation($expected);
		$this->searchContext->shouldReceive('findAll')->with('se', $expected)->andReturn(array());

		$this->assertCount(0, $this->locator->findAll());
	}

	/**
	 * @expectedException \aik099\QATools\PageObject\Exception\AnnotationException
	 * @expectedExceptionCode \aik099\QATools\PageObject\Exception\AnnotationException::TYPE_REQUIRED
	 */
	public function testGetSelectorFailure()
	{
		$this->property->shouldReceive('__toString')->andReturn('OK');
		$this->property->shouldReceive('getDataType');
		$this->property->shouldReceive('getAnnotationsFromPropertyOrClass')->with('@find-by')->andReturn(array());

		$this->assertCount(0, $this->locator->findAll());
	}

	public function testToString()
	{
		$expected = 'OK';
		$this->expectFindByAnnotation('OK');

		$this->assertEquals(var_export(array('se' => $expected), true), (string)$this->locator);
	}

	/**
	 * Adds expectation for @find-by annotation.
	 *
	 * @param mixed $selector Selector.
	 *
	 * @return FindByAnnotation
	 */
	protected function expectFindByAnnotation($selector)
	{
		$annotation = m::mock(self::FIND_BY_CLASS);
		$annotation->shouldReceive('getSelector')->andReturn($selector);

		$this->property->shouldReceive('getAnnotationsFromPropertyOrClass')->with('@find-by')->andReturn(array($annotation));

		return $annotation;
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
