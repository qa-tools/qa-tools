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
use aik099\QATools\HtmlElements\Element\Radio;

class RadioTest extends LabeledElementTest
{

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\Radio';
		}

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSelect()
	{
		$this->webElement->shouldReceive('check')->once()->andReturnNull();

		$element = $this->getElement();

		$this->assertSame($element, $element->select());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testIsSelected()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('isChecked')->once()->andReturn($expected);

		$this->assertSame($expected, $this->getElement()->isSelected());
	}

	/**
	 * Returns existing element.
	 *
	 * @return Radio
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
