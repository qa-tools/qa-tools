<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\BEM\Annotation;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use QATools\QATools\BEM\Annotation\BEMAnnotation;
use QATools\QATools\BEM\ElementLocator\LocatorHelper;
use Mockery\MockInterface;
use Mockery as m;

class BEMAnnotationTest extends TestCase
{

	use MockeryPHPUnitIntegration;

	/**
	 * Annotation.
	 *
	 * @var BEMAnnotation
	 */
	private $_annotation;

	/**
	 * Locator helper.
	 *
	 * @var LocatorHelper|MockInterface
	 */
	private $_locatorHelper;

	protected function setUp()
	{
		parent::setUp();

		$this->_annotation = new BEMAnnotation();
		$this->_locatorHelper = m::mock('\\QATools\\QATools\\BEM\\ElementLocator\\LocatorHelper');
	}

	public function testGetElementSelector()
	{
		$this->_annotation->block = 'block-name';
		$this->_annotation->element = 'element-name';

		$this->_locatorHelper
			->shouldReceive('getElementLocator')
			->with('element-name', 'block-name', null, null)
			->once()
			->andReturn('OK');

		$this->assertEquals('OK', $this->_annotation->getSelector($this->_locatorHelper));
	}

	public function testGetElementSelectorWithModificator()
	{
		$this->_annotation->block = 'block-name';
		$this->_annotation->element = 'element-name';
		$this->_annotation->modificator = array('modificator-name' => 'modificator-value');

		$this->_locatorHelper
			->shouldReceive('getElementLocator')
			->with('element-name', 'block-name', 'modificator-name', 'modificator-value')
			->once()
			->andReturn('OK');

		$this->assertEquals('OK', $this->_annotation->getSelector($this->_locatorHelper));
	}

	public function testGetBlockSelector()
	{
		$this->_annotation->block = 'block-name';

		$this->_locatorHelper
			->shouldReceive('getBlockLocator')
			->with('block-name', null, null)
			->once()
			->andReturn('OK');

		$this->assertEquals('OK', $this->_annotation->getSelector($this->_locatorHelper));
	}

	public function testGetBlockSelectorWithModificator()
	{
		$this->_annotation->block = 'block-name';
		$this->_annotation->modificator = array('modificator-name' => 'modificator-value');

		$this->_locatorHelper
			->shouldReceive('getBlockLocator')
			->with('block-name', 'modificator-name', 'modificator-value')
			->once()
			->andReturn('OK');

		$this->assertEquals('OK', $this->_annotation->getSelector($this->_locatorHelper));
	}

}
