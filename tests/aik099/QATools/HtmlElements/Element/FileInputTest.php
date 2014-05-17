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


use aik099\QATools\HtmlElements\Element\FileInput;
use Mockery as m;

class FileInputTest extends AbstractTypifiedElementTest
{

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\FileInput';
		}

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @param mixed   $attribute_value Attribute value.
	 * @param boolean $multiple        Is multiple.
	 *
	 * @return void
	 * @dataProvider isMultipleDataProvider
	 */
	public function testIsMultiple($attribute_value, $multiple)
	{
		$this->webElement->shouldReceive('hasAttribute')->with('multiple')->once()->andReturn($attribute_value);

		$this->assertSame($multiple, $this->getElement()->isMultiple());
	}

	/**
	 * Provides attribute values for "isMultiple" test.
	 *
	 * @return array
	 */
	public function isMultipleDataProvider()
	{
		return array(
			array(false, false),
			array(true, true),
		);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\HtmlElements\Exception\FileInputException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\FileInputException::TYPE_FILE_NOT_FOUND
	 * @expectedExceptionMessage File "/non-existing-file.txt" doesn't exist
	 */
	public function testSetNonExistingFileToUpload()
	{
		$this->getElement()->setFileToUpload('/non-existing-file.txt');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetExistingFileToUpload()
	{
		$element = $this->getElement();
		$this->webElement->shouldReceive('attachFile')->with(__FILE__)->once();

		$this->assertSame($element, $element->setFileToUpload(__FILE__));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetValue()
	{
		/* @var $element FileInput */
		$element = $this->mockElement(array('setFileToUpload'));
		$element->shouldReceive('setFileToUpload')->with('file')->once()->andReturn($element);

		$this->assertSame($element, $element->setValue('file'));
	}

	/**
	 * Returns existing element.
	 *
	 * @return FileInput
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
