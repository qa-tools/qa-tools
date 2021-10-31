<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\BEM\Element;


use QATools\QATools\BEM\Element\Element;
use QATools\QATools\PageObject\Element\WebElement;
use Mockery as m;
use Mockery\MockInterface;

class ElementTest extends PartTestCase
{

	/**
	 * WebElement.
	 *
	 * @var WebElement|MockInterface
	 */
	private $_webElement;

	/**
	 * @before
	 */
	protected function setUpTest()
	{
		parent::setUpTest();

		$this->partClass = '\\QATools\\QATools\\BEM\\Element\\Element';
		$this->_webElement = m::mock('\\QATools\\QATools\\PageObject\\Element\\WebElement');
	}

	public function testConstructor()
	{
		$element = $this->createPart();

		$this->assertEquals('element-name', $element->getName());
		$this->assertSame($this->_webElement, $element->getWrappedElement());
	}

	/**
	 * Creates part to be tested.
	 *
	 * @return Element
	 */
	protected function createPart()
	{
		return new $this->partClass('element-name', $this->_webElement);
	}

}
