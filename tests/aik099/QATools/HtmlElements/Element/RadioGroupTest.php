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
use aik099\QATools\HtmlElements\Element\RadioButton;
use aik099\QATools\HtmlElements\Element\RadioGroup;
use Mockery\MockInterface;

class RadioGroupTest extends TypifiedElementCollectionTest
{

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\aik099\\QATools\\HtmlElements\\Element\\RadioGroup';
			$this->collectionElementClass = '\\aik099\\QATools\\HtmlElements\\Element\\RadioButton';
		}

		parent::setUp();
	}

	/**
	 * Creates valid collection element mock.
	 *
	 * @return MockInterface
	 */
	protected function createValidElementMock()
	{
		$call_count = $this->getName(false) == 'testArrayAccessInterface' ? 2 : 1;

		$element = m::mock($this->collectionElementClass);
		$element->shouldReceive('getTagName')->times($call_count)->andReturn('input');
		$element->shouldReceive('getAttribute')->with('type')->times($call_count)->andReturn('radio');

		return $element;
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testHasSelectedButtonNotFound()
	{
		$this->assertFalse($this->mockCollection()->hasSelectedButton());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testHasSelectedButtonFound()
	{
		$radio = $this->createRadioButton();
		$radio->shouldReceive('isSelected')->once()->andReturn(true);

		$this->assertTrue($this->mockCollection(array(), array($radio))->hasSelectedButton());
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
		$this->mockCollection()->getSelectedButton();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetSelectedButtonFound()
	{
		$radio = $this->createRadioButton();
		$radio->shouldReceive('isSelected')->once()->andReturn(true);

		$this->assertSame($radio, $this->mockCollection(array(), array($radio))->getSelectedButton());
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
		$this->mockCollection()->selectButtonByLabelText('ANY');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSelectButtonByLabelTextFound()
	{
		$radio = $this->createRadioButton();
		$radio->shouldReceive('getLabelText')->once()->andReturn('EXAMPLE TEXT');
		$radio->shouldReceive('select')->once()->andReturnNull();

		$element = $this->mockCollection(array(), array($radio));
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
		$this->mockCollection()->selectButtonByValue('ANY');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSelectButtonByValueFound()
	{
		$radio = $this->createRadioButton();
		$radio->shouldReceive('getValue')->once()->andReturn('V1');
		$radio->shouldReceive('select')->once()->andReturnNull();

		$element = $this->mockCollection(array(), array($radio));
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
		$this->mockCollection()->selectButtonByIndex(100);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSelectButtonByIndexFound()
	{
		$radio = $this->createRadioButton();
		$radio->shouldReceive('select')->once()->andReturnNull();

		$element = $this->mockCollection(array(), array($radio));
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
		$element = parent::mockCollection(array('selectButtonByValue'));
		$element->shouldReceive('selectButtonByValue')->with('555')->once()->andReturn($element);

		$this->assertSame($element, $element->setValue(555));
	}

	/**
	 * Creates a radio button.
	 *
	 * @return RadioButton
	 */
	protected function createRadioButton()
	{
		$radio = m::mock($this->collectionElementClass);
		$radio->shouldReceive('getTagName')->once()->andReturn('input');
		$radio->shouldReceive('getAttribute')->with('type')->once()->andReturn('radio');

		return $radio;
	}

	/**
	 * Mocks element.
	 *
	 * @param array               $methods  Methods to mock.
	 * @param array|RadioButton[] $elements Radio buttons.
	 *
	 * @return RadioGroup
	 */
	protected function mockCollection(array $methods = array(), array $elements = array())
	{
		return parent::mockCollection($methods, $elements);
	}

}
