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


use QATools\QATools\HtmlElements\Element\Button;

class ButtonTest extends AbstractTypifiedElementTest
{

	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\QATools\\QATools\\HtmlElements\\Element\\Button';
		}

		$this->expectedTagName = 'button';

		$this->ignoreExpectTypifiedNodeCheck[] = 'testAssertWrappedElementTagNotMatching';
		$this->ignoreExpectTypifiedNodeCheck[] = 'testAssertWrappedElementAttributeNotMatching';

		parent::setUp();
	}

	public function testClick()
	{
		$this->webElement->shouldReceive('click')->once();

		$this->assertSame($this->typifiedElement, $this->getElement()->click());
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\TypifiedElementException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\TypifiedElementException::TYPE_INCORRECT_WRAPPED_ELEMENT
	 * @expectedExceptionMessageRegExp /^Wrapped element "Mockery.*?" does not match "QATools\\QATools\\HtmlElements\\Element\\Button" criteria$/
	 */
	public function testAssertWrappedElementTagNotMatching()
	{
		$this->expectDriverGetTagName($this->expectedTagName . '_postfix');
		$this->expectWebElementGetTagName($this->expectedTagName . '_postfix');

		$this->expectDriverGetAttribute(array('role' => 'link'));
		$this->expectWebElementGetAttribute(array('role' => 'link'));

		$this->typifiedElement = $this->createElement();

		$this->assertSame($this->webElement, $this->typifiedElement->getWrappedElement());
	}

	/**
	 * @expectedException \QATools\QATools\HtmlElements\Exception\TypifiedElementException
	 * @expectedExceptionCode \QATools\QATools\HtmlElements\Exception\TypifiedElementException::TYPE_INCORRECT_WRAPPED_ELEMENT
	 * @expectedExceptionMessageRegExp /^Wrapped element "Mockery.*?" does not match "QATools\\QATools\\HtmlElements\\Element\\Button" criteria$/
	 */
	public function testAssertWrappedElementAttributeNotMatching()
	{
		$this->expectDriverGetTagName('input');
		$this->expectWebElementGetTagName('input');

		$this->expectDriverGetAttribute(array('type' => 'checkbox', 'role' => 'link'));
		$this->expectWebElementGetAttribute(array('type' => 'checkbox', 'role' => 'link'));

		$this->typifiedElement = $this->createElement();

		$this->assertSame($this->webElement, $this->typifiedElement->getWrappedElement());
	}

	/**
	 * @dataProvider assertWrappedElementAttributeMatchingDataProvider
	 */
	public function testAssertWrappedElementAttributeMatching($attributes)
	{
		$this->expectDriverGetTagName('input');
		$this->expectWebElementGetTagName('input');

		$this->expectDriverGetAttribute($attributes);
		$this->expectWebElementGetAttribute($attributes);

		$this->typifiedElement = $this->createElement();

		$this->assertSame($this->webElement, $this->typifiedElement->getWrappedElement());
	}

	public function assertWrappedElementAttributeMatchingDataProvider()
	{
		return array(
			array(array('type' => 'button', 'role' => 'link')),
			array(array('type' => 'submit', 'role' => 'link')),
			array(array('type' => 'checkbox', 'role' => 'button')),
		);
	}

	/**
	 * Returns existing element.
	 *
	 * @return Button
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
