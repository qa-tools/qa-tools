<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\Element;


use Mockery as m;
use tests\QATools\QATools\PageObject\Fixture\Element\ElementContainerChild;

class AbstractElementContainerTest extends WebElementTest
{

	protected function setUp()
	{
		parent::setUp();

		$this->elementClass = '\\tests\\QATools\\QATools\\PageObject\\Fixture\\Element\\ElementContainerChild';

		if ( $this->getName(false) != 'testFromNodeElementWithoutPageFactory' ) {
			$this->pageFactory->shouldReceive('initElementContainer')->once()->andReturn($this->pageFactory);
			$this->pageFactory->shouldReceive('initElements')->once()->andReturn($this->pageFactory);

			$decorator = m::mock('\\QATools\\QATools\\PageObject\\PropertyDecorator\\IPropertyDecorator');
			$this->pageFactory->shouldReceive('createDecorator')->once()->andReturn($decorator);
		}
	}

	public function testGetPageFactory()
	{
		$element = $this->createElement();
		$method = new \ReflectionMethod(get_class($element), 'getPageFactory');
		$method->setAccessible(true);

		$this->assertSame($this->pageFactory, $method->invoke($element));
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\ElementException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\ElementException::TYPE_PAGE_FACTORY_REQUIRED
	 * @expectedExceptionMessage Page factory is required to create this element
	 */
	public function testFromNodeElementWithoutPageFactory()
	{
		/* @var $element_class ElementContainerChild */
		$element_class = $this->elementClass;
		$element_class::fromNodeElement($this->createNodeElement());
	}

	/**
	 * Create element.
	 *
	 * @return ElementContainerChild
	 */
	protected function createElement()
	{
		return new $this->elementClass($this->createNodeElement(), $this->pageFactory);
	}

}
