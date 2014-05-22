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


use aik099\QATools\HtmlElements\Element\Link;

class LinkTest extends AbstractTypifiedElementTest
{

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\Link';
		}

		parent::setUp();
	}

	public function testGetUrl()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('getAttribute')->with('href')->once()->andReturn($expected);

		$this->assertSame($expected, $this->getElement()->getUrl());
	}

	public function testClick()
	{
		$this->webElement->shouldReceive('click')->once()->andReturnNull();

		$element = $this->getElement();

		$this->assertSame($element, $element->click());
	}

	public function testGetText()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('getText')->once()->andReturn($expected);

		$this->assertSame($expected, $this->getElement()->getText());
	}

	/**
	 * Returns existing element.
	 *
	 * @return Link
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
