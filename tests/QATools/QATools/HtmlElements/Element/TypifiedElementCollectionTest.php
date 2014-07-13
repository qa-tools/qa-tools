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
use QATools\QATools\HtmlElements\Element\AbstractTypifiedElementCollection;
use QATools\QATools\PageObject\Element\WebElement;
use tests\QATools\QATools\PageObject\Element\AbstractElementCollectionTestCase;

class TypifiedElementCollectionTest extends AbstractElementCollectionTestCase
{

	const WEB_ELEMENT_CLASS = '\\QATools\\QATools\\PageObject\\Element\\WebElement';

	/**
	 * Web Element.
	 *
	 * @var WebElement
	 */
	protected $webElement;

	protected function setUp()
	{
		if ( is_null($this->collectionClass) ) {
			$this->collectionClass = '\\tests\\QATools\\QATools\\HtmlElements\\Fixture\\Element\\TypifiedElementCollectionChild';
			$this->collectionElementClass = '\\QATools\\QATools\\HtmlElements\\Element\\TextInput';
		}

		$this->webElement = m::mock(self::WEB_ELEMENT_CLASS);
		$this->webElement->shouldReceive('getSession')->withNoArgs()->andReturn($this->session);

		parent::setUp();
	}

	public function testSetName()
	{
		$expected = 'OK';
		$this->assertSame($this->element, $this->element->setName($expected));
		$this->assertEquals($expected, $this->element->getName());
	}

	public function testSetContainer()
	{
		$container = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');

		$element = $this->createValidElementMock();
		$element->shouldReceive('setContainer')->with($container)->once();

		$this->element[] = $element;

		$this->assertSame($this->element, $this->element->setContainer($container));
	}

	public function testGetContainer()
	{
		$container = m::mock('\\QATools\\QATools\\PageObject\\ISearchContext');
		$this->element->setContainer($container);

		$this->assertEquals($container, $this->element->getContainer());
	}

	public function testDefaultElementClass()
	{
		$collection = new DummyTypifiedElementCollection();
		$collection[] = m::mock('\\QATools\\QATools\\HtmlElements\\Element\\TextInput');
		$this->assertCount(1, $collection);
	}

}

class DummyTypifiedElementCollection extends AbstractTypifiedElementCollection
{

}
