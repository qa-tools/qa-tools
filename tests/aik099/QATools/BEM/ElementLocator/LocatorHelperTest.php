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


use aik099\QATools\BEM\ElementLocator\LocatorHelper;

class LocatorHelperTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Locator helper.
	 *
	 * @var LocatorHelper
	 */
	private $_locatorHelper;

	/**
	 * Prepares the test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->_locatorHelper = new LocatorHelper();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\BEM\Exception\ElementException
	 * @expectedExceptionCode \aik099\QATools\BEM\Exception\ElementException::TYPE_BLOCK_REQUIRED
	 */
	public function testGetBlockLocatorWithoutName()
	{
		$this->_locatorHelper->getBlockLocator('');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetBlockLocator()
	{
		$locator = $this->_locatorHelper->getBlockLocator('block-name');

		$this->_assertLocatorClassName($locator, 'block-name');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetBlockLocatorWithModificator()
	{
		$locator = $this->_locatorHelper->getBlockLocator('block-name', 'modificator-name', 'modificator-value');

		$this->_assertLocatorClassName($locator, 'block-name_modificator-name_modificator-value');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\BEM\Exception\ElementException
	 * @expectedExceptionCode \aik099\QATools\BEM\Exception\ElementException::TYPE_ELEMENT_REQUIRED
	 */
	public function testGetElementLocatorWithoutName()
	{
		$this->_locatorHelper->getElementLocator('', 'block-name');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\BEM\Exception\ElementException
	 * @expectedExceptionCode \aik099\QATools\BEM\Exception\ElementException::TYPE_BLOCK_REQUIRED
	 */
	public function testGetElementLocatorWithoutBlockName()
	{
		$this->_locatorHelper->getElementLocator('element-name', '');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetElementLocator()
	{
		$locator = $this->_locatorHelper->getElementLocator('element-name', 'block-name');

		$this->_assertLocatorClassName($locator, 'block-name__element-name');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
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
	 * @param array  $Locator  Locator.
	 * @param string $expected Expected class name.
	 *
	 * @return void
	 */
	private function _assertLocatorClassName(array $Locator, $expected)
	{
		$this->assertEquals(array('className' => $expected), $Locator);
	}

}
