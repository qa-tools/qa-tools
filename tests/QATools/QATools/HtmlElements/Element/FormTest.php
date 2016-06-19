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


use QATools\QATools\HtmlElements\Element\Form;
use Behat\Mink\Element\NodeElement;
use Mockery as m;

class FormTest extends AbstractElementContainerTest
{

	const TYPIFIED_ELEMENT_CLASS = '\\QATools\\QATools\\HtmlElements\\Element\\AbstractTypifiedElement';

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\Form';
		}

		$this->ignoreExpectTypifiedNodeCheck[] = 'testTypify';

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
	 * @expectedException \QATools\QATools\HtmlElements\Exception\FormException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\FormException::TYPE_NOT_FOUND
	 * @expectedExceptionMessage Form field "field-name" not found
	 */
	public function testGetNodeElementsFailure()
	{
		if ( method_exists($this->selectorsHandler, 'xpathLiteral') ) {
			$this->selectorsHandler->shouldReceive('xpathLiteral')
				->with('field-name')
				->once()
				->andReturn("'field-name'");
			$this->webElement->shouldReceive('findAll')
				->with('named', array('field', "'field-name'"))
				->once()
				->andReturn(array());
		}
		else {
			$this->webElement->shouldReceive('findAll')
				->with('named', array('field', 'field-name'))
				->once()
				->andReturn(array());
		}

		$this->getElement()->getNodeElements('field-name');
	}

	public function testGetNodeElementsSuccess()
	{
		$node_elements = array($this->createNodeElement());

		if ( method_exists($this->selectorsHandler, 'xpathLiteral') ) {
			$this->selectorsHandler->shouldReceive('xpathLiteral')
				->with('field-name')
				->once()
				->andReturn("'field-name'");

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
		// 3 times for radio, because radio is wrapped within a collection and is asserted.
		$call_count = $input_type == 'radio' ? 3 : 2;

		$node_element = new NodeElement('XPATH', $this->session);
		$this->driver->shouldReceive('getTagName')->with('XPATH')->times($call_count)->andReturn($tag_name);

		if ( $tag_name == 'input' ) {
			$this->driver
				->shouldReceive('getAttribute')
				->with('XPATH', 'type')->atLeast($call_count - 1)
				->andReturn($input_type);
		}

		$this->typifiedElement = $this->createElement();

		$form_element = $this->getElement()->typify(array($node_element));
		$this->assertInstanceOf($element_class, $form_element);
	}

	public function typifyDataProvider()
	{
		return array(
			'checkbox' => array('input', 'checkbox', '\\QATools\\QATools\\HtmlElements\\Element\\Checkbox'),
			'radio' => array('input', 'radio', '\\QATools\\QATools\\HtmlElements\\Element\\RadioGroup'),
			'file' => array('input', 'file', '\\QATools\\QATools\\HtmlElements\\Element\\FileInput'),
			'text-no-type' => array('input', null, '\\QATools\\QATools\\HtmlElements\\Element\\TextInput'),
			'text' => array('input', 'text', '\\QATools\\QATools\\HtmlElements\\Element\\TextInput'),
			'password' => array('input', 'password', '\\QATools\\QATools\\HtmlElements\\Element\\TextInput'),
			'color' => array('input', 'color', '\\QATools\\QATools\\HtmlElements\\Element\\TextInput'),
			'select' => array('select', null, '\\QATools\\QATools\\HtmlElements\\Element\\Select'),
			'textarea' => array('textarea', null, '\\QATools\\QATools\\HtmlElements\\Element\\TextInput'),
		);
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\FormException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\FormException::TYPE_UNKNOWN_FIELD
	 * @expectedExceptionMessage Unable create typified element for element (class: QATools\QATools\PageObject\Element\WebElement; xpath: WRONG_TAG)
	 */
	public function testTypifyFailure()
	{
		$node_element = $this->createNodeElement('WRONG_TAG');
		$this->driver->shouldReceive('getTagName')->with('WRONG_TAG')->once()->andReturn('article');

		$this->getElement()->typify(array($node_element));
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\FormException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\FormException::TYPE_READONLY_FIELD
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
			'\\QATools\\QATools\\HtmlElements\\Element\\ISimpleSetter',
		);

		$typified_element = m::mock(implode(', ', $mock_parts));
		$typified_element->shouldReceive('setValue')->with('the value')->once()->andReturn($typified_element);

		$form = $this->getElement();
		$this->assertSame($form, $form->setValue($typified_element, 'the value'));
	}

	public function testSubmit()
	{
		$this->webElement->shouldReceive('submit')->withNoArgs()->once();

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
