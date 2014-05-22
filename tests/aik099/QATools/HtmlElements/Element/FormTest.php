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


use aik099\QATools\HtmlElements\Element\Form;
use Behat\Mink\Element\NodeElement;
use Mockery as m;

class FormTest extends AbstractElementContainerTest
{

	const TYPIFIED_ELEMENT_CLASS = '\\aik099\\QATools\\HtmlElements\\Element\\AbstractTypifiedElement';

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\Form';
		}

		parent::setUp();
	}

	public function testFill()
	{
		/** @var Form $form */
		$form = $this->mockElement(array('typify', 'getNodeElements', 'setValue'));

		$this->createFillFixture($form, 'f1', 'v1');
		$this->createFillFixture($form, 'f2', 'v2');

		$form_data = array('f1' => 'v1', 'f2' => 'v2');
		$this->assertSame($form, $form->fill($form_data));
	}

	/**
	 * Creates fixture for "Form::fill" method testing.
	 *
	 * @param Form   $form        Form.
	 * @param string $field_name  Field name.
	 * @param string $field_value Field value.
	 *
	 * @return void
	 */
	protected function createFillFixture(Form $form, $field_name, $field_value)
	{
		$node_elements = array($this->createNodeElement());
		$typified_element = m::mock(self::TYPIFIED_ELEMENT_CLASS);
		$form->shouldReceive('getNodeElements')->with($field_name)->once()->andReturn($node_elements);
		$form->shouldReceive('typify')->with(m::mustBe($node_elements))->once()->andReturn($typified_element);
		$form->shouldReceive('setValue')->with($typified_element, $field_value)->once()->andReturn($form);
	}

	/**
	 * @expectedException \aik099\QATools\HtmlElements\Exception\FormException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\FormException::TYPE_NOT_FOUND
	 * @expectedExceptionMessage Form field "field-name" not found
	 */
	public function testGetNodeElementsFailure()
	{
		if ( method_exists($this->selectorsHandler, 'xpathLiteral') ) {
			$this->selectorsHandler->shouldReceive('xpathLiteral')->with('field-name')->once()->andReturn("'field-name'");
			$this->webElement->shouldReceive('findAll')->with('named', array('field', "'field-name'"))->once()->andReturn(array());
		}
		else {
			$this->webElement->shouldReceive('findAll')->with('named', array('field', 'field-name'))->once()->andReturn(array());
		}

		$this->getElement()->getNodeElements('field-name');
	}

	public function testGetNodeElementsSuccess()
	{
		$node_elements = array($this->createNodeElement());

		if ( method_exists($this->selectorsHandler, 'xpathLiteral') ) {
			$this->selectorsHandler->shouldReceive('xpathLiteral')->with('field-name')->once()->andReturn("'field-name'");

			$this->webElement
				->shouldReceive('findAll')
				->with('named', array('field', "'field-name'"))
				->once()
				->andReturn($node_elements);
		}
		else {
			$this->webElement
				->shouldReceive('findAll')
				->with('named', array('field', 'field-name'))
				->once()
				->andReturn($node_elements);
		}

		$found_elements = $this->getElement()->getNodeElements('field-name');

		$this->assertSame($node_elements, $found_elements);
		$this->assertEquals('XPATH', $found_elements[0]->getXpath());
	}

	/**
	 * @dataProvider typifyDataProvider
	 */
	public function testTypify($tag_name, $input_type, $element_class)
	{
		// 2 times for radio, because radio is wrapped within a collection and is asserted.
		$call_count = $input_type == 'radio' ? 2 : 1;

		$node_element = new NodeElement('XPATH', $this->session);
		$this->driver->shouldReceive('getTagName')->with('XPATH')->times($call_count)->andReturn($tag_name);

		if ( $tag_name == 'input' ) {
			$this->driver->shouldReceive('getAttribute')->with('XPATH', 'type')->times($call_count)->andReturn($input_type);
		}

		$form_element = $this->getElement()->typify(array($node_element));
		$this->assertInstanceOf($element_class, $form_element);
	}

	public function typifyDataProvider()
	{
		return array(
			'checkbox' => array('input', 'checkbox', '\\aik099\\QATools\\HtmlElements\\Element\\Checkbox'),
			'radio' => array('input', 'radio', '\\aik099\\QATools\\HtmlElements\\Element\\RadioGroup'),
			'file' => array('input', 'file', '\\aik099\\QATools\\HtmlElements\\Element\\FileInput'),
			'text-no-type' => array('input', null, '\\aik099\\QATools\\HtmlElements\\Element\\TextInput'),
			'text' => array('input', 'text', '\\aik099\\QATools\\HtmlElements\\Element\\TextInput'),
			'password' => array('input', 'password', '\\aik099\\QATools\\HtmlElements\\Element\\TextInput'),
			'color' => array('input', 'color', '\\aik099\\QATools\\HtmlElements\\Element\\TextInput'),
			'select' => array('select', null, '\\aik099\\QATools\\HtmlElements\\Element\\Select'),
			'textarea' => array('textarea', null, '\\aik099\\QATools\\HtmlElements\\Element\\TextInput'),
		);
	}

	/**
	 * @expectedException \aik099\QATools\HtmlElements\Exception\FormException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\FormException::TYPE_UNKNOWN_FIELD
	 * @expectedExceptionMessage Unable create typified element for element (class: aik099\QATools\PageObject\Element\WebElement; xpath: XPATH)
	 */
	public function testTypifyFailure()
	{
		$node_element = $this->createNodeElement();
		$node_element->shouldReceive('getTagName')->withNoArgs()->once()->andReturn('article');

		$this->getElement()->typify(array($node_element));
	}

	/**
	 * @expectedException \aik099\QATools\HtmlElements\Exception\FormException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\FormException::TYPE_READONLY_FIELD
	 * @expectedExceptionMessage Element ELEMENT NAME doesn't support value changing
	 */
	public function testSetValueFailure()
	{
		$typified_element = m::mock(self::TYPIFIED_ELEMENT_CLASS);
		$typified_element->shouldReceive('__toString')->withNoArgs()->once()->andReturn('ELEMENT NAME');

		$this->getElement()->setValue($typified_element, 'the value');
	}

	public function testSetValueSuccess()
	{
		$mock_parts = array(
			self::TYPIFIED_ELEMENT_CLASS,
			'\\aik099\\QATools\\HtmlElements\\Element\\ISimpleSetter',
		);

		$typified_element = m::mock(implode(', ', $mock_parts));
		$typified_element->shouldReceive('setValue')->with('the value')->once()->andReturn($typified_element);

		$form = $this->getElement();
		$this->assertSame($form, $form->setValue($typified_element, 'the value'));
	}

	public function testSubmit()
	{
		$this->webElement->shouldReceive('submit')->withNoArgs()->once()->andReturnNull();

		$form = $this->getElement();
		$this->assertSame($form, $form->submit());
	}

	/**
	 * Returns existing element.
	 *
	 * @return Form
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
