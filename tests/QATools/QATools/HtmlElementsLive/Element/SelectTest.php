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


use QATools\QATools\HtmlElements\Element\Select;

class SelectTest extends TypifiedElementTestCase
{

	const SELECT_OPTION_CLASS = '\\QATools\\QATools\\HtmlElements\\Element\\SelectOption';

	protected function setUp()
	{
		parent::setUp();

		$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\Select';
	}

	public function testGetOptions()
	{
		/* @var $element Select */
		$element = $this->createElement(array('id' => 'select-complex'));
		$options = $element->getOptions();

		$this->assertCount(3, $options);
		$this->assertInstanceOf(self::SELECT_OPTION_CLASS, $options[0]);
		$this->assertEquals('t1', $options[0]->getText());
	}

	public function testGetOptionsByValue()
	{
		/* @var $element Select */
		$element = $this->createElement(array('id' => 'select-complex'));
		$options = $element->getOptionsByValue('v1');

		$this->assertCount(1, $options);
		$this->assertInstanceOf(self::SELECT_OPTION_CLASS, $options[0]);
		$this->assertEquals('v1', $options[0]->getValue());
	}

	public function testGetOptionsByText()
	{
		/* @var $element Select */
		$element = $this->createElement(array('id' => 'select-complex'));
		$options = $element->getOptionsByText('t1');

		$this->assertCount(1, $options);
		$this->assertInstanceOf(self::SELECT_OPTION_CLASS, $options[0]);
		$this->assertEquals('t1', $options[0]->getText());
	}

	public function testSelectByText()
	{
		/* @var $element Select */
		$element = $this->createElement(array('id' => 'select-complex'));
		$element->selectByText('t1');

		$options = $element->getSelectedOptions();

		$this->assertCount(1, $options);
		$this->assertInstanceOf(self::SELECT_OPTION_CLASS, $options[0]);
		$this->assertEquals('t1', $options[0]->getText());
	}

}
