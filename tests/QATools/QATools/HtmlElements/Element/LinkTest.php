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


use QATools\QATools\HtmlElements\Element\Link;

class LinkTest extends AbstractTypifiedElementTest
{

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\Link';
		}

		parent::setUpTest();
	}

	public function testGetUrl()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('getAttribute')->with('href')->once()->andReturn($expected);

		$this->assertSame($expected, $this->getElement()->getUrl());
	}

	public function testClick()
	{
		$this->webElement->shouldReceive('click')->once();

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
