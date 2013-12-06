<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElements\Elements;


use aik099\QATools\HtmlElements\Elements\Checkbox;
use Mockery as m;

class CheckboxTest extends LabeledElementTest
{

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Elements\\Checkbox';
		}

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @param string  $test_method Test method.
	 * @param boolean $checked     Checked.
	 *
	 * @return void
	 * @dataProvider checkDataProvider
	 */
	public function testCheckUncheck($test_method, $checked)
	{
		$this->webElement->shouldReceive($test_method)->once()->andReturnNull();

		$element = $this->getElement();

		$this->assertSame($element, $checked ? $element->check() : $element->uncheck());
	}

	/**
	 * Test description.
	 *
	 * @param string  $test_method Test method.
	 * @param boolean $checked     Checked.
	 *
	 * @return void
	 * @dataProvider checkDataProvider
	 */
	public function testToggle($test_method, $checked)
	{
		/* @var $element Checkbox */
		$element = $this->mockElement(array($test_method));
		$element->shouldReceive($test_method)->once()->andReturn('OK');

		$this->assertEquals('OK', $element->toggle($checked));
	}

	/**
	 * Test description.
	 *
	 * @param string  $test_method Test method.
	 * @param boolean $checked     Checked.
	 *
	 * @return void
	 * @dataProvider checkDataProvider
	 */
	public function testToggleInvert($test_method, $checked)
	{
		/* @var $element Checkbox */
		$element = $this->mockElement(array($test_method, 'isChecked'));
		$element->shouldReceive('isChecked')->once()->andReturn(!$checked);
		$element->shouldReceive($test_method)->once()->andReturn('OK');

		$this->assertEquals('OK', $element->toggle());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testIsChecked()
	{
		$this->webElement->shouldReceive('isChecked')->once()->andReturn('OK');

		$this->assertEquals('OK', $this->getElement()->isChecked());
	}

	/**
	 * Data provider for "toggle" method test.
	 *
	 * @return array
	 */
	public function checkDataProvider()
	{
		return array(
			array('check', true),
			array('uncheck', false),
		);
	}

	/**
	 * Test description.
	 *
	 * @param mixed   $value       Input value.
	 * @param boolean $final_value Final value, that goes to toggle method.
	 *
	 * @return void
	 * @dataProvider setValueDataProvider
	 */
	public function testSetValue($value, $final_value)
	{
		/* @var $checkbox Checkbox */
		$checkbox = $this->mockElement(array('toggle'));
		$checkbox->shouldReceive('toggle')->with($final_value)->once()->andReturn($checkbox);

		$this->assertSame($checkbox, $checkbox->setValue($value));
	}

	/**
	 * Test data for "setValue" method testing.
	 *
	 * @return array
	 */
	public function setValueDataProvider()
	{
		return array(
			array(1, true),
			array(0, false),
			array('1', true),
			array('0', false),
		);
	}

	/**
	 * Returns existing element.
	 *
	 * @return Checkbox
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
