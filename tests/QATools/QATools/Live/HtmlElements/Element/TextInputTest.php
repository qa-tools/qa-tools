<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\Live\HtmlElements\Element;


use QATools\QATools\HtmlElements\Element\TextInput;

class TextInputTest extends TypifiedElementTestCase
{

	protected function setUp()
	{
		parent::setUp();

		$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\TextInput';
	}

	/**
	 * @dataProvider sendKeysDataProvider
	 */
	public function testSendKeys($element_id, $expected_text)
	{
		/** @var TextInput $element */
		$element = $this->createElement(array('id' => $element_id));

		$this->assertEmpty($element->getText());
		$this->assertSame($element, $element->sendKeys($expected_text));
		$this->assertEquals($expected_text, $element->getText());
	}

	public function sendKeysDataProvider()
	{
		return array(
			array('text-input', 'tx'),
			array('text-area', 'a1' . PHP_EOL . 'a2'),
		);
	}

}
