<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\BEM\ElementLocator;


use QATools\QATools\BEM\Annotation\BEMAnnotation;
use QATools\QATools\BEM\ElementLocator\BEMElementLocator;
use QATools\QATools\BEM\ElementLocator\LocatorHelper;
use Mockery as m;
use QATools\QATools\PageObject\ElementLocator\IElementLocator;
use Mockery\MockInterface;
use tests\QATools\QATools\PageObject\ElementLocator\DefaultElementLocatorTest;

class BEMElementLocatorTest extends DefaultElementLocatorTest
{

	const BEM_CLASS = '\\QATools\\QATools\\BEM\\Annotation\\BEMAnnotation';

	/**
	 * Locator.
	 *
	 * @var BEMElementLocator
	 */
	protected $locator;

	/**
	 * Locator helper.
	 *
	 * @var LocatorHelper|MockInterface
	 */
	private $_locatorHelper;

	protected function setUp()
	{
		$this->searchContextClass = '\\QATools\\QATools\\BEM\\Element\\IBlock';
		$this->locatorClass = '\\QATools\\QATools\\BEM\\ElementLocator\\BEMElementLocator';
		$this->_locatorHelper = m::mock('\\QATools\\QATools\\BEM\\ElementLocator\\LocatorHelper');

		parent::setUp();
	}

	/**
	 * @dataProvider selectorProvider
	 */
	public function testGetSelectorSuccess(array $selectors)
	{
		$annotations = $this->expectBEMAnnotation($selectors);

		foreach ( $annotations as $annotation ) {
			$annotation->element = 'element-name';
		}

		foreach ( $selectors as $selector ) {
			$this->searchContext->shouldReceive('findAll')->with('se', $selector)->andReturn(array('OK'));
		}

		$this->searchContext->shouldReceive('getName')->times(count($selectors))->andReturn('block-name');

		$this->assertCount(count($selectors), $this->locator->findAll());

		foreach ( $annotations as $annotation ) {
			$this->assertEquals('block-name', $annotation->block, 'block name set to element annotation from parent block');
		}
	}

	public function testGetSelectorBlock()
	{
		$expected = array('xpath' => 'xpath1');
		$annotations = $this->expectBEMAnnotation(array($expected));

		$annotations[0]->block = 'block-name';

		$this->searchContext->shouldReceive('findAll')->with('se', $expected)->andReturn(array());
		$this->searchContext->shouldReceive('getName')->never();

		$this->assertEquals('', $annotations[0]->element, 'element name isn\'t touched');
		$this->assertCount(0, $this->locator->findAll());
	}

	/**
	 * @dataProvider getSelectorFailureDataProvider
	 *
	 * @expectedException \QATools\QATools\PageObject\Exception\AnnotationException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\AnnotationException::TYPE_REQUIRED
	 * @expectedExceptionMessage BEM block/element must be specified as annotation
	 */
	public function testGetSelectorFailure($annotations)
	{
		$this->property->shouldReceive('__toString')->andReturn('OK');
		$this->property->shouldReceive('getDataType');
		$this->property->shouldReceive('getAnnotations')->with('@bem')->andReturn($annotations);

		$this->assertCount(0, $this->locator->findAll());
	}

	public function testToString()
	{
		$expected = 'OK';
		$this->expectBEMAnnotation('OK');

		$this->assertEquals(var_export(array(array('se' => $expected)), true), (string)$this->locator);
	}

	/**
	 * Adds expectation for @bem annotation.
	 *
	 * @param mixed $selectors Selector.
	 *
	 * @return BEMAnnotation
	 */
	protected function expectBEMAnnotation($selectors)
	{
		$annotations = array();
		$selectors = (array)$selectors;

		foreach ( $selectors as $selector ) {
			$annotation = m::mock(self::BEM_CLASS);
			$annotation->shouldReceive('getSelector')->with($this->_locatorHelper)->andReturn($selector);

			$annotations[] = $annotation;
		}

		$this->property->shouldReceive('getAnnotations')->with('@bem')->andReturn($annotations);

		return $annotations;
	}

	public function testGetBlockLocator()
	{
		$this->_locatorHelper
			->shouldReceive('getBlockLocator')
			->with('block-name', 'modificator-name', 'modificator-value')
			->once()
			->andReturn('OK');

		$this->assertEquals('OK', $this->locator->getBlockLocator('block-name', 'modificator-name', 'modificator-value'));
	}

	public function testGetElementLocator()
	{
		$this->_locatorHelper
			->shouldReceive('getElementLocator')
			->with('element-name', 'block-name', 'modificator-name', 'modificator-value')
			->once()
			->andReturn('OK');

		$this->assertEquals(
			'OK',
			$this->locator->getElementLocator('element-name', 'block-name', 'modificator-name', 'modificator-value')
		);
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

			return m::mock(
				$class,
				array($this->property, $this->searchContext, $this->annotationManager, $this->_locatorHelper)
			);
		}

		return new $this->locatorClass(
			$this->property, $this->searchContext, $this->annotationManager, $this->_locatorHelper
		);
	}

}
