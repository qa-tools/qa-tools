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
use QATools\QATools\HtmlElements\Element\RadioButton;

class RadioTest extends LabeledElementTest
{

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\RadioButton';
		}

		parent::setUp();
	}

	public function testSelect()
	{
		$this->webElement->shouldReceive('getAttribute')->with('value')->once()->andReturn('OK');
		$this->webElement->shouldReceive('selectOption')->with('OK')->once();

		$element = $this->getElement();

		$this->assertSame($element, $element->select());
	}

	public function testIsSelected()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('isChecked')->once()->andReturn($expected);

		$this->assertSame($expected, $this->getElement()->isSelected());
	}

	/**
	 * Returns existing element.
	 *
	 * @return RadioButton
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
