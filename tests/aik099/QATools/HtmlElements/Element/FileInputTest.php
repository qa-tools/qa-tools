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

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\FileInput';
		}

		parent::setUp();
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

	/**
	 * @expectedException \aik099\QATools\HtmlElements\Exception\FileInputException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\FileInputException::TYPE_FILE_NOT_FOUND
	 * @expectedExceptionMessage File "/non-existing-file.txt" doesn't exist
	 */
	public function testSetNonExistingFileToUpload()
	{
		$this->getElement()->setFileToUpload('/non-existing-file.txt');
	}

	public function testSetExistingFileToUpload()
	{
		$element = $this->getElement();
		$this->webElement->shouldReceive('attachFile')->with(__FILE__)->once();

		$this->assertSame($element, $element->setFileToUpload(__FILE__));
	}

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
