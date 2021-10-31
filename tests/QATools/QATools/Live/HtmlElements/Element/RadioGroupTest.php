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


use QATools\QATools\HtmlElements\Element\RadioGroup;

class RadioGroupTest extends TypifiedElementTestCase
{

	const RADIO_CLASS = '\\QATools\\QATools\\HtmlElements\\Element\\RadioButton';

	const XPATH_RADIO_GROUP = "/descendant-or-self::*[@name = 'radio-group1']";

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		parent::setUpTest();

		$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\RadioGroup';
	}

	public function testGetButtonsWithName()
	{
		/** @var RadioGroup $radio_group */
		$radio_group = $this->createElement(array('xpath' => self::XPATH_RADIO_GROUP));

		$this->assertCount(4, $radio_group);
		$this->assertInstanceOf(self::RADIO_CLASS, $radio_group[0]);
	}

	public function testGetButtonsWithoutName()
	{
		/** @var RadioGroup $radio_group */
		$radio_group = $this->createElement(array(
			'xpath' => "/descendant-or-self::*[@type = 'radio']",
		));

		$this->assertCount(5, $radio_group);
		$this->assertInstanceOf(self::RADIO_CLASS, $radio_group[0]);
	}

	public function testSelection()
	{
		/** @var RadioGroup $radio_group */
		$radio_group = $this->createElement(array('xpath' => self::XPATH_RADIO_GROUP));

		$this->assertFalse($radio_group->hasSelectedButton(), 'No radio button is selected initially');
		$radio_group->selectButtonByValue(4);
		$this->assertEquals(4, $radio_group->getSelectedButton()->getValue());
	}

	public function testAccessOfSingleRadioButton()
	{
		/** @var RadioGroup $radio_group */
		$radio_group = $this->createElement(array('xpath' => self::XPATH_RADIO_GROUP));

		$this->assertFalse($radio_group->hasSelectedButton(), 'No radio button is selected initially');
		$radio_group[2]->select();
		$this->assertEquals(3, $radio_group->getSelectedButton()->getValue());
	}

	public function testIteratingRadioButtons()
	{
		/** @var RadioGroup $radio_group */
		$radio_group = $this->createElement(array('xpath' => self::XPATH_RADIO_GROUP));

		$this->assertFalse($radio_group->hasSelectedButton(), 'No radio button is selected initially');
		$this->assertCount(4, $radio_group);

		foreach ( $radio_group as $index => $radio ) {
			$radio->select();

			foreach ( $radio_group as $check_index => $check_radio ) {
				$this->assertEquals($check_index === $index, $check_radio->isSelected());
			}
		}
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
