<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElementsLive\Element;


use aik099\QATools\HtmlElements\Element\TextInput;

class TextInputTest extends TypifiedElementTestCase
{

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\TextInput';
	}

	/**
	 * Test description.
	 *
	 * @param string $element_id    Element ID.
	 * @param string $expected_text Expected text.
	 *
	 * @return void
	 * @dataProvider sendKeysDataProvider
	 */
	public function testSendKeys($element_id, $expected_text)
	{
		/* @var $element TextInput */
		$element = $this->createElement(array('id' => $element_id));

		$this->assertEmpty($element->getText());
		$this->assertSame($element, $element->sendKeys($expected_text));
		$this->assertEquals($expected_text, $element->getText());
	}

	/**
	 * Test data for "sendKeys" method.
	 *
	 * @return array
	 */
	public function sendKeysDataProvider()
	{
		return array(
			array('text-input', 'tx'),
			array('text-area', 'a1' . PHP_EOL . 'a2'),
		);
	}

}
