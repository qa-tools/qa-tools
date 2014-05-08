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


use Mockery as m;
use aik099\QATools\HtmlElements\Element\Radio;
use aik099\QATools\HtmlElements\Element\RadioGroup;

class RadioGroupTest extends TypifiedElementTest
{

	const RADIO_CLASS = '\\aik099\\QATools\\HtmlElements\\Element\\Radio';

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\RadioGroup';
		}

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @param array  $xpath_expressions Xpath expressions to be queried.
	 * @param string $radio_name        Radio button name.
	 *
	 * @return void
	 * @dataProvider getButtonsDataProvider
	 */
	public function testGetButtons(array $xpath_expressions, $radio_name)
	{
		$this->webElement->shouldReceive('getAttribute')->with('name')->once()->andReturn($radio_name);
		$this->selectorsHandler->shouldReceive('xpathLiteral')->with($radio_name)->andReturn("'" . $radio_name . "'");

		foreach ( $xpath_expressions as $xpath_expression ) {
			$expected = crc32($xpath_expression);
			$node_element = m::mock('\\Behat\\Mink\\Element\\NodeElement');
			$node_element->shouldReceive('getXpath')->andReturn($expected);
			$node_element->shouldReceive('getSession')->andReturn($this->session);

			$this->selectorsHandler
				->shouldReceive('selectorToXpath')
				->with('se', array('xpath' => $expected))
				->andReturn($expected);
			$this->webElement
				->shouldReceive('findAll')
				->with('xpath', $xpath_expression)
				->once()
				->andReturn(array($node_element));
		}

		$buttons = $this->getElement()->getButtons();

		$this->assertCount(count($xpath_expressions), $buttons);

		foreach ( $buttons as $index => $button ) {
			$this->assertInstanceOf(self::RADIO_CLASS, $button);
			$this->assertEquals(crc32($xpath_expressions[$index]), $button->getXpath());
		}
	}

	/**
	 * Data provider for "getButtons" method testing.
	 *
	 * @return array
	 */
	public function getButtonsDataProvider()
	{
		return array(
			array(
				array(
					'self::*',
					"following::input[@type = 'radio']",
					"preceding::input[@type = 'radio']",
				),
				null,
			),
			array(
				array(
					'self::*',
					"following::input[@type = 'radio' and @name = 'RN']",
					"preceding::input[@type = 'radio' and @name = 'RN']",
				),
				'RN',
			),
		);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testHasSelectedButtonNotFound()
	{
		$this->assertFalse($this->mockElement()->hasSelectedButton());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testHasSelectedButtonFound()
	{
		$radio = m::mock(self::RADIO_CLASS);
		$radio->shouldReceive('isSelected')->once()->andReturn(true);

		$this->assertTrue($this->mockElement(array(), array($radio))->hasSelectedButton());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\HtmlElements\Exception\RadioGroupException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\RadioGroupException::TYPE_NOT_SELECTED
	 */
	public function testGetSelectedButtonNotFound()
	{
		$this->mockElement()->getSelectedButton();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetSelectedButtonFound()
	{
		$radio = m::mock(self::RADIO_CLASS);
		$radio->shouldReceive('isSelected')->once()->andReturn(true);

		$this->assertSame($radio, $this->mockElement(array(), array($radio))->getSelectedButton());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\HtmlElements\Exception\RadioGroupException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\RadioGroupException::TYPE_NOT_FOUND
	 */
	public function testSelectButtonByLabelTextNotFound()
	{
		$this->mockElement()->selectButtonByLabelText('ANY');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSelectButtonByLabelTextFound()
	{
		$radio = m::mock(self::RADIO_CLASS);
		$radio->shouldReceive('getLabelText')->once()->andReturn('EXAMPLE TEXT');
		$radio->shouldReceive('select')->once()->andReturnNull();

		$element = $this->mockElement(array(), array($radio));
		$this->assertSame($element, $element->selectButtonByLabelText('LE T'));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\HtmlElements\Exception\RadioGroupException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\RadioGroupException::TYPE_NOT_FOUND
	 */
	public function testSelectButtonByValueNotFound()
	{
		$this->mockElement()->selectButtonByValue('ANY');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSelectButtonByValueFound()
	{
		$radio = m::mock(self::RADIO_CLASS);
		$radio->shouldReceive('getValue')->once()->andReturn('V1');
		$radio->shouldReceive('select')->once()->andReturnNull();

		$element = $this->mockElement(array(), array($radio));
		$this->assertSame($element, $element->selectButtonByValue('V1'));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\HtmlElements\Exception\RadioGroupException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\RadioGroupException::TYPE_NOT_FOUND
	 */
	public function testSelectButtonByIndexNotFound()
	{
		$this->mockElement()->selectButtonByIndex(100);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSelectButtonByIndexFound()
	{
		$radio = m::mock(self::RADIO_CLASS);
		$radio->shouldReceive('select')->once()->andReturnNull();

		$element = $this->mockElement(array(), array($radio));
		$this->assertSame($element, $element->selectButtonByIndex(0));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetValue()
	{
		/* @var $element RadioGroup */
		$element = parent::mockElement(array('selectButtonByValue'));
		$element->shouldReceive('selectButtonByValue')->with('555')->once()->andReturn($element);

		$this->assertSame($element, $element->setValue(555));
	}

	/**
	 * Mocks element.
	 *
	 * @param array         $methods       Methods to mock.
	 * @param array|Radio[] $radio_buttons Radio buttons.
	 *
	 * @return RadioGroup
	 */
	protected function mockElement(array $methods = array(), $radio_buttons = array())
	{
		$methods[] = 'getButtons';
		$element = parent::mockElement($methods);
		$element->shouldReceive('getButtons')->once()->andReturn($radio_buttons);

		return $element;
	}

	/**
	 * Returns existing element.
	 *
	 * @return RadioGroup
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
