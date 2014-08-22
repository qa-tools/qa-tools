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


use Mockery as m;
use QATools\QATools\HtmlElements\Element\RadioButton;
use QATools\QATools\HtmlElements\Element\RadioGroup;
use Mockery\MockInterface;

class RadioGroupTest extends TypifiedElementCollectionTest
{

	protected function setUp()
	{
		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\QATools\\QATools\\HtmlElements\\Element\\RadioGroup';
			$this->collectionElementClass = '\\QATools\\QATools\\HtmlElements\\Element\\RadioButton';
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

	public function testHasSelectedButtonNotFound()
	{
		$this->assertFalse($this->mockCollection()->hasSelectedButton());
	}

	public function testHasSelectedButtonFound()
	{
		$radio = $this->createRadioButton();
		$radio->shouldReceive('isSelected')->once()->andReturn(true);

		$this->assertTrue($this->mockCollection(array(), array($radio))->hasSelectedButton());
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\RadioGroupException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\RadioGroupException::TYPE_NOT_SELECTED
	 * @expectedExceptionMessage No selected button
	 */
	public function testGetSelectedButtonNotFound()
	{
		$this->mockCollection()->getSelectedButton();
	}

	public function testGetSelectedButtonFound()
	{
		$radio = $this->createRadioButton();
		$radio->shouldReceive('isSelected')->once()->andReturn(true);

		$this->assertSame($radio, $this->mockCollection(array(), array($radio))->getSelectedButton());
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\RadioGroupException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\RadioGroupException::TYPE_NOT_FOUND
	 * @expectedExceptionMessage Cannot locate radio button with label text containing: ANY
	 */
	public function testSelectButtonByLabelTextNotFound()
	{
		$this->mockCollection()->selectButtonByLabelText('ANY');
	}

	public function testSelectButtonByLabelTextFound()
	{
		$radio = $this->createRadioButton();
		$radio->shouldReceive('getLabelText')->once()->andReturn('EXAMPLE TEXT');
		$radio->shouldReceive('select')->once();

		$element = $this->mockCollection(array(), array($radio));
		$this->assertSame($element, $element->selectButtonByLabelText('LE T'));
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\RadioGroupException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\RadioGroupException::TYPE_NOT_FOUND
	 * @expectedExceptionMessage Cannot locate radio button with value: ANY
	 */
	public function testSelectButtonByValueNotFound()
	{
		$this->mockCollection()->selectButtonByValue('ANY');
	}

	public function testSelectButtonByValueFound()
	{
		$radio = $this->createRadioButton();
		$radio->shouldReceive('getValue')->once()->andReturn('V1');
		$radio->shouldReceive('select')->once();

		$element = $this->mockCollection(array(), array($radio));
		$this->assertSame($element, $element->selectButtonByValue('V1'));
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\RadioGroupException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\RadioGroupException::TYPE_NOT_FOUND
	 * @expectedExceptionMessage Cannot locate radio button with index: 100
	 */
	public function testSelectButtonByIndexNotFound()
	{
		$this->mockCollection()->selectButtonByIndex(100);
	}

	public function testSelectButtonByIndexFound()
	{
		$radio = $this->createRadioButton();
		$radio->shouldReceive('select')->once();

		$element = $this->mockCollection(array(), array($radio));
		$this->assertSame($element, $element->selectButtonByIndex(0));
	}

	public function testSetValue()
	{
		/* @var $element RadioGroup */
		$element = $this->mockCollection(array('selectButtonByValue'));
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
