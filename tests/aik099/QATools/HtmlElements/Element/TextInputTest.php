<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElements\Element;


use Mockery as m;
use aik099\QATools\HtmlElements\Element\TextInput;

class TextInputTest extends TypifiedElementTest
{

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\TextInput';
		}

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testClear()
	{
		$this->webElement->shouldReceive('setValue')->with('')->once()->andReturnNull();

		$element = $this->getElement();

		$this->assertEquals($element, $element->clear());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSendKeys()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('setValue')->with($expected)->once()->andReturnNull();

		$element = $this->getElement();

		$this->assertEquals($element, $element->sendKeys($expected));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetText()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('getValue')->once()->andReturn($expected);

		$this->assertEquals($expected, $this->getElement()->getText());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetValue()
	{
		/* @var $element TextInput */
		$element = $this->mockElement(array('sendKeys'));
		$element->shouldReceive('sendKeys')->with('5')->once()->andReturn($element);

		$this->assertSame($element, $element->setValue(5));
	}

	/**
	 * Returns existing element.
	 *
	 * @return TextInput
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
