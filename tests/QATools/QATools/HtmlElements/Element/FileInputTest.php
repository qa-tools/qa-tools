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


use QATools\QATools\HtmlElements\Element\FileInput;
use Mockery as m;

class FileInputTest extends AbstractTypifiedElementTest
{

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\FileInput';
		}

		$this->expectedAttributes = array('type' => 'file');

		parent::setUpTest();
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

	public function testSetNonExistingFileToUpload()
	{
		$this->expectException('\QATools\QATools\HtmlElements\Exception\FileInputException');
		$this->expectExceptionCode(\QATools\QATools\HtmlElements\Exception\FileInputException::TYPE_FILE_NOT_FOUND);
		$this->expectExceptionMessage('File "/non-existing-file.txt" doesn\'t exist');

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
