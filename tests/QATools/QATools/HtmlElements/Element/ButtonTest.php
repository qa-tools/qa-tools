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


use QATools\QATools\HtmlElements\Element\Button;

class ButtonTest extends AbstractTypifiedElementTest
{

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\Button';
		}

		parent::setUp();
	}

	public function testClick()
	{
		$this->webElement->shouldReceive('click')->once();

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
