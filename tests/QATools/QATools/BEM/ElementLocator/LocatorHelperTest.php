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


use QATools\QATools\BEM\ElementLocator\LocatorHelper;

class LocatorHelperTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Locator helper.
	 *
	 * @var LocatorHelper
	 */
	private $_locatorHelper;

	protected function setUp()
	{
		parent::setUp();

		$this->_locatorHelper = new LocatorHelper();
	}

	/**
	 * @expectedException \QATools\QATools\BEM\Exception\ElementException
	 * @expectedExceptionCode \QATools\QATools\BEM\Exception\ElementException::TYPE_BLOCK_REQUIRED
	 */
	public function testGetBlockLocatorWithoutName()
	{
		$this->_locatorHelper->getBlockLocator('');
	}

	public function testGetBlockLocator()
	{
		$locator = $this->_locatorHelper->getBlockLocator('block-name');

		$this->_assertLocatorClassName($locator, 'block-name');
	}

	public function testGetBlockLocatorWithModificator()
	{
		$locator = $this->_locatorHelper->getBlockLocator('block-name', 'modificator-name', 'modificator-value');

		$this->_assertLocatorClassName($locator, 'block-name_modificator-name_modificator-value');
	}

	/**
	 * @expectedException \QATools\QATools\BEM\Exception\ElementException
	 * @expectedExceptionCode \QATools\QATools\BEM\Exception\ElementException::TYPE_ELEMENT_REQUIRED
	 */
	public function testGetElementLocatorWithoutName()
	{
		$this->_locatorHelper->getElementLocator('', 'block-name');
	}

	/**
	 * @expectedException \QATools\QATools\BEM\Exception\ElementException
	 * @expectedExceptionCode \QATools\QATools\BEM\Exception\ElementException::TYPE_BLOCK_REQUIRED
	 */
	public function testGetElementLocatorWithoutBlockName()
	{
		$this->_locatorHelper->getElementLocator('element-name', '');
	}

	public function testGetElementLocator()
	{
		$locator = $this->_locatorHelper->getElementLocator('element-name', 'block-name');

		$this->_assertLocatorClassName($locator, 'block-name__element-name');
	}

	public function testGetElementLocatorWithModificator()
	{
		$locator = $this->_locatorHelper->getElementLocator(
			'element-name', 'block-name',
			'modificator-name', 'modificator-value'
		);

		$this->_assertLocatorClassName($locator, 'block-name__element-name_modificator-name_modificator-value');
	}

	/**
	 * Checks, that Locator has given CSS class.
	 *
	 * @param array  $locator  Locator.
	 * @param string $expected Expected class name.
	 *
	 * @return void
	 */
	private function _assertLocatorClassName(array $locator, $expected)
	{
		$this->assertEquals(array('className' => $expected), $locator);
	}

}
