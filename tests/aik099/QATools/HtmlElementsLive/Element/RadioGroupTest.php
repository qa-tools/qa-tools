<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\aik099\QATools\HtmlElementsLive\Element;


use aik099\QATools\HtmlElements\Element\RadioGroup;

class RadioGroupTest extends TypifiedElementTestCase
{

	const RADIO_CLASS = '\\aik099\\QATools\\HtmlElements\\Element\\RadioButton';

	protected function setUp()
	{
		parent::setUp();

		$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\RadioGroup';
	}

	public function testGetButtonsWithName()
	{
		/* @var $radio_group RadioGroup */
		$radio_group = $this->createElement(array('xpath' => "(//html/descendant-or-self::*[@id = 'r1_v3'])[1]"));
		$buttons = $radio_group->getButtons();

		$this->assertCount(4, $buttons);
		$this->assertInstanceOf(self::RADIO_CLASS, $buttons[0]);
	}

	public function testGetButtonsWithoutName()
	{
		/* @var $radio_group RadioGroup */
		$radio_group = $this->createElement(array(
			'xpath' => "(//html/descendant-or-self::*[@id = 'radio-without-group'])[1]",
		));
		$buttons = $radio_group->getButtons();

		$this->assertCount(5, $buttons);
		$this->assertInstanceOf(self::RADIO_CLASS, $buttons[0]);
	}

	public function testSelection()
	{
		/* @var $radio_group RadioGroup */
		$radio_group = $this->createElement(array('xpath' => "(//html/descendant-or-self::*[@id = 'r1_v3'])[1]"));

		$this->assertFalse($radio_group->hasSelectedButton(), 'No radio button is selected initially');
		$radio_group->selectButtonByValue(4);
		$this->assertEquals(4, $radio_group->getSelectedButton()->getValue());
	}

}
