<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\HtmlElements\Element;


use QATools\QATools\HtmlElements\Element\Checkbox;
use Mockery as m;

class CheckboxTest extends LabeledElementTest
{

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\Checkbox';
		}

		$this->expectedAttributes = array('type' => 'checkbox');

		parent::setUp();
	}

	/**
	 * @dataProvider checkDataProvider
	 */
	public function testCheckUncheck($test_method, $checked)
	{
		$this->webElement->shouldReceive($test_method)->once();

		$element = $this->getElement();

		$this->assertSame($element, $checked ? $element->check() : $element->uncheck());
	}

	/**
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
	 * @dataProvider setValueDataProvider
	 */
	public function testSetValue($value, $final_value)
	{
		/* @var $checkbox Checkbox */
		$checkbox = $this->mockElement(array('toggle'));
		$checkbox->shouldReceive('toggle')->with($final_value)->once()->andReturn($checkbox);

		$this->assertSame($checkbox, $checkbox->setValue($value));
	}

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
