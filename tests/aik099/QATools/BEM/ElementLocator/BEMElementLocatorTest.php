<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\BEM\ElementLocator;


use aik099\QATools\BEM\Annotation\BEMAnnotation;
use aik099\QATools\BEM\ElementLocator\BEMElementLocator;
use aik099\QATools\BEM\ElementLocator\LocatorHelper;
use Mockery as m;
use aik099\QATools\PageObject\ElementLocator\IElementLocator;
use Mockery\MockInterface;
use tests\aik099\QATools\PageObject\ElementLocator\DefaultElementLocatorTest;

class BEMElementLocatorTest extends DefaultElementLocatorTest
{

	const BEM_CLASS = '\\aik099\\QATools\\BEM\\Annotation\\BEMAnnotation';

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
		$this->searchContextClass = '\\aik099\\QATools\\BEM\\Element\\IBlock';
		$this->locatorClass = '\\aik099\\QATools\\BEM\\ElementLocator\\BEMElementLocator';
		$this->_locatorHelper = m::mock('\\aik099\\QATools\\BEM\\ElementLocator\\LocatorHelper');

		parent::setUp();
	}

	public function testGetSelectorSuccess()
	{
		$expected = array('xpath' => 'xpath1');
		$annotation = $this->expectBEMAnnotation($expected);
		$annotation->element = 'element-name';

		$this->searchContext->shouldReceive('findAll')->with('se', $expected)->andReturn(array());
		$this->searchContext->shouldReceive('getName')->once()->andReturn('block-name');

		$this->assertCount(0, $this->locator->findAll());
		$this->assertEquals('block-name', $annotation->block, 'block name set to element annotation from parent block');
	}

	public function testGetSelectorBlock()
	{
		$expected = array('xpath' => 'xpath1');
		$annotation = $this->expectBEMAnnotation($expected);
		$annotation->block = 'block-name';

		$this->searchContext->shouldReceive('findAll')->with('se', $expected)->andReturn(array());
		$this->searchContext->shouldReceive('getName')->never();

		$this->assertEquals('', $annotation->element, 'element name isn\'t touched');
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
		$this->property->shouldReceive('getAnnotations')->with('@bem')->andReturn(array());

		$this->assertCount(0, $this->locator->findAll());
	}

	public function testToString()
	{
		$expected = 'OK';
		$this->expectBEMAnnotation('OK');

		$this->assertEquals(var_export(array('se' => $expected), true), (string)$this->locator);
	}

	/**
	 * Adds expectation for @bem annotation.
	 *
	 * @param mixed $selector Selector.
	 *
	 * @return BEMAnnotation
	 */
	protected function expectBEMAnnotation($selector)
	{
		$annotation = m::mock(self::BEM_CLASS);
		$annotation->shouldReceive('getSelector')->with($this->_locatorHelper)->andReturn($selector);

		$this->property->shouldReceive('getAnnotations')->with('@bem')->andReturn(array($annotation));

		return $annotation;
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
