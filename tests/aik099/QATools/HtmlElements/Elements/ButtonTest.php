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


use aik099\QATools\HtmlElements\Elements\Button;

class ButtonTest extends TypifiedElementTest
{

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Elements\\Button';
		}

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testClick()
	{
		$this->webElement->shouldReceive('click')->once()->andReturnNull();

		$this->assertSame($this->typifiedElement, $this->getElement()->click());
	}

	/**
	 * Returns existing element.
	 *
	 * @return Button
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
