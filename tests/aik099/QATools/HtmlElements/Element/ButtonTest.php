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


use aik099\QATools\HtmlElements\Element\Button;

class ButtonTest extends AbstractTypifiedElementTest
{

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\Button';
		}

		parent::setUp();
	}

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
