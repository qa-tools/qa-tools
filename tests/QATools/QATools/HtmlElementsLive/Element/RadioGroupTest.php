<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\HtmlElementsLive\Element;


use QATools\QATools\HtmlElements\Element\RadioGroup;

class RadioGroupTest extends TypifiedElementTestCase
{

	const RADIO_CLASS = '\\QATools\\QATools\\HtmlElements\\Element\\RadioButton';

	protected function setUp()
	{
		parent::setUp();

		$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\RadioGroup';
	}

	public function testGetButtonsWithName()
	{
		/* @var $radio_group RadioGroup */
		$radio_group = $this->createElement(array('xpath' => "/descendant-or-self::*[@name = 'radio-group1']"));

		$this->assertCount(4, $radio_group);
		$this->assertInstanceOf(self::RADIO_CLASS, $radio_group[0]);
	}

	public function testGetButtonsWithoutName()
	{
		/* @var $radio_group RadioGroup */
		$radio_group = $this->createElement(array(
			'xpath' => "/descendant-or-self::*[@type = 'radio']",
		));

		$this->assertCount(5, $radio_group);
		$this->assertInstanceOf(self::RADIO_CLASS, $radio_group[0]);
	}

	public function testSelection()
	{
		/* @var $radio_group RadioGroup */
		$radio_group = $this->createElement(array('xpath' => "/descendant-or-self::*[@name = 'radio-group1']"));

		$this->assertFalse($radio_group->hasSelectedButton(), 'No radio button is selected initially');
		$radio_group->selectButtonByValue(4);
		$this->assertEquals(4, $radio_group->getSelectedButton()->getValue());
	}

	/**
	 * Creates element.
	 *
	 * @param array $selector Selector.
	 *
	 * @return RadioGroup
	 */
	protected function createElement(array $selector)
	{
		$node_elements = $this->session->getPage()->findAll('se', $selector);

		return call_user_func($this->elementClass . '::fromNodeElements', $node_elements, null, $this->pageFactory);
	}

}
