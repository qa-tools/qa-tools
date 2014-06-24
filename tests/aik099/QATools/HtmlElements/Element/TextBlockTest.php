<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\aik099\QATools\HtmlElements\Element;


use Mockery as m;
use aik099\QATools\HtmlElements\Element\TextBlock;

class TextBlockTest extends AbstractTypifiedElementTest
{

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\TextBlock';
		}

		parent::setUp();
	}

	public function testGetText()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('getText')->once()->andReturn($expected);

		$this->assertEquals($expected, $this->getElement()->getText());
	}

	/**
	 * Returns existing element.
	 *
	 * @return TextBlock
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
