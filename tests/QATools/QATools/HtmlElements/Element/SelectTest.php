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


use QATools\QATools\HtmlElements\Element\Select;
use Mockery as m;
use QATools\QATools\HtmlElements\Element\SelectOption;

class SelectTest extends AbstractTypifiedElementTest
{

	const SELECT_OPTION_CLASS = '\\QATools\\QATools\\HtmlElements\\Element\\SelectOption';

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\Select';
		}

		$this->ignoreExpectTypifiedNodeCheck[] = 'testGetOptions';
		$this->ignoreExpectTypifiedNodeCheck[] = 'testGetOptionsByValue';
		$this->ignoreExpectTypifiedNodeCheck[] = 'testGetOptionsByText';

		$this->expectedTagName = 'select';

		parent::setUp();
	}

	/**
	 * Occurs before element creation in setUp.
	 *
	 * @return void
	 */
	protected function setUpBeforeCreateElement()
	{
		$this->expectWebElementGetTagName('select');
	}

	/**
	 * @dataProvider isMultipleDataProvider
	 */
	public function testIsMultiple($attribute_value, $multiple)
	{
		$this->webElement->shouldReceive('hasAttribute')->with('multiple')->once()->andReturn($attribute_value);

		$this->assertSame($multiple, $this->getElement()->isMultiple());
	}

	public function isMultipleDataProvider()
	{
		return array(
			array(false, false),
			array(true, true),
		);
	}

	public function testGetOptions()
	{
		$this->expectDriverGetTagName('option');

		$this->webElement->shouldReceive('findAll')->with('se', array('tagName' => 'option'))->once()->andReturn(
			array($this->createNodeElement())
		);

		$this->typifiedElement = $this->createElement();

		$this->assertValidOptions($this->getElement()->getOptions());
	}

	public function testGetOptionsByValue()
	{
		$this->expectDriverGetTagName('option');

		$this->selectorsHandler->shouldReceive('xpathLiteral')->with('SV')->andReturn('SV');

		$this->webElement
			->shouldReceive('findAll')
			->with('xpath', 'descendant-or-self::option[@value = SV]')
			->once()
			->andReturn(
				array($this->createNodeElement())
			);

		$this->typifiedElement = $this->createElement();

		$this->assertValidOptions($this->getElement()->getOptionsByValue('SV'));
	}

	/**
	 * @dataProvider trueFalseDataProvider
	 */
	public function testGetOptionsByText($exact_match)
	{
		$this->expectDriverGetTagName('option');

		$this->selectorsHandler->shouldReceive('xpathLiteral')->with('SV')->andReturn('SV');

		if ( $exact_match ) {
			$xpath = 'descendant-or-self::option[normalize-space(.) = SV]';
		}
		else {
			$xpath = 'descendant-or-self::option[contains(., SV)]';
		}

		$this->webElement->shouldReceive('findAll')->with('xpath', $xpath)->once()->andReturn(
			array($this->createNodeElement())
		);

		$this->typifiedElement = $this->createElement();

		$this->assertValidOptions($this->getElement()->getOptionsByText('SV', $exact_match));
	}

	public function testGetSelectedOptions()
	{
		$this->expectDriverGetTagName('option');

		$selected_option = $this->createOption(true);
		$not_selected_option = $this->createOption(false);

		/* @var $element Select */
		$element = $this->mockElement(array('getOptions'));
		$element->shouldReceive('getOptions')->andReturn(array($not_selected_option, $selected_option));

		$options = $element->getSelectedOptions();
		$this->assertValidOptions($options);

		$this->assertSame($selected_option, $options[0]);
	}

	public function testGetFirstSelectedOptionSuccess()
	{
		$this->expectDriverGetTagName('option');

		$selected_option1 = $this->createOption(true);
		$selected_option2 = $this->createOption(true);
		$not_selected_option = $this->createOption(false);

		/* @var $element Select */
		$element = $this->mockElement(array('getOptions'));
		$element->shouldReceive('getOptions')->andReturn(array($not_selected_option, $selected_option1, $selected_option2));

		$option = $element->getFirstSelectedOption();
		$this->assertValidOptions(array($option));

		$this->assertSame($selected_option1, $option);
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\SelectException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\SelectException::TYPE_NOT_SELECTED
	 * @expectedExceptionMessage No options are selected
	 */
	public function testGetFirstSelectedOptionError()
	{
		$this->expectDriverGetTagName('option');

		/* @var $element Select */
		$element = $this->mockElement(array('getOptions'));
		$element->shouldReceive('getOptions')->andReturn(array());

		$element->getFirstSelectedOption();
	}

	/**
	 * @dataProvider trueFalseDataProvider
	 */
	public function testSelectByText($is_multiple)
	{
		/* @var $element Select */
		$element = $this->mockElement(array('getOptionsByText', 'isMultiple'));
		$element->shouldReceive('isMultiple')->once()->andReturn($is_multiple);
		$element->shouldReceive('getOptionsByText')->with('TX', true)->andReturn($this->getSelectOptions($is_multiple));

		$this->assertSame($element, $element->selectByText('TX', true));
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\SelectException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\SelectException::TYPE_NOT_FOUND
	 * @expectedExceptionMessage Cannot locate option with text: TX
	 */
	public function testSelectByTextWithoutExactMatch()
	{
		/* @var $element Select */
		$element = $this->mockElement(array('getOptionsByText'));
		$element->shouldReceive('getOptionsByText')->andReturn(array());

		$this->assertSame($element, $element->selectByText('TX'));
	}

	public function testDeselectByText()
	{
		$selected_option = $this->createOption(true);
		$selected_option->shouldReceive('deselect')->once();

		/* @var $element Select */
		$element = $this->mockElement(array('getOptionsByText'));
		$element->shouldReceive('getOptionsByText')->andReturn(array($selected_option));

		$this->assertSame($element, $element->deselectByText('TX', true));
	}

	/**
	 * @dataProvider trueFalseDataProvider
	 */
	public function testSelectByValue($is_multiple)
	{
		/* @var $element Select */
		$element = $this->mockElement(array('getOptionsByValue', 'isMultiple'));
		$element->shouldReceive('isMultiple')->once()->andReturn($is_multiple);
		$element->shouldReceive('getOptionsByValue')->with('TX')->andReturn($this->getSelectOptions($is_multiple));

		$this->assertSame($element, $element->selectByValue('TX'));
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\SelectException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\SelectException::TYPE_NOT_FOUND
	 * @expectedExceptionMessage Cannot locate option with value: TX
	 */
	public function testSelectByValueWithoutExactMatch()
	{
		/* @var $element Select */
		$element = $this->mockElement(array('getOptionsByValue'));
		$element->shouldReceive('getOptionsByValue')->andReturn(array());

		$this->assertSame($element, $element->selectByValue('TX'));
	}

	public function testDeselectByValue()
	{
		$selected_option = $this->createOption(true);
		$selected_option->shouldReceive('deselect')->once();

		/* @var $element Select */
		$element = $this->mockElement(array('getOptionsByValue'));
		$element->shouldReceive('getOptionsByValue')->andReturn(array($selected_option));

		$this->assertSame($element, $element->deselectByValue('TX', true));
	}

	/**
	 * Returns options, used for "select*" method testing.
	 *
	 * @param boolean $is_multiple Is multiple selection allowed.
	 *
	 * @return SelectOption[]
	 */
	protected function getSelectOptions($is_multiple)
	{
		if ( $is_multiple ) {
			$option1 = $this->createOption(false, 1);
			$option2 = $this->createOption(false, 1, true);
		}
		else {
			$option1 = $this->createOption(false, 1);
			$option2 = $this->createOption(false, 0);
		}

		return array($option1, $option2);
	}

	public function testSelectAll()
	{
		/* @var $element Select */
		$element = $this->mockElement(array('getOptions', 'isMultiple'));
		$element->shouldReceive('isMultiple')->once()->andReturn(true);
		$element->shouldReceive('getOptions')->andReturn($this->getSelectOptions(true));

		$this->assertSame($element, $element->selectAll());
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\SelectException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\SelectException::TYPE_NOT_MULTISELECT
	 * @expectedExceptionMessage You may only deselect all options of a multi-select
	 */
	public function testSelectAllNotIsMultiple()
	{
		/* @var $element Select */
		$element = $this->mockElement(array('isMultiple'));
		$element->shouldReceive('isMultiple')->once()->andReturn(false);

		$this->assertSame($element, $element->selectAll());
	}

	public function testSetSelected()
	{
		/* @var $element Select */
		$element = $this->mockElement(array('isMultiple', 'getOptions'));
		$element->shouldReceive('isMultiple')->once()->andReturn(true);

		$option1 = $this->createOption(false, 1, false);
		$option1->shouldReceive('getValue')->andReturn(1);
		$option2 = $this->createOption(false, 0, true);
		$option2->shouldReceive('getValue')->andReturn(3);

		$element->shouldReceive('getOptions')->andReturn(array($option1, $option2));

		$this->assertSame($element, $element->setSelected(array(1, 2)));
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\SelectException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\SelectException::TYPE_NOT_MULTISELECT
	 * @expectedExceptionMessage You may only deselect all options of a multi-select
	 */
	public function testSetSelectedNotIsMultiple()
	{
		/* @var $element Select */
		$element = $this->mockElement(array('isMultiple'));
		$element->shouldReceive('isMultiple')->once()->andReturn(false);

		$this->assertSame($element, $element->setSelected(array()));
	}

	public function testDeselectAll()
	{
		$selected_option = $this->createOption(true);
		$selected_option->shouldReceive('deselect')->once();

		/* @var $element Select */
		$element = $this->mockElement(array('isMultiple', 'getSelectedOptions'));
		$element->shouldReceive('isMultiple')->once()->andReturn(true);
		$element->shouldReceive('getSelectedOptions')->andReturn(array($selected_option));

		$this->assertSame($element, $element->deselectAll());
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\SelectException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\SelectException::TYPE_NOT_MULTISELECT
	 * @expectedExceptionMessage You may only deselect all options of a multi-select
	 */
	public function testDeselectAllNotIsMultiple()
	{
		/* @var $element Select */
		$element = $this->mockElement(array('isMultiple'));
		$element->shouldReceive('isMultiple')->once()->andReturn(false);

		$this->assertSame($element, $element->deselectAll());
	}

	/**
	 * @dataProvider setValueDataProvider
	 */
	public function testSetValue($value, $final_value, $setter_method)
	{
		/* @var $element Select */
		$element = $this->mockElement(array($setter_method));
		$element->shouldReceive($setter_method)->with($final_value)->once()->andReturn($element);

		$this->assertSame($element, $element->setValue($value));
	}

	public function setValueDataProvider()
	{
		return array(
			array(array('V1'), array('V1'), 'setSelected'),
			array(5, '5', 'selectByValue'),
		);
	}

	/**
	 * Returns existing element.
	 *
	 * @return Select
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

	/**
	 * Creates an option.
	 *
	 * @param boolean      $selected         Selected.
	 * @param integer|null $select_times     How much times `select` method expected to be called.
	 * @param boolean|null $append_selection Append to current selection.
	 *
	 * @return SelectOption
	 */
	protected function createOption($selected, $select_times = null, $append_selection = false)
	{
		$option = m::mock(self::SELECT_OPTION_CLASS);
		$option->shouldReceive('isSelected')->andReturn($selected);
		$option->shouldReceive('getXpath')->andReturn('XPATH');

		if ( isset($select_times) ) {
			$option->shouldReceive('select')->with($append_selection)->times($select_times)->andReturn($option);
		}

		return $option;
	}

	/**
	 * Checks, that found options are correct ones.
	 *
	 * @param array|SelectOption[] $options Options.
	 *
	 * @return void
	 */
	protected function assertValidOptions(array $options)
	{
		$this->assertCount(1, $options);
		$this->assertInstanceOf(self::SELECT_OPTION_CLASS, $options[0]);
		$this->assertEquals('XPATH', $options[0]->getXpath());
	}

	public function trueFalseDataProvider()
	{
		return array(
			array(false),
			array(true),
		);
	}

}
