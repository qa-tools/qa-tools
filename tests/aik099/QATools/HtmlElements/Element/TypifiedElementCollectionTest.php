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


use aik099\QATools\HtmlElements\Element\AbstractTypifiedElementCollection;
use Mockery as m;
use aik099\QATools\PageObject\Element\WebElement;
use tests\aik099\QATools\PageObject\Element\AbstractElementCollectionTestCase;

class TypifiedElementCollectionTest extends AbstractElementCollectionTestCase
{

	const WEB_ELEMENT_CLASS = '\\aik099\\QATools\\PageObject\\Element\\WebElement';

	/**
	 * Web Element.
	 *
	 * @var WebElement
	 */
	protected $webElement;

	/**
	 * Prepares mocks for object creation.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\tests\\aik099\\QATools\\HtmlElements\\Fixture\\Element\\TypifiedElementCollectionChild';
			$this->collectionElementClass = '\\aik099\\QATools\\HtmlElements\\Element\\TextInput';
		}

		$this->webElement = m::mock(self::WEB_ELEMENT_CLASS);
		$this->webElement->shouldReceive('getSession')->withNoArgs()->andReturn($this->session);

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetName()
	{
		$expected = 'OK';
		$this->assertSame($this->element, $this->element->setName($expected));
		$this->assertEquals($expected, $this->element->getName());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetContainer()
	{
		$container = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');

		$this->assertSame($this->element, $this->element->setContainer($container));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetContainer()
	{
		$container = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');
		$this->element->setContainer($container);

		$this->assertEquals($container, $this->element->getContainer());
	}

	public function testDefaultElementClass()
	{
		$collection = new DummyTypifiedElementCollection();
		$collection[] = m::mock('\\aik099\\QATools\\HtmlElements\\Element\\TextInput');
		$this->assertCount(1, $collection);
	}

}

class DummyTypifiedElementCollection extends AbstractTypifiedElementCollection
{


}
