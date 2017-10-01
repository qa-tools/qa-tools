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


use Mockery as m;
use QATools\QATools\HtmlElements\Element\TextInput;

class TextInputTest extends AbstractTypifiedElementTest
{

	protected function setUp()
	{
		if ( $this->elementClass === null ) {
			$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\TextInput';
		}

		parent::setUp();
	}

	public function testClear()
	{
		$this->webElement->shouldReceive('setValue')->with('')->once();

		$element = $this->getElement();

		$this->assertEquals($element, $element->clear());
	}

	public function testSendKeys()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('setValue')->with($expected)->once();

		$element = $this->getElement();

		$this->assertEquals($element, $element->sendKeys($expected));
	}

	public function testGetText()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('getValue')->once()->andReturn($expected);

		$this->assertEquals($expected, $this->getElement()->getText());
	}

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
