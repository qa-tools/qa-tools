<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElementsLive\Elements;


use aik099\QATools\HtmlElements\Elements\Select;

class SelectTest extends TypifiedElementTestCase
{

	const SELECT_OPTION_CLASS = '\\aik099\\QATools\\HtmlElements\\Elements\\SelectOption';

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Elements\\Select';
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetOptions()
	{
		/* @var $element Select */
		$element = $this->createElement(array('id' => 'select-complex'));
		$options = $element->getOptions();

		$this->assertCount(3, $options);
		$this->assertInstanceOf(self::SELECT_OPTION_CLASS, $options[0]);
		$this->assertEquals('t1', $options[0]->getText());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetOptionsByValue()
	{
		/* @var $element Select */
		$element = $this->createElement(array('id' => 'select-complex'));
		$options = $element->getOptionsByValue('v1');

		$this->assertCount(1, $options);
		$this->assertInstanceOf(self::SELECT_OPTION_CLASS, $options[0]);
		$this->assertEquals('v1', $options[0]->getValue());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetOptionsByText()
	{
		/* @var $element Select */
		$element = $this->createElement(array('id' => 'select-complex'));
		$options = $element->getOptionsByText('t1');

		$this->assertCount(1, $options);
		$this->assertInstanceOf(self::SELECT_OPTION_CLASS, $options[0]);
		$this->assertEquals('t1', $options[0]->getText());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
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
